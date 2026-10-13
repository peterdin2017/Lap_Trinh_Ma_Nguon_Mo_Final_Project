<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Auth\RegisterEmailRequest;
use App\Http\Requests\Store\Auth\RegisterPasswordRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    // Hiển thị form nhập email.
    public function showEmailForm(): View
    {
        return view('store.auth.register');
    }

    // Tạo hoặc dùng lại tài khoản Store chưa xác thực rồi gửi OTP.
    public function submitEmail(RegisterEmailRequest $request): RedirectResponse
    {
        $email = $request->validated()['email'];

        // Xóa session đăng ký cũ trước khi bắt đầu luồng mới.
        session()->forget([
            'register_account_id',
            'register_role',
            'register_verified',
        ]);

        // Store sử dụng role user; tài khoản Seller/Admin cùng email được giữ riêng.
        $account = Account::where('email', $email)
            ->where('role', 'user')
            ->first();

        // Không cho đăng ký Store lần nữa nếu tài khoản đã xác thực.
        if ($account && $account->is_verified) {
            return back()
                ->withErrors([
                    'email' => 'Email này đã đăng ký tài khoản Store rồi.',
                ])
                ->withInput();
        }

        // Tạo tài khoản tạm nếu email chưa có tài khoản Store.
        if (! $account) {
            $account = Account::create([
                'email' => $email,
                'password_hash' => Str::random(32),
                'role' => 'user',
                'status' => 'active',
                'is_verified' => false,
            ]);
        }

        // Lưu thông tin để các bước OTP và tạo mật khẩu dùng tiếp.
        session([
            'register_account_id' => $account->account_id,
            'register_role' => 'user',
            'register_verified' => false,
        ]);

        $this->generateAndSendOtp($account);

        // Chuyển đến bước xác minh OTP.
        return redirect()->route('otp.form');
    }

    // Tạo OTP mới và vô hiệu hóa các OTP đăng ký cũ chưa dùng.
    private function generateAndSendOtp(Account $account): void
    {
        Otp::where('account_id', $account->account_id)
            ->where('account_type', 'user')
            ->where('purpose', 'register')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $otpCode = (string) random_int(100000, 999999);

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
    }

    // Chỉ hiển thị form tạo thông tin sau khi OTP đã được xác minh.
    public function showPasswordForm(): View|RedirectResponse
    {
        if (
            ! session('register_account_id') ||
            ! session('register_verified') ||
            session('register_role') !== 'user'
        ) {
            return redirect()->route('register.form');
        }

        // View nằm tại resources/views/auth/register/password.blade.php.
        return view('auth.register.password', [
            'step' => 2,
        ]);
    }

    // Lưu họ tên và mật khẩu, sau đó chuyển sang trang hoàn tất.
    public function submitPassword(
        RegisterPasswordRequest $request
    ): RedirectResponse {
        if (
            ! session('register_account_id') ||
            ! session('register_verified') ||
            session('register_role') !== 'user'
        ) {
            return redirect()->route('register.form');
        }

        // Chỉ lấy tài khoản Store, tránh cập nhật tài khoản khác cùng email.
        $account = Account::where('account_id', session('register_account_id'))
            ->where('role', 'user')
            ->first();

        if (! $account) {
            session()->forget([
                'register_account_id',
                'register_role',
                'register_verified',
            ]);

            return redirect()->route('register.form');
        }

        $data = $request->validated();

        // Lưu tài khoản và hồ sơ User cùng lúc.
        DB::transaction(function () use ($account, $data): void {
            $account->password_hash = $data['password'];
            $account->is_verified = true;
            $account->save();

            User::firstOrCreate(
                ['account_id' => $account->account_id],
                ['full_name' => $data['full_name']]
            );
        });

        // Xóa session đăng ký sau khi hoàn tất.
        session()->forget([
            'register_account_id',
            'register_role',
            'register_verified',
        ]);

        // Chuyển đến bước cuối: trang đăng ký thành công.
        return redirect()->route('register.complete');
    }

    // Hiển thị trang hoàn tất tại resources/views/auth/register/complete.blade.php.
    public function complete(): View
    {
        return view('auth.register.complete', [
            'step' => 3,
        ]);
    }
}
