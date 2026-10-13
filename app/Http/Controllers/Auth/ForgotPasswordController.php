<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPassword\ForgotPasswordEmailRequest;
use App\Http\Requests\Auth\ForgotPassword\ResetPasswordRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Ánh xạ cổng đăng nhập sang role lưu trong bảng accounts.
     */
    private const PORTAL_ROLES = [
        'store' => 'user',
        'seller' => 'seller',
        'admin' => 'admin',
    ];

    /**
     * Bước 1/4: hiển thị trang nhập email.
     */
    public function showEmailForm(Request $request): View
    {
        $portal = $request->query('portal', 'store');

        if (! array_key_exists($portal, self::PORTAL_ROLES)) {
            $portal = 'store';
        }

        return view('auth.forgot-password.email', [
            'portal' => $portal,
        ]);
    }

    /**
     * Bước 1/4: tìm tài khoản theo portal và gửi OTP.
     */
    public function sendOtp(
        ForgotPasswordEmailRequest $request
    ): RedirectResponse {
        $email = $request->validated('email');

        // Portal được gửi trong form; query là giá trị dự phòng.
        $portal = $request->input(
            'portal',
            $request->query('portal', 'store')
        );

        if (! array_key_exists($portal, self::PORTAL_ROLES)) {
            return back()
                ->withErrors([
                    'email' => 'Không xác định được loại tài khoản.',
                ])
                ->withInput();
        }

        // Xóa trạng thái cũ để không dùng nhầm một lượt đặt lại trước đó.
        $this->clearResetSession();

        $role = self::PORTAL_ROLES[$portal];

        // Tìm đúng tài khoản theo cả email và role.
        $account = Account::query()
            ->where('email', $email)
            ->where('role', $role)
            ->where('status', 'active')
            ->where('is_verified', true)
            ->first();

        if (! $account) {
            return back()
                ->withErrors([
                    'email' => 'Không tìm thấy tài khoản đã xác minh với email này.',
                ])
                ->withInput();
        }

        // Vô hiệu hóa OTP đặt lại mật khẩu cũ của đúng tài khoản.
        Otp::query()
            ->where('account_id', $account->account_id)
            ->where('purpose', 'reset_password')
            ->whereNull('used_at')
            ->update([
                'used_at' => now(),
            ]);

        $otpCode = (string) random_int(100000, 999999);

        $otp = Otp::create([
            'account_id' => $account->account_id,
            'account_type' => $account->role,
            'email' => $account->email,
            'otp_hash' => Hash::make($otpCode),
            'attempts' => 0,
            'purpose' => 'reset_password',
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($account->email)->send(
                new OtpMail($otpCode, 'reset_password')
            );
        } catch (\Throwable $exception) {
            report($exception);

            // Vô hiệu hóa OTP nếu không gửi được email.
            $otp->update([
                'used_at' => now(),
            ]);

            return back()
                ->withErrors([
                    'email' => 'Chưa gửi được mã OTP. Vui lòng thử lại.',
                ])
                ->withInput();
        }

        // Giữ ID và portal để các bước sau tiếp tục dùng đúng tài khoản.
        session([
            'forgot_password_account_id' => $account->account_id,
            'forgot_password_verified' => false,
            'forgot_password_portal' => $portal,
        ]);

        return redirect()
            ->route('forgot-password.otp.form', [
                'portal' => $portal,
            ])
            ->with('success', 'Mã OTP đã được gửi đến email của bạn.');
    }

    /**
     * Bước 3/4: chỉ mở form mật khẩu sau khi OTP được xác minh.
     */
    public function showPasswordForm(): View|RedirectResponse
    {
        $account = $this->getResetAccount();

        if (! $account || ! session('forgot_password_verified')) {
            return redirect()->route('forgot-password.form', [
                'portal' => session('forgot_password_portal', 'store'),
            ])->withErrors([
                'email' => 'Vui lòng xác minh OTP trước khi tạo mật khẩu mới.',
            ]);
        }

        return view('auth.forgot-password.password');
    }

    /**
     * Bước 3/4: lưu mật khẩu mới cho đúng tài khoản đã xác minh OTP.
     */
    public function resetPassword(
        ResetPasswordRequest $request
    ): RedirectResponse {
        $account = $this->getResetAccount();

        if (! $account || ! session('forgot_password_verified')) {
            $portal = session('forgot_password_portal', 'store');

            $this->clearResetSession();

            return redirect()->route('forgot-password.form', [
                'portal' => $portal,
            ]);
        }

        $account->update([
            'password_hash' => Hash::make(
                $request->validated('password')
            ),
        ]);

        $portal = session('forgot_password_portal', 'store');

        $loginRoute = match ($portal) {
            'seller' => 'seller.login.form',
            'admin' => 'admin.login.form',
            default => 'login.form',
        };

        // Xóa trạng thái của luồng quên mật khẩu.
        $this->clearResetSession();

        // Nếu đang đăng nhập đúng tài khoản vừa đổi mật khẩu,
        // đăng xuất để yêu cầu đăng nhập lại bằng mật khẩu mới.
        if (
            Auth::check() &&
            (int) Auth::id() === (int) $account->account_id
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()
            ->route('forgot-password.complete')
            ->with('password_reset_login_route', $loginRoute);
    }

    /**
     * Bước 4/4: hiển thị thông báo đặt lại mật khẩu thành công.
     */
    public function complete(): View|RedirectResponse
    {
        $loginRoute = session('password_reset_login_route');

        if (! $loginRoute) {
            return redirect()->route('forgot-password.form');
        }

        return view('auth.forgot-password.success', [
            'loginRoute' => $loginRoute,
        ]);
    }

    /**
     * Lấy lại đúng tài khoản đã nhận OTP, kiểm tra cả ID và role.
     */
    private function getResetAccount(): ?Account
    {
        $accountId = session('forgot_password_account_id');
        $portal = session('forgot_password_portal');

        if (
            ! $accountId ||
            ! array_key_exists($portal, self::PORTAL_ROLES)
        ) {
            return null;
        }

        return Account::query()
            ->where('account_id', $accountId)
            ->where('role', self::PORTAL_ROLES[$portal])
            ->where('status', 'active')
            ->where('is_verified', true)
            ->first();
    }

    /**
     * Xóa trạng thái của quy trình quên mật khẩu.
     */
    private function clearResetSession(): void
    {
        session()->forget([
            'forgot_password_account_id',
            'forgot_password_verified',
            'forgot_password_portal',
        ]);
    }
}
