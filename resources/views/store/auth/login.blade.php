<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body style="background: linear-gradient(135deg, #003B73 0%, #002347 100%); min-height: 100vh;">

    <header class="container pt-4">
        <a href="{{ route('store.home') }}" class="text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-shop me-2 fs-3"></i>
            <span class="fw-bold fs-5">Website Bán hàng Online</span>
        </a>
    </header>

    <main class="container py-4">
        <div class="row justify-content-center align-items-center min-vh-75">
            <section class="col-lg-6 d-none d-lg-block text-white">
                <div class="px-4">
                    <i class="bi bi-bag-heart display-2"></i>
                    <h1 class="fw-bold mt-3">Chào mừng trở lại</h1>
                    <p class="fs-5 text-white-50">
                        Đăng nhập để tiếp tục mua sắm tại Website Bán hàng Online.
                    </p>
                </div>
            </section>

            <section class="col-12 col-md-8 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-4 text-primary">Đăng nhập</h2>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" class="form-control form-control-lg"
                                    value="{{ old('email') }}" placeholder="Nhập email" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input id="password" type="password" name="password"
                                    class="form-control form-control-lg" placeholder="Nhập mật khẩu" required>
                            </div>

                            <div class="text-end mb-3">
                                <a href="{{ route('forgot-password.form', ['portal' => 'store']) }}"
                                    class="text-primary text-decoration-none">
                                    Quên mật khẩu?
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Đăng nhập
                            </button>
                        </form>

                        <p class="text-center text-secondary mt-4 mb-0">
                            Bạn chưa có tài khoản?
                            <a href="{{ route('register.form') }}" class="fw-semibold text-decoration-none">
                                Đăng ký
                            </a>
                        </p>
                        <p class="text-center small mt-3 mb-4">
                            Bằng việc đăng nhập, bạn đồng ý với
                            <a href="{{ route('page.terms') }}">Điều khoản dịch vụ</a>
                            và
                            <a href="{{ route('page.privacy') }}">Chính sách bảo mật</a>.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
