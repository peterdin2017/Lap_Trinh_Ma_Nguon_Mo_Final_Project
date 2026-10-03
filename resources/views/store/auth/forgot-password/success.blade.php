<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu thành công - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 text-center">

                @include('store.auth.partials.step-indicator', ['current' => 3])

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-5">

                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                             style="width: 70px; height: 70px; background-color: #d1e7dd; font-size: 2rem; color: #198754;">
                            ✓
                        </div>

                        <h4 class="fw-bold mt-3 mb-2">Đặt lại mật khẩu thành công!</h4>
                        <p class="text-muted mb-4">Bạn có thể đăng nhập bằng mật khẩu mới.</p>

                        {{-- Trả về trang đăng nhập --}}
                        <a href="{{ route('login.form') }}" class="btn btn-lg text-white fw-semibold w-100"
                           style="background-color: #003B73;">
                            ĐĂNG NHẬP NGAY
                        </a>

                        <a href="{{ route('store.home') }}" class="d-block mt-3 text-decoration-none"
                           style="color: #6c757d;">
                            Về trang chủ
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
