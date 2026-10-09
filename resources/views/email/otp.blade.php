<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã OTP</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #f4f6f8; font-family: Arial, sans-serif;">
    <div style="max-width: 480px; margin: 0 auto; padding: 32px; background-color: #ffffff; border-radius: 8px;">

        @if ($purpose === 'reset_password')
            <h2 style="margin-top: 0; color: #003B73;">Đặt lại mật khẩu</h2>

            <p style="color: #333333;">
                Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.
                Nhập mã bên dưới để tiếp tục.
            </p>
        @else
            <h2 style="margin-top: 0; color: #003B73;">Xác nhận đăng ký tài khoản</h2>

            <p style="color: #333333;">
                Cảm ơn bạn đã đăng ký tại Website Bán hàng Online.
                Hãy nhập mã bên dưới để xác nhận email.
            </p>
        @endif

        <div style="margin: 24px 0; text-align: center;">
            <span style="display: inline-block; padding: 12px 24px; background-color: #eaf2fb;
                         border-radius: 8px; color: #003B73; font-size: 32px;
                         font-weight: bold; letter-spacing: 8px;">
                {{ $otpCode }}
            </span>
        </div>

        <p style="color: #666666; font-size: 14px;">
            Mã OTP chỉ sử dụng được một lần và sẽ hết hạn sau ít phút.
            Vui lòng không chia sẻ mã này với bất kỳ ai.
        </p>

        @if ($purpose === 'reset_password')
            <p style="border-top: 1px solid #eeeeee; padding-top: 16px; color: #999999; font-size: 13px;">
                Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.
            </p>
        @else
            <p style="border-top: 1px solid #eeeeee; padding-top: 16px; color: #999999; font-size: 13px;">
                Nếu bạn không đăng ký tài khoản, hãy bỏ qua email này.
            </p>
        @endif

    </div>
</body>
</html>
