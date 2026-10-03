<!DOCTYPE html>
<html lang="vi">
<body style="margin:0; padding:24px; background:#f4f6f8; font-family:Arial, sans-serif;">

    <div style="max-width:480px; margin:0 auto; background:#ffffff; border-radius:8px; padding:32px;">

        <h2 style="color:#003B73; margin-top:0;">
            @if ($purpose === 'reset_password')
                Đặt lại mật khẩu
            @else
                Xác nhận đăng ký tài khoản
            @endif
        </h2>

        <p style="color:#333;">
            @if ($purpose === 'reset_password')
                Chúng tôi nhận được yêu cầu <strong>đặt lại mật khẩu</strong> cho tài khoản
                đăng ký bằng email này tại Website Bán hàng Online.
            @else
                Cảm ơn bạn đã <strong>đăng ký tài khoản</strong> tại Website Bán hàng Online.
                Hãy nhập mã bên dưới để xác nhận email của bạn.
            @endif
        </p>

        <div style="text-align:center; margin:24px 0;">
            <span style="display:inline-block; padding:12px 24px; font-size:32px; font-weight:bold;
                         letter-spacing:8px; color:#003B73; background:#eaf2fb; border-radius:8px;">
                {{ $otpCode }}
            </span>
        </div>

        <p style="color:#666; font-size:14px;">
            Mã chỉ dùng được một lần và sẽ hết hạn sau ít phút. Vui lòng không chia sẻ mã này cho bất kỳ ai.
        </p>

        <p style="color:#999; font-size:13px; border-top:1px solid #eee; padding-top:16px;">
            @if ($purpose === 'reset_password')
                Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này. Mật khẩu của bạn vẫn an toàn.
            @else
                Nếu bạn không đăng ký tài khoản, hãy bỏ qua email này.
            @endif
        </p>

    </div>
</body>
</html>
