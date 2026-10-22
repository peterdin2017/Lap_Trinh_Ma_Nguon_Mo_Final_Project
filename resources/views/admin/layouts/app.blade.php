<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Center - Website Bán hàng Online')</title>

    {{-- Khôi phục theme đã chọn trước khi trình duyệt hiển thị trang --}}
    <script>
        (() => {
            try {
                const theme = localStorage.getItem('app_theme') === 'dark'
                    ? 'dark'
                    : 'light';

                document.documentElement.setAttribute('data-bs-theme', theme);
            } catch {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            color-scheme: light;

            --app-primary: #1A94FF;
            --app-primary-hover: #0077E6;
            --app-primary-light: #EBF5FF;
            --app-page-bg: #F4F7FA;
            --app-card-bg: #FFFFFF;
            --app-header-bg: linear-gradient(135deg, #1A94FF 0%, #0077E6 100%);
            --app-text: #0F172A;
            --app-text-muted: #64748B;
            --app-border: #E2E8F0;
        }

        [data-bs-theme="dark"] {
            color-scheme: dark;

            --app-primary: #0D6EFD;
            --app-primary-hover: #0B5ED7;
            --app-primary-light: #172A46;
            --app-page-bg: #0F172A;
            --app-card-bg: #1E293B;
            --app-header-bg: linear-gradient(135deg, #0B192C 0%, #1E293B 100%);
            --app-text: #F8FAFC;
            --app-text-muted: #94A3B8;
            --app-border: #334155;
        }

        body {
            min-height: 100vh;
            background: var(--app-page-bg);
            color: var(--app-text);
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
            transition: background-color .2s ease, color .2s ease;
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--app-primary-hover);
            --bs-btn-hover-border-color: var(--app-primary-hover);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--app-primary-hover);
            --bs-btn-active-border-color: var(--app-primary-hover);
            --bs-btn-disabled-bg: var(--app-primary);
            --bs-btn-disabled-border-color: var(--app-primary);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--app-primary);
            --bs-btn-hover-border-color: var(--app-primary);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--app-primary-hover);
            --bs-btn-active-border-color: var(--app-primary-hover);
        }

        .text-primary,
        .link-primary {
            color: var(--app-primary) !important;
        }

        .text-secondary,
        .text-muted {
            color: var(--app-text-muted) !important;
        }

        .form-check-input:checked {
            border-color: var(--app-primary);
            background-color: var(--app-primary);
        }

        .page-item.active .page-link {
            color: #fff;
            border-color: var(--app-primary);
            background-color: var(--app-primary);
        }

        .page-link {
            color: var(--app-primary);
        }

        .card,
        .admin-card {
            color: var(--app-text);
            border-color: var(--app-border);
            background-color: var(--app-card-bg);
        }

        .form-control,
        .form-select {
            color: var(--app-text);
            border-color: var(--app-border);
            background-color: var(--app-card-bg);
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            color: var(--app-text);
        }

        [data-bs-theme="dark"] .form-control::placeholder {
            color: var(--app-text-muted);
        }

        [data-bs-theme="dark"] .table {
            --bs-table-color: var(--app-text);
            --bs-table-bg: var(--app-card-bg);
            --bs-table-border-color: var(--app-border);
            --bs-table-striped-bg: #172338;
            --bs-table-striped-color: var(--app-text);
            --bs-table-hover-bg: #25344A;
            --bs-table-hover-color: var(--app-text);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex min-vh-100">
        @include('admin.layouts.sidebar')

        <div class="flex-grow-1 min-vh-100">
            @include('admin.layouts.header')

            <main class="container-fluid px-3 px-lg-4 py-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="status">
                        {{ session('success') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('admin.layouts.footer')
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Bật/tắt theme và lưu lựa chọn --}}
    <script>
        (() => {
            const root = document.documentElement;

            function applyTheme(theme) {
                root.setAttribute('data-bs-theme', theme);

                try {
                    localStorage.setItem('app_theme', theme);
                } catch {
                    // Vẫn đổi giao diện nếu localStorage không khả dụng.
                }

                document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                    const isDark = theme === 'dark';
                    button.setAttribute('aria-pressed', String(isDark));

                    const icon = button.querySelector('[data-theme-icon]');
                    if (icon) {
                        icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
                    }

                    const label = button.querySelector('[data-theme-label]');
                    if (label) {
                        label.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
                    }
                });
            }

            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-theme-toggle]');
                if (!button) return;

                const currentTheme = root.getAttribute('data-bs-theme');
                applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
            });

            applyTheme(root.getAttribute('data-bs-theme') || 'light');
        })();
    </script>

    @stack('scripts')
</body>
</html>
