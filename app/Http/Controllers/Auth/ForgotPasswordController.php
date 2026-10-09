<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Form nhập email để bắt đầu quên mật khẩu
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Gửi OTP đến email
    public function sendOtp(ForgotPasswordRequest $request)
    {
        $account = Account::where(
            'email',
            $request->validated('email')
        )->firstOrFail();

        $otpCode = (string) random_int(100000, 999999);

        Otp::where('account_id', $account->account_id)
            ->where('purpose', 'reset_password')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        Otp::create([
            'account_id' => $account->account_id,
            'account_type' => $account->role,
            'email' => $account->email,
            'otp_hash' => Hash::make($otpCode),
            'attempts' => 0,
            'purpose' => 'reset_password',
            'expires_at' => now()->addMinutes(5),
        ]);

        session([
            'forgot_password_account_id' => $account->account_id,
            'forgot_password_verified' => false,
        ]);

        Mail::to($account->email)->send(
            new OtpMail($otpCode, 'reset_password')
        );

        return redirect()->route('forgot-password.otp.form')
            ->with('success', 'Mã OTP đã được gửi đến email của bạn.');
    }

    // Form nhập mật khẩu mới sau khi OTP đã được xác minh
    public function showPasswordForm()
    {
        if (!session('forgot_password_verified')) {
            return redirect()->route('forgot-password.form');
        }

        return view('auth.reset-password');
    }

    // Lưu mật khẩu mới rồi chuyển về trang đăng nhập
    public function resetPassword(ResetPasswordRequest $request)
    {
        if (!session('forgot_password_verified')) {
            return redirect()->route('forgot-password.form');
        }

        $account = Account::find(session('forgot_password_account_id'));

        if (!$account) {
            session()->forget([
                'forgot_password_account_id',
                'forgot_password_verified',
            ]);

            return redirect()->route('forgot-password.form');
        }

        // Model Account tự băm giá trị này qua cast password_hash.
        $account->password_hash = $request->validated('password');
        $account->save();

        session()->forget([
            'forgot_password_account_id',
            'forgot_password_verified',
        ]);

        $loginRoute = match ($account->role) {
            'seller' => 'seller.login.form',
            'admin' => 'admin.login.form',
            default => 'login.form',
        };

        return redirect()->route($loginRoute)
            ->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
