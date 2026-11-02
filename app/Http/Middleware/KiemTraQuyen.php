<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KiemTraQuyen
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('dangnhap');
        }

        // Tài khoản bị khóa trong lúc đang đăng nhập -> đăng xuất ngay
        if ((int) $user->trangthai !== 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('dangnhap')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
        }

        if (! in_array((string) $user->quyen, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
