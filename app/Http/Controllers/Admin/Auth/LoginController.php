<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập Admin.
    public function showLoginForm(): View|RedirectResponse
{
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.home');
    }

    return view('admin.auth.login');
}

    // Kiểm tra thông tin và đăng nhập tài khoản Admin.
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập email Admin.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $account = Account::where('email', $credentials['email'])
            ->where('role', 'admin')
            ->where('status', 'active')
            ->first();

        if (
            ! $account ||
            ! Hash::check($credentials['password'], $account->password_hash)
        ) {
            return back()
                ->withErrors(['login' => 'Email hoặc mật khẩu không chính xác.'])
                ->onlyInput('email');
        }

        Auth::login($account);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.home'));
    }

    // Đăng xuất Admin và làm mới session.
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login.form')
            ->with('success', 'Bạn đã đăng xuất.');
    }
}
