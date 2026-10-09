<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký thành công - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <div class="text-success display-4 mb-3">✓</div>
                        <h1 class="h4 fw-bold">Đăng ký thành công!</h1>
                        <p class="text-secondary mb-4">
                            Tài khoản của bạn đã sẵn sàng sử dụng.
                        </p>
                        <a href="{{ route('login.form') }}" class="btn btn-primary btn-lg w-100">
                            Đăng nhập ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
