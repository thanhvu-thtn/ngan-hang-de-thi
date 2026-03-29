<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        // Nếu đã đăng nhập thì chuyển hướng vào Dashboard (Trang chào mừng)
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Kiểm tra thông tin đăng nhập (có tùy chọn "Ghi nhớ đăng nhập")
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Sửa dòng này để sau khi đăng nhập xong là vào thẳng Dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Đăng nhập thất bại -> Quay lại form và báo lỗi
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
