<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt lại mật khẩu</title>
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
                            Đặt lại mật khẩu
                        </h1>

                        <p class="text-secondary mb-4">
                            Nhập mật khẩu mới cho tài khoản của bạn.
                        </p>

                        <form method="POST" action="{{ route('forgot-password.password.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Xác nhận mật khẩu mới
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn text-white w-100"
                                    style="background-color: #003B73;">
                                Lưu mật khẩu mới
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
