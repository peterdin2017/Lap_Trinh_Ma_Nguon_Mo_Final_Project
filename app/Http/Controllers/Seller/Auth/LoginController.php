<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Auth\SellerLoginRequest;
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

    public function login(SellerLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        // Lọc theo role để email dùng chung với Store hoặc Admin
        // không thể đăng nhập nhầm vào tài khoản Seller.
        $account = Account::query()
            ->where('email', $credentials['email'])
            ->where('role', 'seller')
            ->first();

        if (! $account || ! Hash::check($credentials['password'], $account->password_hash)) {
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])
                ->onlyInput('email');
        }

        if ($account->status === 'locked') {
            return back()
                ->withErrors(['email' => 'Tài khoản người bán đã bị khóa. Vui lòng liên hệ bộ phận hỗ trợ.'])
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
        // Seller dùng guard web; giữ nguyên session độc lập của Store và Admin.
        Auth::guard('web')->logout();

        return redirect()
            ->route('seller.login.form')
            ->with('success', 'Bạn đã đăng xuất.');
    }
}
