<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\LoginRequest;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $account = Account::where('email', $data['email'])
            ->where('role', 'user')
            ->where('status', 'active')
            ->where('is_verified', true)
            ->first();

        if (! $account || ! Hash::check($data['password'], $account->password_hash)) {
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])
                ->withInput();
        }

        $request->session()->regenerate();

        $profile = User::where('account_id', $account->account_id)->first();

        session([
            'account_id' => $account->account_id,
            'account_email' => $account->email,
            'user_name' => $profile?->full_name ?? $account->email,
            'avatar' => $profile?->avatar,
        ]);

        return redirect()->intended(route('store.home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.home');
    }
}
