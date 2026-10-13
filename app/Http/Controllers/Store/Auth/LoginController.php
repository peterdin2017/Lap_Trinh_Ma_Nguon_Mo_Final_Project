<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\LoginRequest;
use App\Models\Account;
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
            $account->status !== 'active' ||
            ! $account->is_verified ||
            ! Hash::check($data['password'], $account->password_hash)
        ) {
            return back()
                ->withErrors([
                    'email' => 'Thông tin đăng nhập không hợp lệ.',
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

    public function logout(): RedirectResponse
    {
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('store.home');
    }
}
