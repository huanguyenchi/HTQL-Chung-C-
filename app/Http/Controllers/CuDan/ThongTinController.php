<?php

namespace App\Http\Controllers\CuDan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThongTinController extends Controller
{
    public function edit()
    {
        return view('cudan.thong-tin', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        // Chỉ nhận các trường được phép sửa; email, quyền, trạng thái không đổi được ở đây
        $data = $request->validate([
            'ten' => 'required|string|max:100',
            'ngaysinh' => 'nullable|date|before:today',
            'sdt' => ['required', 'regex:/^0\d{9}$/'],
            'stk' => 'nullable|string|max:30',
        ], [
            'sdt.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0.',
        ]);

        $request->user()->update($data);

        return back()->with('success', 'Đã cập nhật thông tin cá nhân.');
    }
}
