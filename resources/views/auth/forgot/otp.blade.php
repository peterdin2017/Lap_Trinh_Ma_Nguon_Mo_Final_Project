<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP - SHOP ONLINE</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
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
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-2 text-center" style="color: #003B73;">Nhập mã xác nhận</h4>
                        <p class="text-center text-muted mb-4">
                            Mã xác thực đã được gửi đến email<br>
                            <strong>{{ session('reset_email') }}</strong>
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @error('otp_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <form method="POST" action="{{ route('forgot.otp.verify') }}" id="otpForm">
                            @csrf
                            <div class="d-flex justify-content-center gap-2 mb-4" id="otpBoxes">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                                <input type="text" maxlength="1" class="form-control otp-input" inputmode="numeric">
                            </div>
                            <input type="hidden" name="otp_code" id="otpHidden">

                            <button type="submit" class="btn w-100 btn-lg text-white fw-semibold"
                                    style="background-color: #003B73;">
                                XÁC NHẬN
                            </button>
                        </form>

                        <form method="POST" action="{{ route('forgot.otp.resend') }}" class="text-center mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none" style="color: #003B73;">
                                Gửi lại mã
                            </button>
                        </form>

                        <p class="text-center mt-2 mb-0">
                            <a href="{{ route('forgot.form') }}" class="text-decoration-none" style="color: #003B73;">
                                Quay lại
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const boxes = document.querySelectorAll('#otpBoxes input');
        const hiddenInput = document.getElementById('otpHidden');
        const form = document.getElementById('otpForm');

        boxes.forEach((box, index) => {
            box.addEventListener('input', () => {
                box.value = box.value.replace(/[^0-9]/g, '');
                if (box.value.length === 1 && index < boxes.length - 1) {
                    boxes[index + 1].focus();
                }
            });
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && box.value === '' && index > 0) {
                    boxes[index - 1].focus();
                }
            });
        });

        form.addEventListener('submit', () => {
            let otpValue = '';
            boxes.forEach(box => otpValue += box.value);
            hiddenInput.value = otpValue;
        });
    </script>

</body>
</html>
