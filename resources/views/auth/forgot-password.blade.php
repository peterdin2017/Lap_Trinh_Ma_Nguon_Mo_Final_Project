<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quên mật khẩu</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="min-vh-100 d-flex align-items-center"
      style="background: linear-gradient(135deg, #003B73 0%, #002347 100%);">

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 fw-bold mb-2" style="color: #003B73;">
                            Quên mật khẩu
                        </h1>

                        <p class="text-secondary mb-4">
                            Nhập email tài khoản để nhận mã xác thực.
                        </p>

                        <form method="POST" action="{{ route('forgot-password.send-otp') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Nhập email của bạn"
                                    required
                                >

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn text-white w-100"
                                    style="background-color: #003B73;">
                                Gửi mã OTP
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            <a href="{{ route('login.form') }}" class="text-decoration-none">
                                Quay lại đăng nhập
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
