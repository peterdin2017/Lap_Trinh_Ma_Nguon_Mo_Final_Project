<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    // Hiển thị form nhập emai, laravel sẽ tìm resources/views/auth/register/email.blade.php
    public function showEmailForm()
    {
        return view('auth.register.email');
    }

    // Nhận email
    public function submitEmail(Request $request)
    {
        // Xử lý dữ liệu email đầu vào
        $request->validate([
            'email' => 'required|email|',
        ], [

            // Thông báo nếu có lỗi
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
        ]);

        // Bắt đầu một quá trình đăng ký mới
        // Xóa trạng thái xác thực OTP của lần đăng ký trước
        session()->forget('register_verified');


        // Tìm tài khoản theo gmail
        $account = Account::where('email', $request->email)->first();

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

            // Chuyển sang bước nhập OTP
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
        $accountId = $account->account_id;
        session(['register_account_id' => $accountId]);

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

        return view('auth.register.password');
    }

    // Xử lý khi submit mật khẩu + họ tên
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

        // Lấy giá trị register_account_id đang được lưu trong session và đưa vào biến $accountId
        $accountId = session('register_account_id');
        $account = Account::find($accountId);

        // Cập nhật mật khẩu thật ghi đè mật khẩu tạm và đánh dấu đã xác thực
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

    // Trang hoàn thành
    public function complete()
    {
        return view('auth.register.complete');
    }
}
