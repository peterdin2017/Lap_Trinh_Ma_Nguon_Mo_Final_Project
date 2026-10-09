<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpController extends Controller
{
    // Hiển thị form nhập OTP
    public function showForm()
    {
        $isForgotPassword = request()->routeIs('forgot-password.*');

        $accountId = $isForgotPassword
            ? session('forgot_password_account_id')
            : session('register_account_id');

        if (!$accountId) {
            return redirect()->route(
                $isForgotPassword ? 'forgot-password.form' : 'register.form'
            );
        }

        $account = Account::find($accountId);

        if (!$account) {
            session()->forget(
                $isForgotPassword
                    ? ['forgot_password_account_id', 'forgot_password_verified']
                    : ['register_account_id', 'register_verified']
            );

            return redirect()->route(
                $isForgotPassword ? 'forgot-password.form' : 'register.form'
            );
        }

        return view('auth.otp', [
            'email' => $account->email,
        ]);
    }

    public function verify(VerifyOtpRequest $request)
    {
        $isForgotPassword = $request->routeIs('forgot-password.*');

        $accountId = $isForgotPassword
            ? session('forgot_password_account_id')
            : session('register_account_id');

        if (!$accountId) {
            return redirect()->route(
                $isForgotPassword ? 'forgot-password.form' : 'register.form'
            );
        }

        $otp = Otp::where('account_id', $accountId)
            ->where('purpose', $isForgotPassword ? 'reset_password' : 'register')
            ->whereNull('used_at')
            ->orderByDesc('otp_id')
            ->first();

        if (!$otp) {
            return back()->withErrors([
                'otp_code' => 'Không tìm thấy mã OTP. Vui lòng gửi lại mã.',
            ]);
        }

        if (now()->greaterThan($otp->expires_at)) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã hết hạn. Vui lòng gửi lại mã.',
            ]);
        }

        if ($otp->attempts >= 5) {
            return back()->withErrors([
                'otp_code' => 'Bạn đã nhập sai quá số lần cho phép. Vui lòng gửi lại mã.',
            ]);
        }

        if (!Hash::check($request->validated('otp_code'), $otp->otp_hash)) {
            $otp->increment('attempts');

            return back()->withErrors([
                'otp_code' => 'Mã OTP không đúng.',
            ]);
        }

        $otp->update([
            'used_at' => now(),
        ]);

        if ($isForgotPassword) {
            session(['forgot_password_verified' => true]);

            return redirect()->route('forgot-password.password.form');
        }

        session(['register_verified' => true]);

        return redirect()->route('register.password.form');
    }
    // Gửi lại OTP
    public function resend(): RedirectResponse
    {
        $accountId = session('register_account_id');

        if (! $accountId) {
            return redirect()->route('register.form');
        }

        $account = Account::find($accountId);

        if (! $account) {
            session()->forget([
                'register_account_id',
                'register_verified',
            ]);

            return redirect()->route('register.form');
        }

        $otpCode = (string) random_int(100000, 999999);

        // Đánh dấu OTP cũ đã dùng
        Otp::where('account_id', $account->account_id)
            ->where('purpose', 'register')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

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

        return back()->with(
            'success',
            'Đã gửi lại mã OTP. Vui lòng kiểm tra email.'
        );
    }
}
