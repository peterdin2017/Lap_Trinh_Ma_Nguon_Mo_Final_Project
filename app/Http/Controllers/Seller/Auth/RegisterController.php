<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Auth\RegisterCredentialsRequest;
use App\Http\Requests\Seller\Auth\RegisterEmailRequest;
use App\Http\Requests\Seller\Auth\RegisterShopRequest;
use App\Mail\OtpMail;
use App\Models\Account;
use App\Models\Otp;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Hiển thị form nhập email đăng ký người bán.
     */
    public function showForm(): View
    {
        return view('seller.auth.register');
    }

    /**
     * Tạo hoặc lấy tài khoản người bán chưa xác minh,
     * sau đó gửi OTP đến email đăng ký.
     */
    public function sendOtp(RegisterEmailRequest $request): RedirectResponse
    {
        $email = $request->validated('email');

        $account = Account::where('email', $email)
            ->where('role', 'seller')
            ->first();

        // Email đã hoàn tất đăng ký người bán thì không đăng ký lại.
        if ($account && $account->is_verified) {
            return back()
                ->withErrors([
                    'email' => 'Email này đã có tài khoản người bán hoàn tất đăng ký.',
                ])
                ->withInput();
        }

        // Tạo tài khoản chờ hoàn tất nếu email chưa có tài khoản người bán.
        if (! $account) {
            $account = Account::create([
                'email' => $email,
                'password_hash' => Str::random(64),
                'role' => 'seller',
                'status' => 'active',
                'is_verified' => false,
            ]);
        }

        $otpCode = (string) random_int(100000, 999999);

        // Đánh dấu OTP đăng ký cũ chưa sử dụng là đã dùng.
        Otp::where('account_id', $account->account_id)
            ->where('purpose', 'register')
            ->whereNull('used_at')
            ->update([
                'used_at' => now(),
            ]);

        // Lưu mã OTP đã băm để xác minh sau.
        Otp::create([
            'account_id' => $account->account_id,
            'account_type' => 'seller',
            'email' => $account->email,
            'otp_hash' => Hash::make($otpCode),
            'attempts' => 0,
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(5),
        ]);

        // Ghi trạng thái đăng ký người bán vào session.
        session([
            'register_account_id' => $account->account_id,
            'register_role' => 'seller',
            'register_verified' => false,
        ]);

        // Xóa tên shop cũ nếu người dùng bắt đầu lại quy trình.
        session()->forget('seller_register_shop_name');

        Mail::to($account->email)->send(
            new OtpMail($otpCode, 'register')
        );

        return redirect()
            ->route('seller.register.otp.form')
            ->with('success', 'Mã OTP đã được gửi đến email của bạn.');
    }

    /**
     * Hiển thị bước tạo tên shop và mật khẩu người bán.
     * Đây là bước 3/4 theo mẫu giao diện.
     */
    public function showCredentialsForm(): View|RedirectResponse
    {
        if (
            ! session('register_account_id') ||
            ! session('register_verified') ||
            session('register_role') !== 'seller'
        ) {
            return redirect()->route('seller.register.form');
        }

        $account = Account::find(session('register_account_id'));

        if (! $account || $account->role !== 'seller') {
            $this->clearRegistrationSession();

            return redirect()->route('seller.register.form');
        }

        return view('seller.auth.credentials', [
            'email' => $account->email,
            'step' => 3,
            'totalSteps' => 4,
            'stepLabel' => 'Tạo tài khoản người bán',
        ]);
    }

    /**
     * Lưu tên shop và mật khẩu, sau đó chuyển sang bước thông tin cửa hàng.
     */
    public function saveCredentials(
        RegisterCredentialsRequest $request
    ): RedirectResponse {
        if (
            ! session('register_account_id') ||
            ! session('register_verified') ||
            session('register_role') !== 'seller'
        ) {
            return redirect()->route('seller.register.form');
        }

        $account = Account::find(session('register_account_id'));

        if (! $account || $account->role !== 'seller') {
            $this->clearRegistrationSession();

            return redirect()->route('seller.register.form');
        }

        $data = $request->validated();

        // Account model có cast hashed cho password_hash.
        // Gán mật khẩu gốc để model tự băm khi lưu.
        $account->password_hash = $data['password'];
        $account->save();

        session([
            'seller_register_shop_name' => $data['shop_name'],
        ]);

        return redirect()->route('seller.register.shop.form');
    }

    /**
     * Hiển thị bước nhập địa chỉ và thông tin cửa hàng.
     * Đây là bước 4/4 theo mẫu giao diện.
     */
    public function showShopForm(): View|RedirectResponse
    {
        if (
            ! session('register_account_id') ||
            ! session('register_verified') ||
            session('register_role') !== 'seller' ||
            ! session('seller_register_shop_name')
        ) {
            return redirect()->route('seller.register.form');
        }

        $account = Account::find(session('register_account_id'));

        if (! $account || $account->role !== 'seller') {
            $this->clearRegistrationSession();

            return redirect()->route('seller.register.form');
        }

        return view('seller.auth.shop-information', [
            'email' => $account->email,
            'shopName' => session('seller_register_shop_name'),
            'step' => 4,
            'totalSteps' => 4,
            'stepLabel' => 'Thông tin cửa hàng',
        ]);
    }

    /**
     * Lưu hồ sơ cửa hàng và hoàn tất đăng ký người bán.
     */
    public function submitShopInfo(
        RegisterShopRequest $request
    ): RedirectResponse {
        if (
            ! session('register_account_id') ||
            ! session('register_verified') ||
            session('register_role') !== 'seller' ||
            ! session('seller_register_shop_name')
        ) {
            return redirect()->route('seller.register.form');
        }

        $account = Account::find(session('register_account_id'));

        if (
            ! $account ||
            $account->role !== 'seller' ||
            $account->seller()->exists()
        ) {
            $this->clearRegistrationSession();

            return redirect()
                ->route('seller.register.form')
                ->withErrors([
                    'email' => 'Không thể tạo hồ sơ người bán cho tài khoản này.',
                ]);
        }

        $data = $request->validated();
        $shopName = session('seller_register_shop_name');

        DB::transaction(function () use ($account, $data, $shopName): void {
            Seller::create([
                'account_id' => $account->account_id,
                'shop_name' => $shopName,
                'shop_address' => $data['shop_address'] ?? null,
                'shop_province' => $data['shop_province'] ?? null,
                'shop_district' => $data['shop_district'] ?? null,
                'shop_ward' => $data['shop_ward'] ?? null,
                'status' => 'pending',
            ]);

            // Chỉ xác minh tài khoản sau khi hoàn tất hồ sơ đăng ký.
            $account->is_verified = true;
            $account->save();
        });

        $this->clearRegistrationSession();

        return redirect()
            ->route('seller.login.form')
            ->with(
                'success',
                'Hồ sơ người bán đã được gửi và đang chờ quản trị viên duyệt.'
            );
    }

    /**
     * Xóa dữ liệu đăng ký tạm khỏi session.
     */
    private function clearRegistrationSession(): void
    {
        session()->forget([
            'register_account_id',
            'register_role',
            'register_verified',
            'seller_register_shop_name',
        ]);
    }
}
