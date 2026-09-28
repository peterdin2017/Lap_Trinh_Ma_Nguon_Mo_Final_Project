<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Hiển thị form nhập email
    public function showEmailForm()
    {
        return view('auth.forgot.email');
    }

    // Gửi OTP về email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
        ]);

        // Lưu email vào session
        session(['reset_email' => $request->email]);

        // Tìm tài khoản
        $account = $this->findAccount();

        // Không tìm thấy tài khoản
        if (!$account) {
            return back()->withErrors([
                'email' => 'Email không tồn tại hoặc tài khoản chưa được kích hoạt.'
            ])->withInput();
        }

        // Tạo và gửi OTP
        $this->createAndSendOtp($account);

        // Chuyển sang trang nhập OTP
        return redirect()->route('forgot.otp.form');
    }
    // Bước 2: Hiển thị form nhập OTP
    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('forgot.form');
        }
        return view('auth.forgot.otp');
    }

    // Bước 2: Xác nhận OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required',
        ], [
            'otp_code.required' => 'Vui lòng nhập mã OTP',
        ]);

        $account = $this->findAccount();
        if (!$account) {
            return redirect()->route('forgot.form');
        }

        $maxAttempts = 5;

        $otp = Otp::where('account_id', $account->account_id)
            ->where('purpose', 'reset_password')
            ->where('is_used', 0)
            ->orderBy('otp_id', 'desc')
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Không tìm thấy mã OTP.']);
        }

        if ($otp->expires_at < now()) {
            return back()->withErrors(['otp_code' => 'Mã OTP đã hết hạn, vui lòng gửi lại.']);
        }

        if ($otp->attempts >= $maxAttempts) {
            return back()->withErrors(['otp_code' => 'Mã OTP đã bị khóa do nhập sai quá số lần, vui lòng gửi lại.']);
        }

        if ((string) $otp->otp_code !== (string) $request->otp_code) {
            $otp->attempts++;
            $otp->save();

            $remainingAttempts = $maxAttempts - $otp->attempts;

            if ($remainingAttempts <= 0) {
                return back()->withErrors(['otp_code' => 'Bạn đã nhập sai giới hạn. Vui lòng gửi lại mã mới.']);
            }

            return back()->withErrors(['otp_code' => "Mã OTP không đúng. Bạn còn {$remainingAttempts} lần thử."]);
        }

        // OTP đúng
        $otp->update(['is_used' => 1]);
        session(['reset_otp_verified' => true]);

        return redirect()->route('forgot.password.form');
    }

    // Bước 2: Gửi lại OTP
    public function resendOtp()
    {
        $account = $this->findAccount();
        if (!$account) {
            return redirect()->route('forgot.form');
        }

        $this->createAndSendOtp($account);

        return back()->with('success', 'Đã gửi lại mã OTP, vui lòng kiểm tra email');
    }

    // Bước 3: Hiển thị form đặt mật khẩu mới
    public function showPasswordForm()
    {
        if (
            !session('reset_email') ||
            !session('reset_otp_verified')
        ) {
            return redirect()->route('forgot.otp.form');
        }

        return view('auth.forgot.password');
    }

    // Bước 3: Lưu mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ], [
            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
            'password.regex'     => 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt',
        ]);

        if (!session('reset_otp_verified')) {
            return redirect()->route('forgot.otp.form');
        }

        $account = $this->findAccount();
        if (!$account) {
            return redirect()->route('forgot.form');
        }

        $account->update([
            'password_hash' => Hash::make($request->password),
        ]);

        session()->forget(['reset_email', 'reset_otp_verified']);

        return redirect()->route('forgot.success');
    }

    // Bước 4: Trang thông báo thành công
    public function showSuccess()
    {
        return view('auth.forgot.success');
    }

    // ===== PRIVATE HELPERS =====

    private function findAccount()
    {
        $email = session('reset_email');
        if (!$email) return null;

        $account = Account::where('email', $email)->first();

        if ($account && $account->is_verified == 1 && $account->status == 'active') {
            return $account;
        }

        return null;
    }

    private function createAndSendOtp(Account $account)
    {
        $otpCode = random_int(100000, 999999);

        Otp::create([
            'account_id' => $account->account_id,
            'otp_code'   => $otpCode,
            'purpose'    => 'reset_password',
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new OtpMail($otpCode));
    }
}
