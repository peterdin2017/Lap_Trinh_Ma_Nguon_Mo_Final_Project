<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    // Hiển thị form nhập OTP
    public function showForm()
    {
        // Nếu chưa qua bước 1 (chưa có account_id trong session) thì đá về bước 1
        if (!session('register_account_id')) {
            return redirect()->route('register.form');
        }

        $account = Account::find(session('register_account_id'));

        return view('auth.register.otp', ['email' => $account->email]);
    }

    // Xử lý khi submit mã OTP
    public function verify(Request $request)
    {
        // Kiểm tra người dùng có nhập mã chưa
        $request->validate([
            'otp_code' => 'required',
        ], [
            'otp_code.required' => 'Vui lòng nhập mã OTP',
        ]);

        // Lấy account_id được lưu trong session register_account_id
        $accountId = session('register_account_id');

        // Số lần nhập tối đa
        $maxAttempts = 5;

        // Tìm mã OTP mới nhất, chưa dùng, đúng mục đích 'register'
        $otp = Otp::where('account_id', $accountId)
            ->where('purpose', 'register')
            ->where('is_used', 0)
            ->orderBy('otp_id', 'desc')   // lấy mã OTP được tạo gần nhất
            ->first();

        // Không tìm thấy OTP
        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Không tìm thấy mã OTP.']);
        }

        // Kiểm tra xem còn hạn sử dụng không
        if ($otp->expires_at < now()) {
            return back()->withErrors(['otp_code' => 'Mã OTP đã hết hạn, vui lòng gửi lại.']);
        }

        // Kiểm tra xem OTP này có bị quá số lần không
        if ($otp->attempts >= $maxAttempts) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã bị khóa do nhập sai quá số lần quy định, vui lòng gửi lại mã mới.'
            ]);
        }

        // Sai mã OTP
        if ($otp->otp_code !== $request->otp_code) {

            // Tăng số lần nhập sai và lưu lại
            $otp->attempts++;
            $otp->save();

            // Tính số lần thử còn lại
            $remainingAttempts = $maxAttempts - $otp->attempts;

            // Nếu lần sai này làm cạn kiệt số lần thử
            if ($remainingAttempts <= 0) {
                return back()->withErrors([
                    'otp_code' => 'Bạn đã nhập sai giới hạn. Mã OTP đã hết hiệu lực, vui lòng gửi lại mã mới.'
                ]);
            }

            // Nếu vẫn còn cơ hội thử lại
            return back()->withErrors([
                'otp_code' => "Mã OTP không đúng. Bạn còn {$remainingAttempts} lần thử."
            ]);
        }

        // OTP đúng
        // Đánh dấu mã này đã dùng
        $otp->update(['is_used' => 1]);

        // Đánh dấu đã xác thực xong
        session(['register_verified' => true]);

        // Sang bước tạo mật khẩu
        return redirect()->route('register.password.form');
    }

    // Gửi lại mã OTP mới (nút gửi lại)
    public function resend()
    {
        $accountId = session('register_account_id');

        // nếu account_id không có trong session -> người dùng chưa bắt đầu đăng kí
        if (!$accountId) {
            return redirect()->route('register.form');
        }

        // Tìm tài khoản trong database
        $account = Account::find($accountId);

        // Sinh mã OTP
        $otpCode = rand(100000, 999999);

        // Lưu mã mới vào database
        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(5),
        ]);

        // Gửi mã otp tới gmail
        Mail::to($account->email)->send(new OtpMail($otpCode));

        // Quay lại trang OTP và thông báo thành công
        return back()->with('success', 'Đã gửi lại mã OTP, vui lòng kiểm tra email');
    }
}
