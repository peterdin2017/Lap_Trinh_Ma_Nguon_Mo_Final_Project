<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\LoginRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showForm(): View
    {
        return view('store.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $account = Account::where('email', $data['email'])->first();

        if (
            ! $account ||
            ! in_array($account->role, ['user', 'seller'], true) ||
            ! $account->is_verified ||
            ! Hash::check($data['password'], $account->password_hash)
        ) {
            return back()
                ->withErrors([
                    'email' => 'Thông tin đăng nhập không hợp lệ.',
                ])
                ->onlyInput('email');
        }

        if ($account->status === 'locked') {
            return back()
                ->withErrors([
                    'email' => $account->role === 'seller'
                        ? 'Tài khoản người bán đã bị khóa. Vui lòng liên hệ bộ phận hỗ trợ.'
                        : 'Tài khoản đã bị khóa. Vui lòng liên hệ bộ phận hỗ trợ.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        session([
            'account_id' => $account->account_id,
            'account_email' => $account->email,
            'account_role' => $account->role,
            'user_name' => $account->email,
        ]);

        return redirect()->route('store.home');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'account_id',
            'account_email',
            'account_role',
            'user_name',
            'avatar',
        ]);

        return redirect()->route('store.home');
    }
}
