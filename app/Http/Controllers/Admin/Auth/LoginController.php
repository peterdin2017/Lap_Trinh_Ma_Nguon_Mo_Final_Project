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
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        return view('admin.auth.login');
    }

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

        $account = Account::query()
            ->where('email', $credentials['email'])
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

        Auth::guard('admin')->login($account);
        $request->session()->regenerate();

        return redirect()->route('admin.home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        // Chỉ đăng xuất guard admin, không xóa đăng nhập Store/Seller.
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login.form')
            ->with('success', 'Bạn đã đăng xuất.');
    }
}
