<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\HopDong;
use App\Models\Phong;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HopDongController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $loc = $request->input('loc'); // hieuluc | saphethan | hethan

        $hopDongs = HopDong::with(['cuDan', 'phong.tang.toa'])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->whereHas('cuDan', fn ($u) => $u->where('ten', 'like', "%{$q}%"))
                ->orWhereHas('phong', fn ($p) => $p->where('ten_phong', 'like', "%{$q}%"))))
            ->when($loc === 'hieuluc', fn ($query) => $query->hieuLuc())
            ->when($loc === 'saphethan', fn ($query) => $query->hieuLuc()
                ->whereDate('ngayketthuc', '<=', now()->addDays(30)->toDateString()))
            ->when($loc === 'hethan', fn ($query) => $query->whereDate('ngayketthuc', '<', now()->toDateString()))
            ->orderByDesc('ngaybatdau')
            ->paginate(10)->withQueryString();

        return view('quanly.hopdong.danh-sach', compact('hopDongs', 'q', 'loc'));
    }

    public function create()
    {
        $hopDong = new HopDong([
            'ngaybatdau' => now()->startOfMonth()->addMonth(),
            'ngayketthuc' => now()->startOfMonth()->addMonth()->addYear()->subDay(),
        ]);
        $cuDans = User::where('quyen', User::CU_DAN)->where('trangthai', User::HOAT_DONG)->orderBy('ten')->get();
        $phongs = Phong::with('tang.toa')->orderBy('ten_phong')->get();

        return view('quanly.hopdong.form', compact('hopDong', 'cuDans', 'phongs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => ['required', Rule::exists('users', 'id_user')->where('quyen', User::CU_DAN)],
            'id_phong' => 'required|exists:phong,id_phong',
            'noidung' => 'nullable|string|max:2000',
            'gia' => 'required|integer|min:100000',
            'ngaybatdau' => 'required|date',
            'ngayketthuc' => 'required|date|after:ngaybatdau',
        ]);

        // Một phòng không có 2 hợp đồng trùng thời gian
        $trung = HopDong::where('id_phong', $data['id_phong'])
            ->whereDate('ngaybatdau', '<=', $data['ngayketthuc'])
            ->whereDate('ngayketthuc', '>=', $data['ngaybatdau'])
            ->exists();
        if ($trung) {
            return back()->withErrors(['id_phong' => 'Phòng này đã có hợp đồng khác trong khoảng thời gian đã chọn.'])->withInput();
        }

        $data['id_ql'] = auth()->id();
        $hopDong = HopDong::create($data);

        // Phòng chưa có người ở thì ghi cư dân của hợp đồng vào phòng
        $phong = Phong::find($data['id_phong']);
        if (! $phong->id_user && ! Phong::where('id_user', $data['id_user'])->exists()) {
            $phong->update(['id_user' => $data['id_user']]);
        }

        return redirect()->route('quanly.hopdong.show', $hopDong)->with('success', 'Đã lập hợp đồng #' . $hopDong->id_hopdong . '.');
    }

    public function show(HopDong $hopdong)
    {
        $hopdong->load(['cuDan', 'quanLy', 'phong.tang.toa']);

        return view('quanly.hopdong.chi-tiet', ['hopDong' => $hopdong]);
    }
}
