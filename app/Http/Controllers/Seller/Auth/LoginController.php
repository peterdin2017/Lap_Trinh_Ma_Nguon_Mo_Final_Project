<?php

namespace App\Http\Controllers\Seller\Auth;

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
        if (Auth::check() && Auth::user()->role === 'seller') {
            return $this->redirectBySellerStatus(Auth::user());
        }

        return view('seller.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Lọc theo role để email dùng chung với Store hoặc Admin
        // không thể đăng nhập nhầm vào tài khoản Seller.
        $account = Account::query()
            ->where('email', $credentials['email'])
            ->where('role', 'seller')
            ->where('status', 'active')
            ->first();

        if (! $account || ! Hash::check($credentials['password'], $account->password_hash)) {
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])
                ->onlyInput('email');
        }

        if (! $account->seller) {
            return back()
                ->withErrors(['email' => 'Tài khoản chưa có hồ sơ người bán.'])
                ->onlyInput('email');
        }

        Auth::login($account);
        $request->session()->regenerate();

        return $this->redirectBySellerStatus($account);
    }

    private function redirectBySellerStatus(Account $account): RedirectResponse
    {
        return match ($account->seller?->status) {
            'pending' => redirect()->route('seller.application.pending'),
            'rejected' => redirect()->route('seller.application.rejected'),
            'active' => redirect()->route('seller.home'),
            'suspended' => redirect()->route('seller.application.suspended'),
            default => redirect()
                ->route('seller.login.form')
                ->withErrors(['email' => 'Không xác định được trạng thái hồ sơ người bán.']),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('seller.login.form')
            ->with('success', 'Bạn đã đăng xuất.');
    }
}
