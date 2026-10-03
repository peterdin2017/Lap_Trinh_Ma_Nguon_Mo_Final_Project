<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

// 2 class kiểm tra yêu cầu
use App\Http\Requests\Store\Auth\Register\RegisterEmailRequest;
use App\Http\Requests\Store\Auth\Register\RegisterPasswordRequest;

class RegisterController extends Controller
{
    // Hiển thị form nhập emai, laravel sẽ tìm resources/views/auth/register/email.blade.php
    public function showEmailForm()
    {
        return view('store.auth.register.register');
    }

    // Nhận email
    public function submitEmail(RegisterEmailRequest $request)
    {
        // Lấy dữ liệu đã được Request kiểm tra
        $data=$request->validated();

        // Bắt đầu một quá trình đăng ký mới
        session()->forget('register_verified');

        // Tìm tài khoản theo gmail
        $account = Account::where('email', $data['email'])->first();

        // Nếu email đã tồn tại
        if ($account) {

            // Email đã được xác thực rồi
            if ($account->is_verified == 1) {
                return back()->withErrors([
                    'email' => 'Email này đã được đăng ký rồi.'
                ])->withInput();
            }

            // Email đã tồn tại nhưng chưa được xác thực
            // dùng lại account từ session cũ
            session([
                'register_account_id' => $account->account_id
            ]);

            // Gửi OTP mới
            $this->generateAndSendOtp($account);

            // Chuyển sang trang nhập  OTP
            return redirect()->route('otp.form');
        }

        // Nếu email chưa tồn tại
        // Tạo tài khoản mới
        $account = Account::create([
            'email' => $request->email,
            'password_hash' => password_hash(Str::random(20), PASSWORD_DEFAULT),
            'is_verified' => 0,
        ]);

        // Lưu account_id vào session để các bước sau biết đang xử lý ai
        session([
            'register_account_id' => $account->account_id
        ]);

        // Gửi OTP
        $this->generateAndSendOtp($account);

        return redirect()->route('otp.form');
    }

    // Hàm sinh mã OTP, lưu DB, gửi email
    private function generateAndSendOtp(Account $account)
    {
        $otpCode = rand(100000, 999999);

        // Lưu vào db
        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'register',
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new OtpMail($otpCode));
    }

    // Hiển thị form tạo mật khẩu
    public function showPasswordForm()
    {
        if (
            !session('register_verified') ||
            !session('register_account_id')
        ) {
            return redirect()->route('register.form');
        }

        return view('store.auth.register.password');
    }

    // Xử lý khi submit mật khẩu + họ tên
    public function submitPassword(RegisterPasswordRequest $request)
    {
        // Lấy dữ liệu đã được Request kiểm tra
        $data = $request->validated();

        if (!session('register_verified')) {
            return redirect()->route('register.form');
        }

        // Lấy account từ session
        $accountId = session('register_account_id');

        $account = Account::find($accountId);

        // Cập nhật mật khẩu và xác thực tài khoản
        $account->update([
            'password_hash' => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),
            'is_verified' => 1,
        ]);

        // Tạo thông tin người dùng
        User::create([
            'account_id' => $account->account_id,
            'full_name' => $data['full_name'],
        ]);

        // Xóa session đăng ký
        session()->forget([
            'register_account_id',
            'register_verified'
        ]);

        return redirect()->route('register.complete');
    }

    // Trang hoàn thành
    public function complete()
    {
        return view('store.auth.register.complete');
    }
}
