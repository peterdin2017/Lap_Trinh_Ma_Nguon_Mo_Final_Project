{{-- Nội dung email được gửi đi --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f7; padding: 40px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #0d6efd; padding: 24px 32px;">
                            <span style="color: #ffffff; font-size: 20px; font-weight: bold;">LTMNM Team 6</span>
                        </td>
                    </tr>

                    <!-- Nội dung -->
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin: 0 0 8px 0; color: #212529; font-size: 20px;">Xác thực tài khoản</h2>
                            <p style="margin: 0 0 24px 0; color: #6c757d; font-size: 14px; line-height: 1.5;">
                                Chào bạn, vui lòng sử dụng mã bên dưới để hoàn tất đăng ký tài khoản.
                            </p>

                            <!-- Khung mã OTP -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="background-color: #f1f5ff; border: 1px dashed #0d6efd; border-radius: 8px; padding: 20px;">
                                        <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #0d6efd;">
                                            {{ $otpCode }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0 0; color: #6c757d; font-size: 13px; line-height: 1.5;">
                                Mã có hiệu lực trong <strong>5 phút</strong>. Vui lòng không chia sẻ mã này với bất kỳ ai để bảo vệ tài khoản của bạn.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 16px 32px; text-align: center;">
                            <span style="color: #adb5bd; font-size: 12px;">
                                Đây là email tự động, vui lòng không trả lời.
                            </span>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
