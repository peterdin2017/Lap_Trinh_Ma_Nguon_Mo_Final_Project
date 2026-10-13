<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpController extends Controller
{
    /**
     * Role tương ứng với từng cổng.
     */
    private const PORTAL_ROLES = [
        'store' => 'user',
        'seller' => 'seller',
        'admin' => 'admin',
    ];

    /**
     * Hiển thị form OTP đăng ký hoặc quên mật khẩu.
     */
    public function showForm(): View|RedirectResponse
    {
        $isForgotPassword = request()->routeIs('forgot-password.*');
        $portal = session('forgot_password_portal', 'store');

        $accountId = $isForgotPassword
            ? session('forgot_password_account_id')
            : session('register_account_id');

        if (! $accountId) {
            return $this->redirectToFlowForm($isForgotPassword, $portal);
        }

        $account = $this->findFlowAccount($isForgotPassword);

        if (! $account) {
            $this->clearFlowSession($isForgotPassword);

            return $this->redirectToFlowForm($isForgotPassword, $portal);
        }

        return view(
            $isForgotPassword
                ? 'auth.forgot-password.otp'
                : 'auth.register.verify-otp',
            ['email' => $account->email]
        );
    }

    /**
     * Xác minh OTP cho đúng tài khoản đang ở trong session.
     */
    public function verify(VerifyOtpRequest $request): RedirectResponse
    {
        $isForgotPassword = $request->routeIs('forgot-password.*');
        $portal = session('forgot_password_portal', 'store');

        $accountId = $isForgotPassword
            ? session('forgot_password_account_id')
            : session('register_account_id');

        if (! $accountId) {
            return $this->redirectToFlowForm($isForgotPassword, $portal);
        }

        $account = $this->findFlowAccount($isForgotPassword);

        if (! $account) {
            $this->clearFlowSession($isForgotPassword);

            return $this->redirectToFlowForm($isForgotPassword, $portal);
        }

        $purpose = $isForgotPassword
            ? 'reset_password'
            : 'register';

        $otp = Otp::query()
            ->where('account_id', $account->account_id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->orderByDesc('otp_id')
            ->first();

        if (! $otp) {
            return back()->withErrors([
                'otp_code' => 'Không tìm thấy mã OTP. Vui lòng gửi lại mã.',
            ]);
        }

        if (now()->greaterThan($otp->expires_at)) {
            return back()->withErrors([
                'otp_code' => 'Mã OTP đã hết hạn. Vui lòng gửi lại mã.',
            ]);
        }

        $maxAttempts = 5;

        if ($otp->attempts >= $maxAttempts) {
            return back()->withErrors([
                'otp_code' => 'Bạn đã nhập sai quá số lần cho phép. Vui lòng gửi mã mới.',
            ]);
        }

        $otpCode = $request->validated('otp_code');

        if (! Hash::check($otpCode, $otp->otp_hash)) {
            $otp->increment('attempts');

            $remainingAttempts = max(
                0,
                $maxAttempts - $otp->attempts
            );

            return back()
                ->withErrors([
                    'otp_code' => "Mã OTP không đúng. Bạn còn {$remainingAttempts} lần thử.",
                ])
                ->withInput();
        }

        DB::transaction(function () use ($otp): void {
            $otp->update([
                'used_at' => now(),
            ]);
        });

        if ($isForgotPassword) {
            session([
                'forgot_password_verified' => true,
            ]);

            return redirect()->route('forgot-password.password.form');
        }

        session([
            'register_verified' => true,
        ]);

        if (session('register_role') === 'seller') {
            return redirect()->route('seller.register.credentials.form');
        }

        return redirect()->route('register.password.form');
    }

    /**
     * Gửi lại OTP cho đúng tài khoản trong session.
     */
    public function resend(): RedirectResponse
    {
        $isForgotPassword = request()->routeIs('forgot-password.*');
        $portal = session('forgot_password_portal', 'store');

        $accountId = $isForgotPassword
            ? session('forgot_password_account_id')
            : session('register_account_id');

        if (! $accountId) {
            return $this->redirectToFlowForm($isForgotPassword, $portal);
        }

        $account = $this->findFlowAccount($isForgotPassword);

        if (! $account) {
            $this->clearFlowSession($isForgotPassword);

            return $this->redirectToFlowForm($isForgotPassword, $portal);
        }

        $purpose = $isForgotPassword
            ? 'reset_password'
            : 'register';

        Otp::query()
            ->where('account_id', $account->account_id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->update([
                'used_at' => now(),
            ]);

        $otpCode = (string) random_int(100000, 999999);

        $accountType = $isForgotPassword
            ? $account->role
            : (session('register_role') === 'seller' ? 'seller' : 'user');

        $otp = Otp::create([
            'account_id' => $account->account_id,
            'account_type' => $accountType,
            'email' => $account->email,
            'otp_hash' => Hash::make($otpCode),
            'attempts' => 0,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($account->email)->send(
                new OtpMail($otpCode, $purpose)
            );
        } catch (\Throwable $exception) {
            report($exception);

            $otp->update([
                'used_at' => now(),
            ]);

            return back()->withErrors([
                'otp_code' => 'Chưa gửi được mã OTP. Vui lòng thử lại sau.',
            ]);
        }

        return back()->with(
            'success',
            'Đã gửi mã OTP mới. Vui lòng kiểm tra email.'
        );
    }

    /**
     * Lấy đúng tài khoản theo account_id và role của luồng hiện tại.
     */
    private function findFlowAccount(bool $isForgotPassword): ?Account
    {
        $accountId = $isForgotPassword
            ? session('forgot_password_account_id')
            : session('register_account_id');

        if (! $accountId) {
            return null;
        }

        $account = Account::find($accountId);

        if (! $account) {
            return null;
        }

        if ($isForgotPassword) {
            $portal = session('forgot_password_portal');
            $expectedRole = self::PORTAL_ROLES[$portal] ?? null;

            if (! $expectedRole || $account->role !== $expectedRole) {
                return null;
            }

            return $account;
        }

        $expectedRole = session('register_role', 'user');

        if ($account->role !== $expectedRole) {
            return null;
        }

        return $account;
    }

    /**
     * Chuyển về đúng form bắt đầu của luồng hiện tại.
     */
    private function redirectToFlowForm(
        bool $isForgotPassword,
        string $portal
    ): RedirectResponse {
        if ($isForgotPassword) {
            if (! array_key_exists($portal, self::PORTAL_ROLES)) {
                $portal = 'store';
            }

            return redirect()->route('forgot-password.form', [
                'portal' => $portal,
            ]);
        }

        return redirect()->route($this->registerFormRoute());
    }

    /**
     * Chọn form đăng ký theo role.
     */
    private function registerFormRoute(): string
    {
        return session('register_role') === 'seller'
            ? 'seller.register.form'
            : 'register.form';
    }

    /**
     * Xóa session của luồng đang xử lý.
     */
    private function clearFlowSession(bool $isForgotPassword): void
    {
        if ($isForgotPassword) {
            session()->forget([
                'forgot_password_account_id',
                'forgot_password_verified',
                'forgot_password_portal',
            ]);

            return;
        }

        session()->forget([
            'register_account_id',
            'register_role',
            'register_verified',
        ]);
    }
}
