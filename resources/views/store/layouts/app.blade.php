<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Website Bán hàng Online')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --shop-primary: #1A94FF;
            --shop-primary-hover: #0077E6;
            --shop-primary-light: #EBF5FF;
            --shop-page-bg: #F4F7FA;
            --shop-card-bg: #FFFFFF;
            --shop-text: #0F172A;
            --shop-text-muted: #64748B;
            --shop-border: #E2E8F0;
            --shop-price: #EE4D2D;
            --shop-shadow: 0 2px 8px rgba(0, 0, 0, .04);

            --bs-primary: #1A94FF;
            --bs-primary-rgb: 26, 148, 255;
            --bs-link-color: #1A94FF;
            --bs-link-hover-color: #0077E6;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
            color: var(--shop-text);
            background: var(--shop-page-bg);
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
        }

        .store-main {
            flex: 1 0 auto;
        }

        .shop-button,
        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--shop-primary);
            --bs-btn-border-color: var(--shop-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--shop-primary-hover);
            --bs-btn-hover-border-color: var(--shop-primary-hover);
            --bs-btn-active-bg: var(--shop-primary-hover);
            --bs-btn-active-border-color: var(--shop-primary-hover);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--shop-primary);
            --bs-btn-border-color: var(--shop-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--shop-primary);
            --bs-btn-hover-border-color: var(--shop-primary);
            --bs-btn-active-bg: var(--shop-primary-hover);
            --bs-btn-active-border-color: var(--shop-primary-hover);
        }

        .text-primary,
        .link-primary {
            color: var(--shop-primary) !important;
        }

        .text-secondary {
            color: var(--shop-text-muted) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--shop-primary);
            box-shadow: 0 0 0 .2rem rgba(26, 148, 255, .16);
        }

        .form-check-input:checked {
            border-color: var(--shop-primary);
            background-color: var(--shop-primary);
        }

        .form-check-input:focus {
            border-color: var(--shop-primary);
            box-shadow: 0 0 0 .2rem rgba(26, 148, 255, .16);
        }

        .page-item.active .page-link {
            color: #fff;
            border-color: var(--shop-primary);
            background-color: var(--shop-primary);
        }

        .page-link {
            color: var(--shop-primary);
        }

        .page-link:hover {
            color: var(--shop-primary-hover);
        }

        .shop-footer {
            flex-shrink: 0;
            color: var(--shop-text);
            background: var(--shop-card-bg);
            border-top: 1px solid var(--shop-border);
        }

        .shop-footer a {
            color: var(--shop-text-muted);
            text-decoration: none;
            transition: color .15s ease;
        }

        .shop-footer a:hover,
        .shop-footer a:focus-visible {
            color: var(--shop-primary);
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('store.layouts.header')

    <main class="store-main">
        @yield('content')
    </main>

    @include('store.layouts.footer')

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
