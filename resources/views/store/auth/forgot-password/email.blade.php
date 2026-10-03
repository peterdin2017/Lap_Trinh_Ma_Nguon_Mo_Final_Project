<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #003B73 0%, #002347 100%);
            min-height: 100vh;
        }
    </style>
</head>

<body>

    {{-- Header đơn giản, có logo --}}
    <div class="container pt-4">
        <a href="{{ route('store.home') }}" class="text-white text-decoration-none d-flex align-items-center"
            style="width: fit-content;">
            <i class="bi bi-shop me-2" style="font-size: 1.8rem;"></i>
            <span class="fw-bold" style="font-size: 1.3rem;">Website Bán hàng Online</span>
        </a>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center align-items-center" style="min-height: 75vh;">

            {{-- Cột trái: banner giới thiệu --}}
            <div class="col-lg-6 d-none d-lg-block text-white">
                <div class="px-4">
                    <i class="bi bi-shield-lock" style="font-size: 4rem;"></i>
                    <h1 class="fw-bold mt-3">Quên mật khẩu?</h1>
                    <p style="color: #D6E9FF; font-size: 1.1rem;">
                        Đừng lo, chúng tôi sẽ gửi mã OTP<br>
                        để bạn đặt lại mật khẩu.
                    </p>
                </div>
            </div>

            {{-- Cột phải: card nhập email --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3" style="color: #003B73;">Quên mật khẩu</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Lỗi không gắn với ô email (ví dụ lỗi chung từ controller) --}}
                        @if ($errors->any() && !$errors->has('email'))
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <p class="text-muted">
                            Nhập email đã đăng ký, chúng tôi sẽ gửi mã OTP vào hộp thư của bạn.
                        </p>

                        <form method="POST" action="{{ route('forgot.password.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="Nhập email" autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn w-100 btn-lg text-white fw-semibold"
                                style="background-color: #003B73;">
                                GỬI MÃ OTP
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0" style="color: #6c757d;">
                            <a href="{{ route('login.form') }}" class="fw-semibold text-decoration-none"
                                style="color: #003B73;">
                                <i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập
                            </a>
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
