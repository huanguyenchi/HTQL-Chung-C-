<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class XacThucController extends Controller
{
    public function hienDangNhap()
    {
        return view('xacthuc.dang-nhap');
    }

    public function dangNhap(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($user && Hash::check($data['password'], $user->password) && (int) $user->trangthai !== User::HOAT_DONG) {
            $thongBao = $user->choDuyet()
                ? 'Tài khoản đang chờ ban quản lý duyệt. Bạn sẽ đăng nhập được sau khi được duyệt.'
                : 'Tài khoản đã bị khóa. Vui lòng liên hệ ban quản lý tòa nhà.';

            return back()->withErrors(['email' => $thongBao])->onlyInput('email');
        }

        if (! Auth::attempt($data + ['trangthai' => User::HOAT_DONG])) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return Auth::user()->isQuanLy()
            ? redirect()->route('quanly.tongquan')
            : redirect()->route('cudan.trangchu');
    }

    public function hienDangKy()
    {
        return view('xacthuc.dang-ky');
    }

    public function dangKy(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:100',
            'ngaysinh' => 'nullable|date|before:today',
            'email' => 'required|email|max:100|unique:users,email',
            'sdt' => ['required', 'regex:/^0\d{9}$/'],
            'stk' => 'nullable|string|max:30',
            'ghichu_dangky' => 'nullable|string|max:500',
            'password' => 'required|string|min:6|confirmed',
            'dongy' => 'accepted',
        ], [
            'sdt.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0.',
            'dongy.accepted' => 'Bạn cần xác nhận thông tin là chính xác.',
        ]);

        unset($data['dongy']);
        User::create($data + ['quyen' => User::CU_DAN, 'trangthai' => User::CHO_DUYET]);

        return redirect()->route('dangnhap')->with(
            'success',
            'Đăng ký thành công! Thông tin đã được gửi đến ban quản lý. Bạn sẽ đăng nhập được sau khi tài khoản được duyệt.'
        );
    }

    public function dangXuat(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dangnhap')->with('success', 'Bạn đã đăng xuất.');
    }

    public function hienDoiMatKhau()
    {
        return view('xacthuc.doi-mat-khau');
    }

    public function doiMatKhau(Request $request)
    {
        $request->validate([
            'matkhau_cu' => 'required|current_password',
            'password' => 'required|min:6|confirmed|different:matkhau_cu',
        ]);

        $request->user()->update(['password' => $request->password]);

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }
}
