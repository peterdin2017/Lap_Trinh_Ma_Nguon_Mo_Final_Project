<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác thực OTP - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body class="bg-light">

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="progress mb-4" role="progressbar" aria-label="Tiến trình đăng ký">
                            <div class="progress-bar" style="width: 33%"></div>
                        </div>

                        <h1 class="h4 fw-bold text-center text-primary">Nhập mã xác nhận</h1>
                        <p class="text-center text-secondary mb-4">
                            Mã OTP đã được gửi đến<br>
                            <strong>{{ $email }}</strong>
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @error('otp_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @php
                            $isForgotPassword = request()->routeIs('forgot-password.*');

                            $verifyRoute = $isForgotPassword ? 'forgot-password.otp.verify' : 'otp.verify';
                        @endphp

                        <form method="POST" action="{{ route($verifyRoute) }}" id="otpForm">
                            @csrf

                            <div class="d-flex justify-content-center gap-2 mb-4" id="otpBoxes">
                                @for ($i = 0; $i < 6; $i++)
                                    <input type="text" maxlength="1" class="form-control text-center fs-4"
                                        style="width: 48px; height: 54px;" inputmode="numeric"
                                        aria-label="Số OTP {{ $i + 1 }}">
                                @endfor
                            </div>

                            <input type="hidden" name="otp_code" id="otpHidden">

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Xác minh
                            </button>
                        </form>

                        <form method="POST" action="{{ route('otp.resend') }}" class="text-center mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none">
                                Gửi lại mã
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const boxes = document.querySelectorAll('#otpBoxes input');
        const form = document.getElementById('otpForm');

        boxes.forEach((box, index) => {
            box.addEventListener('input', () => {
                box.value = box.value.replace(/[^0-9]/g, '');

                if (box.value && index < boxes.length - 1) {
                    boxes[index + 1].focus();
                }
            });

            box.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !box.value && index > 0) {
                    boxes[index - 1].focus();
                }
            });
        });

        form.addEventListener('submit', () => {
            document.getElementById('otpHidden').value =
                Array.from(boxes).map(box => box.value).join('');
        });
    </script>
</body>

</html>
