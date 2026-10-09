<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\RegisterEmailRequest;
use App\Http\Requests\Store\Auth\RegisterPasswordRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showEmailForm(): View
    {
        return view('store.auth.register');
    }

    public function submitEmail(RegisterEmailRequest $request): RedirectResponse
    {
        $email = $request->validated()['email'];

        session()->forget([
            'register_account_id',
            'register_verified',
        ]);

        $account = Account::where('email', $email)->first();

        if ($account && ($account->is_verified || $account->role !== 'user')) {
            return back()
                ->withErrors(['email' => 'Email này đã được đăng ký rồi.'])
                ->withInput();
        }

        if (! $account) {
            $account = Account::create([
                'email' => $email,
                'password_hash' => Hash::make(Str::random(32)),
                'role' => 'user',
                'status' => 'active',
                'is_verified' => false,
            ]);
        }

        session(['register_account_id' => $account->account_id]);

        $this->generateAndSendOtp($account);

        return redirect()->route('otp.form');
    }

    private function generateAndSendOtp(Account $account): void
    {
        $otpCode = (string) random_int(100000, 999999);

        Otp::create([
            'account_id' => $account->account_id,
            'account_type' => 'user',
            'email' => $account->email,
            'otp_hash' => Hash::make($otpCode),
            'purpose' => 'register',
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)
            ->send(new OtpMail($otpCode, 'register'));
    }

    public function showPasswordForm(): View|RedirectResponse
    {
        if (
            ! session('register_verified') ||
            ! session('register_account_id')
        ) {
            return redirect()->route('register.form');
        }

        return view('auth.password');
    }

    public function submitPassword(
        RegisterPasswordRequest $request
    ): RedirectResponse {
        if (! session('register_verified')) {
            return redirect()->route('register.form');
        }

        $account = Account::find(session('register_account_id'));

        if (! $account) {
            session()->forget([
                'register_account_id',
                'register_verified',
            ]);

            return redirect()->route('register.form');
        }

        $data = $request->validated();

        DB::transaction(function () use ($account, $data): void {
            $account->update([
                'password_hash' => Hash::make($data['password']),
                'is_verified' => true,
            ]);

            User::firstOrCreate(
                ['account_id' => $account->account_id],
                ['full_name' => $data['full_name']]
            );
        });

        session()->forget([
            'register_account_id',
            'register_verified',
        ]);

        return redirect()
            ->route('login.form')
            ->with('success', 'Đăng ký thành công. Vui lòng đăng nhập.');
    }

    // Giữ lại nếu route register.complete hiện vẫn trỏ đến method này.
    public function complete(): View
    {
        return view('auth.complete');
    }
}
