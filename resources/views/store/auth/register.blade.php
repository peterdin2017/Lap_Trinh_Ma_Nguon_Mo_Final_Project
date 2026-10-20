<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký - Website Bán hàng Online</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
            --auth-border: #E2E8F0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--auth-text);
            background: #F4F7FA;
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
        }

        .register-page {
            min-height: 100vh;
        }

        .register-hero {
            position: relative;
            display: flex;
            min-height: 680px;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: clamp(2rem, 5vw, 4rem);
            color: #fff;
            background: linear-gradient(135deg, #1A94FF 0%, #0077E6 100%);
            border-radius: 16px;
        }

        .register-hero::before,
        .register-hero::after {
            position: absolute;
            content: "";
            pointer-events: none;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 50%;
        }

        .register-hero::before {
            top: -130px;
            right: -100px;
            width: 360px;
            height: 360px;
            background: rgba(255, 255, 255, .08);
            box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
                        0 0 0 70px rgba(255, 255, 255, .025);
        }

        .register-hero::after {
            bottom: -180px;
            left: -130px;
            width: 390px;
            height: 390px;
            background: rgba(255, 255, 255, .06);
            box-shadow: 0 0 0 35px rgba(255, 255, 255, .03),
                        0 0 0 70px rgba(255, 255, 255, .02);
        }

        .register-hero__brand,
        .register-hero__content,
        .register-hero__footer {
            position: relative;
            z-index: 1;
        }

        .register-hero__brand {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            text-decoration: none;
        }

        .register-hero__brand i {
            font-size: 1.8rem;
        }

        .register-hero__content {
            max-width: 520px;
            margin-block: auto;
            padding-block: 3rem;
        }

        .register-hero__icon {
            display: inline-flex;
            width: 96px;
            height: 96px;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.75rem;
            color: #fff;
            font-size: 2.75rem;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .26);
            border-radius: 28px;
            backdrop-filter: blur(8px);
        }

        .register-hero__title {
            margin-bottom: .75rem;
            font-size: clamp(2rem, 3vw, 2.5rem);
            font-weight: 700;
            line-height: 1.2;
        }

        .register-hero__description {
            max-width: 440px;
            margin: 0;
            color: rgba(255, 255, 255, .85);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .register-hero__footer {
            color: rgba(255, 255, 255, .78);
            font-size: .875rem;
        }

        .register-form-card {
            width: 100%;
            max-width: 500px;
            margin-inline: auto;
            background: #fff;
            border: 1px solid var(--auth-border);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(26, 148, 255, .08);
        }

        .register-form-card__body {
            padding: clamp(1.5rem, 4vw, 2.75rem);
        }

        .register-form-card__title {
            margin-bottom: .5rem;
            color: var(--auth-text);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .register-form-card__subtitle {
            margin-bottom: 1.75rem;
            color: var(--auth-muted);
            font-size: .95rem;
        }

        .register-form .form-label {
            color: var(--auth-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .register-input-group {
            min-height: 48px;
            overflow: hidden;
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .register-input-group:focus-within {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .register-input-group .input-group-text {
            min-width: 48px;
            justify-content: center;
            color: var(--auth-muted);
            background: #fff;
            border: 0;
        }

        .register-input-group .form-control {
            min-height: 46px;
            color: var(--auth-text);
            background: #fff;
            border: 0;
            box-shadow: none;
        }

        .register-input-group .form-control:focus {
            box-shadow: none;
        }

        .register-input-group .form-control::placeholder {
            color: #94A3B8;
        }

        .register-submit {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .register-submit:hover,
        .register-submit:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        .register-login-link,
        .register-policy-links a {
            color: var(--auth-primary);
            font-weight: 600;
            text-decoration: none;
        }

        .register-login-link:hover,
        .register-policy-links a:hover {
            color: var(--auth-primary-hover);
            text-decoration: underline;
        }

        .register-policy-links {
            color: var(--auth-muted);
            font-size: .78rem;
            line-height: 1.6;
        }

        @media (max-width: 991.98px) {
            .register-page {
                padding-block: 1.5rem;
            }

            .register-hero {
                min-height: 350px;
                padding: 1.75rem;
            }

            .register-hero__content {
                padding-block: 2rem;
            }

            .register-hero__icon {
                width: 68px;
                height: 68px;
                margin-bottom: 1rem;
                font-size: 2rem;
                border-radius: 20px;
            }

            .register-hero__title {
                font-size: 1.75rem;
            }

            .register-hero__description {
                font-size: .95rem;
            }
        }

        @media (max-width: 575.98px) {
            .register-page {
                padding: .75rem;
            }

            .register-hero {
                min-height: 310px;
                border-radius: 12px;
            }

            .register-form-card {
                border-radius: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>

<body>
    <main class="container-fluid register-page d-flex align-items-center py-lg-4">
        <div class="container">
            <div class="row align-items-stretch justify-content-center g-4 g-xl-5">

                {{-- Hero thương hiệu --}}
                <section class="col-12 col-lg-6" aria-label="Giới thiệu cửa hàng">
                    <div class="register-hero h-100">
                        <a href="{{ route('store.home') }}" class="register-hero__brand">
                            <i class="bi bi-shop-window" aria-hidden="true"></i>
                            <span>Website Bán hàng Online</span>
                        </a>

                        <div class="register-hero__content">
                            <div class="register-hero__icon" aria-hidden="true">
                                <i class="bi bi-bag-heart"></i>
                            </div>

                            <h1 class="register-hero__title">Mua sắm thả ga</h1>
                            <p class="register-hero__description">
                                Hàng ngàn sản phẩm chất lượng, giá tốt mỗi ngày.
                                Tham gia Website Bán hàng Online ngay hôm nay.
                            </p>
                        </div>

                        <div class="register-hero__footer">
                            Tạo tài khoản để bắt đầu trải nghiệm mua sắm.
                        </div>
                    </div>
                </section>

                {{-- Form đăng ký --}}
                <section class="col-12 col-md-9 col-lg-6 d-flex align-items-center"
                         aria-labelledby="register-title">
                    <div class="register-form-card">
                        <div class="register-form-card__body">
                            <h2 id="register-title" class="register-form-card__title">
                                Đăng ký
                            </h2>
                            <p class="register-form-card__subtitle">
                                Nhập email để bắt đầu tạo tài khoản.
                            </p>

                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST"
                                  action="{{ route('register.submit') }}"
                                  class="register-form">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label">Email</label>

                                    <div class="input-group register-input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope" aria-hidden="true"></i>
                                        </span>
                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}"
                                            placeholder="Nhập email"
                                            autocomplete="email"
                                            required
                                            autofocus
                                        >
                                    </div>

                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn register-submit w-100">
                                    Tiếp theo
                                    <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                                </button>
                            </form>

                            <p class="text-center text-secondary mt-4 mb-0">
                                Bạn đã có tài khoản?
                                <a href="{{ route('login.form') }}" class="register-login-link">
                                    Đăng nhập
                                </a>
                            </p>

                            <p class="register-policy-links text-center mt-3 mb-0">
                                Bằng việc đăng ký, bạn đồng ý với
                                <a href="{{ route('page.terms') }}">Điều khoản dịch vụ</a>
                                và
                                <a href="{{ route('page.privacy') }}">Chính sách bảo mật</a>.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
