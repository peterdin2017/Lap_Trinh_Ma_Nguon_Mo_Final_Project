<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Admin - Website Bán hàng Online</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body style="background: linear-gradient(135deg, #003B73 0%, #002347 100%); min-height: 100vh;">
    <header class="container pt-4">
        <a href="{{ url('/') }}"
           class="text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-shop me-2 fs-3"></i>
            <span class="fw-bold fs-5">Website Bán hàng Online</span>
        </a>
    </header>

    <main class="container py-4">
        <div class="row justify-content-center align-items-center"
             style="min-height: calc(100vh - 100px);">

            <section class="col-lg-6 d-none d-lg-block text-white">
                <div class="px-4">
                    <i class="bi bi-shield-lock display-2"></i>
                    <h1 class="fw-bold mt-3">Khu vực quản trị</h1>
                    <p class="fs-5 text-white-50">
                        Đăng nhập để quản lý hoạt động của Website Bán hàng Online.
                    </p>
                    <div class="mt-4">
                        <p><i class="bi bi-check-circle-fill me-2"></i>Quản lý người bán</p>
                        <p><i class="bi bi-check-circle-fill me-2"></i>Quản lý sản phẩm</p>
                        <p><i class="bi bi-check-circle-fill me-2"></i>Theo dõi đơn hàng</p>
                    </div>
                </div>
            </section>

            <section class="col-12 col-md-8 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-lock text-primary display-5"></i>
                            <h2 class="h4 fw-bold text-primary mt-3 mb-2">
                                Đăng nhập Admin
                            </h2>
                            <p class="text-secondary mb-0">
                                Đăng nhập để tiếp tục quản trị hệ thống.
                            </p>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success" role="status">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Admin</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="Nhập email Admin"
                                    autocomplete="username"
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
                                    autocomplete="current-password"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end mb-4">
                                <a href="{{ route('forgot-password.form', ['portal' => 'admin']) }}"
                                   class="text-primary text-decoration-none">
                                    Quên mật khẩu?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Đăng nhập Admin
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ url('/') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>
                                Quay lại Website
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
