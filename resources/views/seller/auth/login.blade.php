<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Seller</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body style="background: linear-gradient(135deg, #003B73 0%, #002347 100%); min-height: 100vh;">

    <header class="container pt-4">
        <a href="{{ route('store.home') }}"
           class="text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-shop me-2 fs-3"></i>
            <span class="fw-bold fs-5">Kênh Người Bán</span>
        </a>
    </header>

    <main class="container py-4">
        <div class="row justify-content-center align-items-center"
             style="min-height: calc(100vh - 100px);">

            <section class="col-lg-6 d-none d-lg-block text-white">
                <div class="px-4">
                    <i class="bi bi-shop-window display-2"></i>
                    <h1 class="fw-bold mt-3">Chào mừng trở lại</h1>
                    <p class="fs-5 text-white-50">
                        Đăng nhập để quản lý cửa hàng và hoạt động bán hàng của bạn.
                    </p>
                </div>
            </section>

            <section class="col-12 col-md-8 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-2 text-primary">
                            Đăng nhập Seller
                        </h2>

                        <p class="text-secondary mb-4">
                            Nhập email và mật khẩu tài khoản Seller của bạn.
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('seller.login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="Nhập email tài khoản Seller"
                                    required
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="Nhập mật khẩu"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end mb-3">
                                <a href="{{ route('forgot-password.form', ['portal' => 'seller']) }}"
                                   class="text-primary text-decoration-none">
                                    Quên mật khẩu?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Đăng nhập
                            </button>
                        </form>

                        <p class="text-center text-secondary mt-4 mb-0">
                            Bạn chưa có tài khoản Seller?
                            <a href="{{ route('seller.register.form') }}"
                               class="fw-semibold text-decoration-none">
                                Đăng ký
                            </a>
                        </p>
                    </div>
                </div>
            </section>

        </div>
    </main>
</body>

</html>
