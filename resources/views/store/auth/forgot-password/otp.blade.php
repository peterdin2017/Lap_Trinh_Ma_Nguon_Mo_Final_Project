<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực OTP - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        /* 6 ô nhập OTP, mỗi ô 1 số */
        .otp-input {
            width: 50px;
            height: 55px;
            text-align: center;
            font-size: 1.4rem;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                @include('store.auth.partials.step-indicator', ['current' => 1])

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-2 text-center" style="color: #003B73;">Nhập mã xác nhận</h4>
                        <p class="text-center text-muted mb-4">
                            Mã xác thực đã được gửi đến email của bạn.<br>
                            Vui lòng kiểm tra hộp thư để lấy mã.
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Hiện lỗi đầu tiên, bất kể controller đặt tên khóa lỗi là gì --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('forgot.password.otp.verify') }}" id="otpForm">
                            @csrf

                            {{-- Người dùng thấy 6 ô riêng biệt, nhưng thực chất chỉ gửi 1 giá trị --}}
                            <div class="d-flex justify-content-center gap-2 mb-4" id="otpBoxes">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                            </div>

                            {{-- Input ẩn, gộp 6 số thành 1 chuỗi (tên khớp ForgotPasswordOtpRequest) --}}
                            <input type="hidden" name="otp_code" id="otpHidden">

                            <button type="submit" class="btn w-100 btn-lg text-white fw-semibold"
                                    style="background-color: #003B73;">
                                KẾ TIẾP
                            </button>
                        </form>

                        {{-- Nút gửi lại mã --}}
                        <form method="POST" action="{{ route('forgot.password.otp.resend') }}" class="text-center mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none" style="color: #003B73;">
                                Gửi lại mã
                            </button>
                        </form>

                        <p class="text-center mt-2 mb-0">
                            <a href="{{ route('forgot.password.form') }}" class="text-decoration-none"
                               style="color: #6c757d;">Dùng email khác</a>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Gõ số vào từng ô, tự nhảy sang ô kế tiếp
        const boxes = document.querySelectorAll('#otpBoxes input');
        const hiddenInput = document.getElementById('otpHidden');
        const form = document.getElementById('otpForm');

        boxes.forEach((box, index) => {
            box.addEventListener('input', () => {
                // Chỉ cho phép nhập số
                box.value = box.value.replace(/[^0-9]/g, '');

                // Đã gõ 1 số thì nhảy sang ô tiếp theo
                if (box.value.length === 1 && index < boxes.length - 1) {
                    boxes[index + 1].focus();
                }
            });

            // Nhấn Backspace ở ô trống thì quay lại ô trước
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && box.value === '' && index > 0) {
                    boxes[index - 1].focus();
                }
            });
        });

        // Dán cả mã 6 số vào ô đầu thì tự chia ra từng ô
        boxes[0].addEventListener('paste', (e) => {
            e.preventDefault();
            const digits = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, 6);
            digits.split('').forEach((d, i) => boxes[i].value = d);
            boxes[Math.min(digits.length, boxes.length - 1)].focus();
        });

        // Trước khi gửi, gộp 6 ô thành 1 chuỗi gán vào input ẩn
        form.addEventListener('submit', () => {
            let otpValue = '';
            boxes.forEach(box => otpValue += box.value);
            hiddenInput.value = otpValue;
        });
    </script>

</body>
</html>
