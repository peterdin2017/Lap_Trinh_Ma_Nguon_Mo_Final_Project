<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'otp_code' => 'required',
        ], [
            'otp_code.required' => 'Vui lòng nhập mã OTP',
        ]);

        $accountId = session('register_account_id');

        // Tìm mã OTP mới nhất, chưa dùng, đúng mục đích 'register'
        $otp = Otp::where('account_id', $accountId)
            ->where('purpose', 'register')
            ->where('is_used', 0)
            ->orderBy('otp_id', 'desc')   // lấy mã OTP được tạo gần nhất
            ->first();

        if (!$otp || $otp->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Mã OTP không đúng, vui lòng thử lại']);
        }

        if ($otp->expires_at < now()) {
            return back()->withErrors(['otp_code' => 'Mã OTP đã hết hạn, vui lòng gửi lại']);
        }

        // Đánh dấu mã này đã dùng, không dùng lại được nữa
        $otp->update(['is_used' => 1]);

        // Đánh dấu trong session là đã xác thực xong, được phép qua Bước 3
        session(['register_verified' => true]);

        return redirect()->route('register.password.form');
    }

    // Gửi lại mã OTP mới (nút "Gửi lại mã" trên giao diện)
    public function resend()
    {
        $accountId = session('register_account_id');

        if (!$accountId) {
            return redirect()->route('register.form');
        }

        $account = Account::find($accountId);

        $otpCode = rand(100000, 999999);

        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new OtpMail($otpCode));

        return back()->with('success', 'Đã gửi lại mã OTP, vui lòng kiểm tra email');
    }
}
