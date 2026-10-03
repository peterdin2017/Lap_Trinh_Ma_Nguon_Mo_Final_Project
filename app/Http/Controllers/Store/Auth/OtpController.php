<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\Register\VerifyOtpRequest;
use App\Models\Account;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    // Hiển thị form nhập OTP
    public function showForm()
    {
        // Nếu chưa có account_id trong session thì quay về bước đăng ký
        if (!session('register_account_id')) {
            return redirect()->route('register.form');
        }

        // Lấy tài khoản đang đăng ký
        $account = Account::find(
            session('register_account_id')
        );

        // Không tìm thấy tài khoản
        if (!$account) {
            session()->forget('register_account_id');

            return redirect()->route('register.form');
        }

        return view('store.auth.register.otp', [
            'email' => $account->email
        ]);
    }

    // Xử lý khi submit mã OTP
    public function verify(VerifyOtpRequest $request)
    {
        // Dữ liệu đã được Request kiểm tra
        $data=$request->validated();

        // Lấy account_id từ session
        $accountId = session('register_account_id');

        // Số lần nhập tối đa
        $maxAttempts = 5;

        // Tìm OTP mới nhất chưa sử dụng
        $otp = Otp::where('account_id', $accountId)
            ->where('purpose', 'register')
            ->where('is_used', 0)
            ->orderBy('otp_id', 'desc')
            ->first();

        // Không tìm thấy OTP
        if (!$otp) {
            return back()->withErrors([
                'otp_code' => 'Không tìm thấy mã OTP.'
            ])->withInput();
        }

        // Kiểm tra xem còn hạn sử dụng không
        if ($otp->expires_at < now()) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã hết hạn, vui lòng gửi lại.'
            ])->withInput();
        }

        // Kiểm tra xem OTP này có bị quá số lần không
        if ($otp->attempts >= $maxAttempts) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã bị khóa do nhập sai quá số lần quy định, vui lòng gửi lại mã mới.'
            ])->withInput();
        }

        // Sai mã OTP
        if ((string) $otp->otp_code !== (string) $data['otp_code']) {

            // Tăng số lần nhập sai
            $otp->attempts++;
            $otp->save();

            // Tính số lần còn lại
            $remainingAttempts = $maxAttempts - $otp->attempts;

            // Hết lượt
            if ($remainingAttempts <= 0) {
                return back()->withErrors([
                    'otp_code' => 'Bạn đã nhập sai giới hạn. Mã OTP đã hết hiệu lực, vui lòng gửi lại mã mới.'
                ])->withInput();
            }

            // Vẫn còn lượt
            return back()->withErrors([
                'otp_code' => "Mã OTP không đúng. Bạn còn {$remainingAttempts} lần thử."
            ])->withInput();
        }

        // OTP đúng
        // Đánh dấu mã này đã dùng
        $otp->update([
            'is_used' => 1
        ]);

        // Đánh dấu đã xác thực xong
        session([
            'register_verified' => true
        ]);

        // Sang bước tạo mật khẩu
        return redirect()->route('register.password.form');
    }

    // Gửi lại mã OTP mới (nút gửi lại)
    public function resend()
    {
        // Lấy account_id từ session
        $accountId = session('register_account_id');

        // Chưa bắt đầu đăng ký
        if (!$accountId) {
            return redirect()->route('register.form');
        }

        // Tìm tài khoản
        $account = Account::find($accountId);

        // Không tìm thấy tài khoản
        if (!$account) {
            session()->forget('register_account_id');

            return redirect()->route('register.form');
        }

        // Sinh OTP mới
        $otpCode = rand(100000, 999999);

        // Lưu OTP
        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'register',
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Gửi email
        Mail::to($account->email)
            ->send(new OtpMail($otpCode));

        return back()->with(
            'success',
            'Đã gửi lại mã OTP, vui lòng kiểm tra email'
        );
    }
}
