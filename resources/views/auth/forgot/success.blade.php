<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thành công - SHOP ONLINE</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-5">

                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#198754" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </div>

                        <h4 class="fw-bold mb-2" style="color: #003B73;">Đặt lại mật khẩu thành công!</h4>
                        <p class="text-muted mb-4">Mật khẩu của bạn đã được cập nhật. Bạn có thể đăng nhập với mật khẩu mới.</p>

                        <a href="{{ route('login.form') }}" class="btn btn-lg text-white fw-semibold w-100"
                           style="background-color: #003B73;">
                            ĐĂNG NHẬP NGAY
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
