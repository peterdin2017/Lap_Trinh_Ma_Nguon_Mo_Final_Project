<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Admin - Website Bán hàng Online</title>

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

        .admin-login-page {
            min-height: 100vh;
        }

        .admin-login-hero {
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

        .admin-login-hero::before,
        .admin-login-hero::after {
            position: absolute;
            content: "";
            pointer-events: none;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 50%;
        }

        .admin-login-hero::before {
            top: -130px;
            right: -100px;
            width: 360px;
            height: 360px;
            background: rgba(255, 255, 255, .08);
            box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
                        0 0 0 70px rgba(255, 255, 255, .025);
        }

        .admin-login-hero::after {
            bottom: -180px;
            left: -130px;
            width: 390px;
            height: 390px;
            background: rgba(255, 255, 255, .06);
            box-shadow: 0 0 0 35px rgba(255, 255, 255, .03),
                        0 0 0 70px rgba(255, 255, 255, .02);
        }

        .admin-login-hero__brand,
        .admin-login-hero__content,
        .admin-login-hero__footer {
            position: relative;
            z-index: 1;
        }

        .admin-login-hero__brand {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            text-decoration: none;
        }

        .admin-login-hero__brand i {
            font-size: 1.8rem;
        }

        .admin-login-hero__content {
            max-width: 520px;
            margin-block: auto;
            padding-block: 3rem;
        }

        .admin-login-hero__icon {
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

        .admin-login-hero__title {
            margin-bottom: .75rem;
            font-size: clamp(2rem, 3vw, 2.5rem);
            font-weight: 700;
            line-height: 1.2;
        }

        .admin-login-hero__description {
            max-width: 440px;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, .85);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .admin-login-hero__features {
            display: grid;
            gap: .8rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .admin-login-hero__features li {
            display: flex;
            align-items: center;
            gap: .65rem;
            color: rgba(255, 255, 255, .96);
        }

        .admin-login-hero__features i {
            color: #fff;
        }

        .admin-login-hero__footer {
            color: rgba(255, 255, 255, .78);
            font-size: .875rem;
        }

        .admin-login-card {
            width: 100%;
            max-width: 500px;
            margin-inline: auto;
            background: #fff;
            border: 1px solid var(--auth-border);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(26, 148, 255, .08);
        }

        .admin-login-card__body {
            padding: clamp(1.5rem, 4vw, 2.75rem);
        }

        .admin-login-card__icon {
            color: var(--auth-primary);
            font-size: 2.25rem;
        }

        .admin-login-card__title {
            color: var(--auth-text);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-login-card__subtitle {
            color: var(--auth-muted);
            font-size: .95rem;
        }

        .admin-login-form .form-label {
            color: var(--auth-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .admin-login-input-group {
            min-height: 48px;
            overflow: hidden;
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .admin-login-input-group:focus-within {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .admin-login-input-group .input-group-text {
            min-width: 48px;
            justify-content: center;
            color: var(--auth-muted);
            background: #fff;
            border: 0;
        }

        .admin-login-input-group .form-control {
            min-height: 46px;
            color: var(--auth-text);
            background: #fff;
            border: 0;
            box-shadow: none;
        }

        .admin-login-input-group .form-control:focus {
            box-shadow: none;
        }

        .admin-login-input-group .form-control::placeholder {
            color: #94A3B8;
        }

        .admin-login-forgot {
            color: var(--auth-primary);
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
        }

        .admin-login-forgot:hover {
            color: var(--auth-primary-hover);
            text-decoration: underline;
        }

        .admin-login-submit {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .admin-login-submit:hover,
        .admin-login-submit:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        .admin-login-back {
            color: var(--auth-muted);
            font-size: .9rem;
            text-decoration: none;
        }

        .admin-login-back:hover {
            color: var(--auth-primary);
        }

        @media (max-width: 991.98px) {
            .admin-login-page {
                padding-block: 1.5rem;
            }

            .admin-login-hero {
                min-height: 350px;
                padding: 1.75rem;
            }

            .admin-login-hero__content {
                padding-block: 2rem;
            }

            .admin-login-hero__icon {
                width: 68px;
                height: 68px;
                margin-bottom: 1rem;
                font-size: 2rem;
                border-radius: 20px;
            }

            .admin-login-hero__title {
                font-size: 1.75rem;
            }

            .admin-login-hero__description {
                margin-bottom: 1rem;
                font-size: .95rem;
            }

            .admin-login-hero__features {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: .5rem;
                font-size: .8rem;
            }
        }

        @media (max-width: 575.98px) {
            .admin-login-page {
                padding: .75rem;
            }

            .admin-login-hero {
                min-height: 330px;
                border-radius: 12px;
            }

            .admin-login-hero__features {
                grid-template-columns: 1fr;
            }

            .admin-login-card {
                border-radius: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-login-input-group,
            .admin-login-submit {
                transition: none;
            }
        }
    </style>
</head>

<body>
    <main class="container-fluid admin-login-page d-flex align-items-center py-lg-4">
        <div class="container">
            <div class="row align-items-stretch justify-content-center g-4 g-xl-5">

                {{-- Hero quản trị --}}
                <section class="col-12 col-lg-6" aria-label="Khu vực quản trị">
                    <div class="admin-login-hero h-100">
                        <a href="{{ url('/') }}" class="admin-login-hero__brand">
                            <i class="bi bi-shop-window" aria-hidden="true"></i>
                            <span>Website Bán hàng Online</span>
                        </a>

                        <div class="admin-login-hero__content">
                            <div class="admin-login-hero__icon" aria-hidden="true">
                                <i class="bi bi-shield-lock"></i>
                            </div>

                            <h1 class="admin-login-hero__title">Khu vực quản trị</h1>

                            <p class="admin-login-hero__description">
                                Đăng nhập để quản lý hoạt động của Website Bán hàng Online.
                            </p>

                            <ul class="admin-login-hero__features">
                                <li>
                                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                    Quản lý người bán
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                    Quản lý sản phẩm
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                    Theo dõi đơn hàng
                                </li>
                            </ul>
                        </div>

                        <div class="admin-login-hero__footer">
                            Cổng quản trị hệ thống
                        </div>
                    </div>
                </section>

                {{-- Form đăng nhập Admin --}}
                <section class="col-12 col-md-9 col-lg-6 d-flex align-items-center"
                         aria-labelledby="admin-login-title">
                    <div class="admin-login-card">
                        <div class="admin-login-card__body">
                            <div class="text-center mb-4">
                                <i class="admin-login-card__icon bi bi-person-lock"
                                   aria-hidden="true"></i>

                                <h2 id="admin-login-title"
                                    class="admin-login-card__title mt-3 mb-2">
                                    Đăng nhập Admin
                                </h2>

                                <p class="admin-login-card__subtitle mb-0">
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

                            <form method="POST"
                                  action="{{ route('admin.login.submit') }}"
                                  class="admin-login-form">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Admin</label>

                                    <div class="input-group admin-login-input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope" aria-hidden="true"></i>
                                        </span>
                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Nhập email Admin"
                                            autocomplete="username"
                                            required
                                            autofocus
                                        >
                                    </div>

                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label>

                                    <div class="input-group admin-login-input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock" aria-hidden="true"></i>
                                        </span>
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Nhập mật khẩu"
                                            autocomplete="current-password"
                                            required
                                        >
                                    </div>

                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end mb-4">
                                    <a href="{{ route('forgot-password.form', ['portal' => 'admin']) }}"
                                       class="admin-login-forgot">
                                        Quên mật khẩu?
                                    </a>
                                </div>

                                <button type="submit" class="btn admin-login-submit btn-lg w-100">
                                    Đăng nhập Admin
                                </button>
                            </form>

                            <div class="text-center mt-4">
                                <a href="{{ url('/') }}" class="admin-login-back">
                                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                                    Quay lại Website
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
