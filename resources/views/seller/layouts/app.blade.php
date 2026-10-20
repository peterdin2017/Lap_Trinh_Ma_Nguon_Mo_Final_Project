<!doctype html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Seller Center')</title>

    {{-- Khôi phục theme đã lưu trước khi render --}}
    <script>
        (() => {
            try {
                document.documentElement.dataset.theme =
                    localStorage.getItem('seller-theme') === 'dark' ? 'dark' : 'light';
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
            }
        })();
    </script>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root,
        [data-theme="light"] {
            color-scheme: light;

            --seller-primary: #1A94FF;
            --seller-primary-hover: #0077E6;
            --seller-primary-light: #EBF5FF;
            --seller-page-bg: #F4F7FA;
            --seller-card-bg: #FFFFFF;
            --seller-sidebar-bg: #FFFFFF;
            --seller-hover-bg: #F8FAFC;
            --seller-text: #0F172A;
            --seller-text-muted: #64748B;
            --seller-border: #E2E8F0;
            --seller-price: #EE4D2D;
            --seller-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            --seller-sidebar-width: 260px;
        }

        [data-theme="dark"] {
            color-scheme: dark;

            --seller-primary: #60A5FA;
            --seller-primary-hover: #3B82F6;
            --seller-primary-light: #1E293B;
            --seller-page-bg: #0B0F19;
            --seller-card-bg: #0F172A;
            --seller-sidebar-bg: #0F172A;
            --seller-hover-bg: #1E293B;
            --seller-text: #F1F5F9;
            --seller-text-muted: #94A3B8;
            --seller-border: #1E293B;
            --seller-price: #FB7185;
            --seller-shadow: 0 2px 8px rgba(0, 0, 0, .2);
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            color: var(--seller-text);
            background: var(--seller-page-bg);
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
            transition: color .18s ease, background-color .18s ease;
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--seller-primary);
            --bs-btn-border-color: var(--seller-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--seller-primary-hover);
            --bs-btn-hover-border-color: var(--seller-primary-hover);
            --bs-btn-active-bg: var(--seller-primary-hover);
            --bs-btn-active-border-color: var(--seller-primary-hover);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--seller-primary);
            --bs-btn-border-color: var(--seller-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--seller-primary);
            --bs-btn-hover-border-color: var(--seller-primary);
        }

        .text-primary,
        .link-primary {
            color: var(--seller-primary) !important;
        }

        .text-secondary,
        .form-text {
            color: var(--seller-text-muted) !important;
        }

        .seller-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            width: var(--seller-sidebar-width);
            height: 100vh;
            overflow-y: auto;
            color: var(--seller-text);
            background: var(--seller-sidebar-bg);
            border-right: 1px solid var(--seller-border);
        }

        .seller-main {
            min-width: 0;
            width: 100%;
        }

        .seller-content {
            min-height: calc(100vh - 70px - 58px);
        }

        .seller-brand {
            min-height: 70px;
            border-color: var(--seller-border) !important;
        }

        .seller-nav-link {
            position: relative;
            color: #475569;
            border-radius: .625rem;
            transition: color .18s ease, background-color .18s ease;
        }

        [data-theme="dark"] .seller-nav-link {
            color: var(--seller-text-muted);
        }

        .seller-nav-link:hover {
            color: var(--seller-primary);
            background: var(--seller-hover-bg);
        }

        .seller-nav-link.active {
            color: var(--seller-primary) !important;
            background: var(--seller-primary-light) !important;
            font-weight: 600;
        }

        .seller-nav-link.active::before {
            position: absolute;
            inset: .35rem auto .35rem 0;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--seller-primary);
            content: "";
        }

        .seller-nav-link.disabled {
            color: var(--seller-text-muted);
            opacity: .65;
            cursor: default;
        }

        .seller-topbar {
            min-height: 70px;
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border-bottom: 1px solid var(--seller-border);
        }

        .seller-mobile-menu {
            color: var(--seller-text);
            background: var(--seller-sidebar-bg);
        }

        .seller-content .card,
        .seller-content .dashboard-card,
        .seller-content .welcome-card,
        .seller-content .products-table-card {
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border: 1px solid var(--seller-border);
            border-radius: 12px;
            box-shadow: var(--seller-shadow);
        }

        .seller-content .dropdown-menu {
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border-color: var(--seller-border);
            box-shadow: var(--seller-shadow);
        }

        .seller-content .dropdown-item {
            color: var(--seller-text);
        }

        .seller-content .dropdown-item:hover {
            color: var(--seller-primary);
            background: var(--seller-primary-light);
        }

        .seller-footer {
            color: var(--seller-text-muted);
            background: var(--seller-card-bg);
            border-top: 1px solid var(--seller-border);
        }

        @media (min-width: 992px) {
            .seller-main {
                width: calc(100% - var(--seller-sidebar-width));
                margin-left: var(--seller-sidebar-width);
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex min-vh-100">
        @include('seller.layouts.sidebar')

        <div class="seller-main flex-grow-1">
            @include('seller.layouts.header')

            <main class="seller-content container-fluid px-3 px-lg-4 py-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="status">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('seller.layouts.footer')
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        (() => {
            const root = document.documentElement;
            const selector = '#sellerThemeToggle, #sellerThemeToggleMobile';

            const updateThemeButtons = () => {
                const dark = root.dataset.theme === 'dark';

                document.querySelectorAll(selector).forEach((button) => {
                    const icon = button.querySelector('[data-theme-icon]');
                    const label = button.querySelector('[data-theme-label]');

                    if (icon) {
                        icon.className = `bi ${dark ? 'bi-sun' : 'bi-moon-stars'}`;
                    }

                    if (label) {
                        label.textContent = dark ? 'Giao diện sáng' : 'Giao diện tối';
                    }
                });
            };

            document.addEventListener('DOMContentLoaded', () => {
                updateThemeButtons();

                document.querySelectorAll(selector).forEach((button) => {
                    button.addEventListener('click', () => {
                        const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
                        root.dataset.theme = next;

                        try {
                            localStorage.setItem('seller-theme', next);
                        } catch (error) {
                            // Vẫn đổi theme cho phiên hiện tại nếu localStorage không khả dụng.
                        }

                        updateThemeButtons();
                    });
                });

                document.querySelectorAll('.alert.alert-dismissible').forEach((alert) => {
                    window.setTimeout(() => {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }, 4500);
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
