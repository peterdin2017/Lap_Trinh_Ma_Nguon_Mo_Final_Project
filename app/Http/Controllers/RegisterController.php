<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // ====== BƯỚC 1: Hiển thị form nhập email ======
    public function showEmailForm()
    {
        return view('auth.register.email');
    }

    // ====== BƯỚC 1: Xử lý khi submit email ======
    public function submitEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:accounts,email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được đăng ký rồi',
        ]);

        // Tạo tài khoản với mật khẩu TẠM THỜI (ngẫu nhiên, không ai biết)
        // Mật khẩu này sẽ được thay bằng mật khẩu thật ở Bước 3
        $account = Account::create([
            'email' => $request->email,
            'password_hash' => password_hash(Str::random(20), PASSWORD_DEFAULT),
        ]);

        // Lưu account_id vào session để các bước sau (2, 3) biết đang xử lý ai
        session(['register_account_id' => $account->account_id]);

        // Gửi OTP
        $this->generateAndSendOtp($account);

        return redirect()->route('otp.form');
    }

    // Hàm dùng chung: sinh mã OTP, lưu DB, gửi email
    private function generateAndSendOtp(Account $account)
    {
        $otpCode = rand(100000, 999999);

        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new OtpMail($otpCode));
    }

    // ====== BƯỚC 3: Hiển thị form tạo mật khẩu ======
    public function showPasswordForm()
    {
        // Chặn truy cập thẳng vào bước 3 nếu chưa xác thực OTP ở bước 2
        if (!session('register_verified')) {
            return redirect()->route('register.form');
        }

        return view('auth.register.password');
    }

    // ====== BƯỚC 3: Xử lý khi submit mật khẩu + họ tên ======
    public function submitPassword(Request $request)
    {
        if (!session('register_verified')) {
            return redirect()->route('register.form');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'password' => [
                'required',
                'confirmed',
                Password::min(6)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.mixed' => 'Mật khẩu phải có cả chữ hoa và chữ thường',
            'password.numbers' => 'Mật khẩu phải có ít nhất 1 chữ số',
            'password.symbols' => 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt',
        ]);

        $account = Account::find(session('register_account_id'));

        // Cập nhật mật khẩu THẬT (ghi đè mật khẩu tạm ở Bước 1) và đánh dấu đã xác thực
        $account->update([
            'password_hash' => password_hash($request->password, PASSWORD_DEFAULT),
            'is_verified' => 1,
        ]);

        // Tạo thông tin cá nhân
        User::create([
            'account_id' => $account->account_id,
            'full_name' => $request->full_name,
        ]);

        // Xóa hết session tạm, không cần dùng nữa
        session()->forget(['register_account_id', 'register_verified']);

        return redirect()->route('register.complete');
    }

    // ====== BƯỚC 4: Trang hoàn thành ======
    public function complete()
    {
        return view('auth.register.complete');
    }
}
