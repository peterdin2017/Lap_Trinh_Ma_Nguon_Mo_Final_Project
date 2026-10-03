<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\ForgotPassword\ForgotPasswordEmailRequest;
use App\Http\Requests\Store\Auth\ForgotPassword\ForgotPasswordOtpRequest;
use App\Http\Requests\Store\Auth\ForgotPassword\ResetPasswordRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Bước 1: Hiển thị form nhập email
    public function showEmailForm()
    {
        return view('store.auth.forgot-password.email');
    }

    // Bước 1: Gửi OTP về email
    public function sendOtp(ForgotPasswordEmailRequest $request)
    {
        $data = $request->validated();

        // Lưu email vào session
        session([
            'reset_email' => $data['email']
        ]);

        // Tìm tài khoản
        $account = $this->findAccount();

        // Không tìm thấy tài khoản
        if (!$account) {
            return back()
                ->withErrors([
                    'email' => 'Email không tồn tại hoặc tài khoản chưa được kích hoạt.'
                ])
                ->withInput();
        }

        // Tạo và gửi OTP
        $this->createAndSendOtp($account);

        // Chuyển sang trang nhập OTP
        return redirect()->route('forgot.password.otp.form');
    }

    // Bước 2: Hiển thị form nhập OTP
    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('forgot.password.form');
        }

        return view('store.auth.forgot-password.otp');
    }

    // Bước 2: Xác nhận OTP
    public function verifyOtp(ForgotPasswordOtpRequest $request)
    {
        $data = $request->validated();

        $account = $this->findAccount();

        if (!$account) {
            return redirect()->route('forgot.password.form');
        }

        $maxAttempts = 5;

        $otp = Otp::where('account_id', $account->account_id)
            ->where('purpose', 'reset_password')
            ->where('is_used', 0)
            ->orderBy('otp_id', 'desc')
            ->first();

        if (!$otp) {
            return back()->withErrors([
                'otp_code' => 'Không tìm thấy mã OTP.'
            ]);
        }

        if ($otp->expires_at < now()) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã hết hạn, vui lòng gửi lại.'
            ]);
        }

        if ($otp->attempts >= $maxAttempts) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã bị khóa do nhập sai quá số lần, vui lòng gửi lại.'
            ]);
        }

        // OTP không đúng
        if ((string) $otp->otp_code !== (string) $data['otp_code']) {

            $otp->attempts++;
            $otp->save();

            $remainingAttempts = $maxAttempts - $otp->attempts;

            if ($remainingAttempts <= 0) {
                return back()->withErrors([
                    'otp_code' => 'Bạn đã nhập sai giới hạn. Vui lòng gửi lại mã mới.'
                ]);
            }

            return back()->withErrors([
                'otp_code' => "Mã OTP không đúng. Bạn còn {$remainingAttempts} lần thử."
            ]);
        }

        // OTP đúng
        $otp->update([
            'is_used' => 1
        ]);

        session([
            'reset_otp_verified' => true
        ]);

        // Chuyển sang form nhập mật khẩu mới
        return redirect()->route('forgot.password.reset.form');
    }

    // Bước 2: Gửi lại OTP
    public function resendOtp()
    {
        $account = $this->findAccount();

        if (!$account) {
            return redirect()->route('forgot.password.form');
        }

        $this->createAndSendOtp($account);

        return back()->with(
            'success',
            'Đã gửi lại mã OTP, vui lòng kiểm tra email.'
        );
    }

    // Bước 3: Hiển thị form đặt mật khẩu mới
    public function showPasswordForm()
    {
        if (
            !session('reset_email') ||
            !session('reset_otp_verified')
        ) {
            return redirect()->route('forgot.password.form');
        }

        return view('store.auth.forgot-password.password');
    }

    // Bước 3: Lưu mật khẩu mới
    public function resetPassword(ResetPasswordRequest $request)
    {
        if (!session('reset_otp_verified')) {
            return redirect()->route('forgot.password.otp.form');
        }

        $data = $request->validated();

        $account = $this->findAccount();

        if (!$account) {
            return redirect()->route('forgot.password.form');
        }

        $account->update([
            'password_hash' => Hash::make($data['password']),
        ]);

        // Xóa session của quá trình reset password
        session()->forget([
            'reset_email',
            'reset_otp_verified',
        ]);

        return redirect()->route('forgot.success');
    }

    // Bước 4: Trang thông báo thành công
    public function showSuccess()
    {
        return view('store.auth.forgot-password.success');
    }

    // Tìm tài khoản từ email trong session
    private function findAccount()
    {
        $email = session('reset_email');

        if (!$email) {
            return null;
        }

        $account = Account::where('email', $email)->first();

        if (
            $account &&
            $account->is_verified == 1 &&
            $account->status == 'active'
        ) {
            return $account;
        }

        return null;
    }

    // Tạo và gửi OTP
    private function createAndSendOtp(Account $account)
    {
        $otpCode = random_int(100000, 999999);

        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'reset_password',
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(
            new OtpMail($otpCode,'reset_password')
        );
    }
}
