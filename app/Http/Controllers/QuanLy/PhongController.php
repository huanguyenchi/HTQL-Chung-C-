<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\Phong;
use App\Models\Tang;
use App\Models\Toa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PhongController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $idToa = $request->input('id_toa');
        $idTang = $request->input('id_tang');

        $phongs = Phong::with(['tang.toa', 'cuDan'])
            ->when($idToa, fn ($query) => $query->whereHas('tang', fn ($t) => $t->where('id_toa', $idToa)))
            ->when($idTang, fn ($query) => $query->where('id_tang', $idTang))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('ten_phong', 'like', "%{$q}%")
                ->orWhereHas('cuDan', fn ($u) => $u->where('ten', 'like', "%{$q}%"))))
            ->orderBy('ten_phong')
            ->paginate(12)->withQueryString();

        return view('quanly.phong.danh-sach', [
            'phongs' => $phongs, 'q' => $q, 'idToa' => $idToa, 'idTang' => $idTang,
            'toas' => Toa::with(['tangs' => fn ($t) => $t->orderBy('ten_tang')])->orderBy('ten_toa')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return view('quanly.phong.form', [
            'phong' => new Phong(['id_tang' => $request->input('id_tang')]),
            'toas' => $this->toas(),
            'cuDans' => $this->cuDans(),
        ]);
    }

    public function store(Request $request)
    {
        $phong = DB::transaction(fn () => Phong::create($this->luuCuDanMoi($this->validated($request))));

        return redirect()->route('quanly.phong.index')->with('success', 'Đã thêm phòng ' . $phong->ten_phong . '.');
    }

    public function edit(Phong $phong)
    {
        return view('quanly.phong.form', ['phong' => $phong, 'toas' => $this->toas(), 'cuDans' => $this->cuDans()]);
    }

    public function update(Request $request, Phong $phong)
    {
        $data = $this->validated($request, $phong);
        DB::transaction(fn () => $phong->update($this->luuCuDanMoi($data)));

        return redirect()->route('quanly.phong.index')->with('success', 'Đã cập nhật phòng ' . $phong->ten_phong . '.');
    }

    private function toas()
    {
        return Toa::with(['tangs' => fn ($t) => $t->orderBy('ten_tang')])->orderBy('ten_toa')->get();
    }

    private function cuDans()
    {
        return User::with('phong')->where('quyen', User::CU_DAN)->where('trangthai', User::HOAT_DONG)->orderBy('ten')->get();
    }

    // Chọn "Thêm cư dân mới" thì tạo tài khoản cư dân rồi gán vào phòng
    private function luuCuDanMoi(array $data): array
    {
        if (($data['id_user'] ?? null) === 'moi') {
            $cuDan = User::create([
                'ten' => $data['moi_ten'],
                'ngaysinh' => $data['moi_ngaysinh'] ?? null,
                'email' => $data['moi_email'],
                'sdt' => $data['moi_sdt'],
                'password' => $data['moi_password'],
                'quyen' => User::CU_DAN,
                'trangthai' => User::HOAT_DONG,
            ]);
            $data['id_user'] = $cuDan->id_user;
        }

        // Phòng trống thì không có ai ở
        if (empty($data['id_user'])) {
            $data['songuoi'] = 0;
        }

        return collect($data)->only(['id_tang', 'ten_phong', 'id_user', 'songuoi'])->all();
    }

    private function validated(Request $request, ?Phong $phong = null): array
    {
        $moi = $request->input('id_user') === 'moi';

        return $request->validate([
            'id_tang' => 'required|exists:tang,id_tang',
            'ten_phong' => [
                'required', 'string', 'max:50',
                Rule::unique('phong', 'ten_phong')
                    ->where('id_tang', $request->input('id_tang'))
                    ->ignore($phong?->id_phong, 'id_phong'),
            ],
            'id_user' => $moi ? ['required'] : [
                'nullable',
                Rule::exists('users', 'id_user')->where('quyen', User::CU_DAN),
                Rule::unique('phong', 'id_user')->ignore($phong?->id_phong, 'id_phong'),
            ],
            'songuoi' => [$request->filled('id_user') ? 'required' : 'nullable', 'integer', 'min:1', 'max:10'],
            'moi_ten' => [$moi ? 'required' : 'nullable', 'string', 'max:100'],
            'moi_ngaysinh' => ['nullable', 'date', 'before:today'],
            'moi_email' => [$moi ? 'required' : 'nullable', 'email', 'max:100', 'unique:users,email'],
            'moi_sdt' => [$moi ? 'required' : 'nullable', 'regex:/^0\d{9}$/'],
            'moi_password' => [$moi ? 'required' : 'nullable', 'string', 'min:6'],
        ], [
            'moi_ten.required' => 'Vui lòng nhập họ tên cư dân mới.',
            'moi_email.required' => 'Vui lòng nhập email cư dân mới.',
            'moi_email.unique' => 'Email này đã có tài khoản.',
            'moi_sdt.required' => 'Vui lòng nhập số điện thoại cư dân mới.',
            'moi_sdt.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0.',
            'moi_password.required' => 'Vui lòng nhập mật khẩu cho cư dân mới.',
            'moi_password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'ten_phong.unique' => 'Tầng này đã có phòng cùng tên.',
            'id_user.unique' => 'Cư dân này đang ở phòng khác.',
            'songuoi.required' => 'Phòng có cư dân thì phải nhập số người ở.',
            'songuoi.min' => 'Số người ở phải từ 1 trở lên.',
            'songuoi.max' => 'Một phòng ở tối đa 10 người.',
        ]);
    }
}
