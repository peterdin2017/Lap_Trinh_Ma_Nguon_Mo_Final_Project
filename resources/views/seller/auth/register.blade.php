<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký người bán - Website Bán hàng Online</title>

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
        <div class="row justify-content-center align-items-center min-vh-75">

            <section class="col-lg-6 d-none d-lg-block text-white">
                <div class="px-4">
                    <i class="bi bi-bag-heart display-2"></i>
                    <h1 class="fw-bold mt-3">Bắt đầu bán hàng</h1>
                    <p class="fs-5 text-white-50">
                        Tạo tài khoản người bán và cửa hàng của bạn để bắt đầu kinh doanh.
                    </p>
                </div>
            </section>

            <section class="col-12 col-md-8 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-2 text-primary">Đăng ký Người Bán</h2>

                        <form method="POST" action="{{ route('seller.register.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="Nhập email"
                                    maxlength="100"
                                    required
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Tiếp theo
                            </button>
                        </form>

                        <p class="text-center text-secondary mt-4 mb-0">
                            Đã có tài khoản người bán?
                            <a href="{{ route('seller.login.form') }}"
                               class="fw-semibold text-decoration-none">
                                Đăng nhập
                            </a>
                        </p>

                        <p class="text-center small mt-3 mb-4">
                            Bằng việc đăng ký, bạn đồng ý với
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
