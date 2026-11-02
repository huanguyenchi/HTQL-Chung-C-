<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaiKhoanController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $quyen = $request->input('quyen');
        $trangThai = $request->input('trangthai');

        $users = User::query()
            ->when($quyen, fn ($query) => $query->where('quyen', $quyen))
            ->when($trangThai !== null && $trangThai !== '', fn ($query) => $query->where('trangthai', (int) $trangThai))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('ten', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('sdt', 'like', "%{$q}%")))
            ->orderByRaw('trangthai = ? desc', [User::CHO_DUYET])
            ->orderBy('quyen')->orderBy('ten')
            ->paginate(10)->withQueryString();

        return view('quanly.taikhoan.danh-sach', [
            'users' => $users, 'q' => $q, 'quyen' => $quyen, 'trangThai' => $trangThai,
            'soChoDuyet' => User::where('trangthai', User::CHO_DUYET)->count(),
        ]);
    }

    public function create()
    {
        return view('quanly.taikhoan.form', ['user' => new User(['quyen' => User::CU_DAN])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['trangthai'] = 1;

        $user = User::create($data);

        return redirect()->route('quanly.taikhoan.index')->with('success', 'Đã thêm tài khoản ' . $user->email . '.');
    }

    public function edit(User $user)
    {
        return view('quanly.taikhoan.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Không cho quản lý tự hạ quyền của chính mình
        if ((int) $user->id_user === (int) auth()->id()) {
            $data['quyen'] = User::QUAN_LY;
        }

        $user->update($data);

        return redirect()->route('quanly.taikhoan.index')->with('success', 'Đã cập nhật tài khoản ' . $user->email . '.');
    }

    public function khoa(User $user)
    {
        if ($user->choDuyet()) {
            return back()->with('error', 'Tài khoản đang chờ duyệt, hãy dùng nút Duyệt hoặc Từ chối.');
        }

        if ((int) $user->id_user === (int) auth()->id()) {
            return back()->with('error', 'Bạn không thể khóa tài khoản của chính mình.');
        }

        $user->update(['trangthai' => (int) $user->trangthai === 1 ? 0 : 1]);

        return back()->with('success', ((int) $user->trangthai === 1 ? 'Đã mở khóa ' : 'Đã khóa ') . $user->email . '.');
    }

    public function duyet(Request $request, User $user)
    {
        if (! $user->choDuyet()) {
            return back()->with('error', 'Tài khoản này không ở trạng thái chờ duyệt.');
        }

        if ($request->input('ketqua') === 'tuchoi') {
            $user->update(['trangthai' => User::BI_KHOA]);

            return back()->with('success', 'Đã từ chối tài khoản ' . $user->email . ' (tài khoản bị khóa).');
        }

        $user->update(['trangthai' => User::HOAT_DONG]);

        return back()->with('success', 'Đã duyệt tài khoản ' . $user->email . '. Cư dân có thể đăng nhập ngay.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'ten' => 'required|string|max:100',
            'ngaysinh' => 'nullable|date|before:today',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user?->id_user, 'id_user')],
            'sdt' => ['required', 'regex:/^0\d{9}$/'],
            'stk' => 'nullable|string|max:30',
            'quyen' => 'required|in:1,2',
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
        ], [
            'sdt.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0.',
        ]);
    }
}
