<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Hiển trị form đăng ký
    public function showForm()
    {
        return view('register');
    }

    // Xử lý khi người dùng bấm nút "Đăng ký"
    public function register(Request $request)
    {
        // Kiểm tra dữ liệu người dùng
        $request->validate([
            // Kiểm tra 
            'email' => 'required|email|unique:accounts,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'full_name' => 'required|string|max:255',
        ], [
            // Thông báo khi lỗi
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được đăng ký rồi',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.mixed' => 'Mật khẩu phải có cả chữ hoa và chữ thường',
            'password.numbers' => 'Mật khẩu phải có ít nhất 1 chữ số',
            'password.symbols' => 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt',
            'full_name.required' => 'Vui lòng nhập họ tên',
        ]);

        // Tạo bảng account
        $account = Account::create([
            'email' => $request->email,
            'password_hash' => password_hash($request->password, PASSWORD_DEFAULT),
        ]);

        // Tạo thông tin cá nhân (bảng user)
        User::create([
            'account_id' => $account->account_id,
            'full_name' => $request->full_name,
        ]);

        // Sinh OTP ngẫu nhiên
        $otpCode = rand(100000, 999999);

        // Lưu OTP vào db
        Otp::create([
            'account_id' => $account->account_id,
            'otp_code' => $otpCode,
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(5),
        ]);


        //Tạo đối tượng email OTP, truyền mã OTP vào
        $otpMail = new OtpMail($otpCode);

        // Gửi email đến địa chỉ email của tài khoản vừa đăng ký
        Mail::to($account->email)->send($otpMail);

        // Chuẩn bị thông báo sẽ hiển thị ở trang tiếp theo
        $thongBao = 'Đăng ký thành công, vui lòng kiểm tra email để lấy mã OTP';

        // Chuẩn bị dữ liệu sẽ gửi kèm theo URL (account_id)
        $duLieuGuiKem = ['account_id' => $account->account_id];

        // Tạo đường dẫn chuyển hướng tới trang nhập OTP
        $duongDanChuyenHuong = redirect()->route('otp.form', $duLieuGuiKem);

        // Gắn thông báo vào đường dẫn đó rồi trả về
        return $duongDanChuyenHuong->with('success', $thongBao);
    }
}
