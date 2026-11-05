<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Website Bán hàng Online')</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/store/css/app.css') }}?v=store-fixed-20261029">

    @stack('styles')

    <style>
        :root {
            color-scheme: light;
            --store-bg: #f3f7fc;
            --store-white: #ffffff;
            --store-text: #14243d;
            --store-muted: #64748b;
            --store-border: #dde6f1;
            --store-blue: #1688f8;
            --store-navy: #112941;
            --store-hover: #e9f4ff;
        }

        html,
        body {
            background: var(--store-bg) !important;
            color: var(--store-text);
            font-family: Roboto, Arial, sans-serif;
        }

        .store-main {
            min-height: 65vh;
            background: var(--store-bg);
        }

        /* NAVY HEADER */

        .shop-header.store-topbar {
            position: relative;
            z-index: 100;
            background: linear-gradient(110deg,
                    #112941 0%,
                    #173b59 55%,
                    #1d4966 100%) !important;
            color: #ffffff !important;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 4px 18px rgba(10, 30, 50, .12);
        }

        .store-header-row {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 18px 0;
        }

        /* BRAND */

        .store-brand {
            display: flex;
            flex: 0 0 285px;
            align-items: center;
            gap: 12px;
            min-width: 0;
            color: #ffffff !important;
            text-decoration: none !important;
        }

        .store-brand-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #1688f8;
            color: #ffffff;
            font-size: 25px;
        }

        .store-brand-title {
            display: block;
            color: #ffffff !important;
            font-size: 19px;
            font-weight: 800;
            line-height: 1.3;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* SEARCH */

        .store-header-search {
            display: flex;
            flex: 1 1 340px;
            min-width: 0;
            height: 48px;
            overflow: hidden;
            border: 1px solid #35506a;
            border-radius: 9px;
            background: #101f33;
        }

        .store-header-search:focus-within {
            border-color: #52aaff;
            box-shadow: 0 0 0 3px rgba(22, 136, 248, .15);
        }

        .store-header-search input {
            width: 100%;
            min-width: 0;
            flex: 1;
            padding: 0 16px;
            outline: none;
            border: none;
            background: transparent;
            color: #ffffff;
            font-size: 15px;
        }

        .store-header-search input::placeholder {
            color: #a5b9cd;
            opacity: 1;
        }

        .store-header-search button {
            width: 56px;
            flex-shrink: 0;
            border: 0;
            border-left: 1px solid #35506a;
            background: #233f60;
            color: #59adff;
            font-size: 20px;
            cursor: pointer;
            transition: background .2s, color .2s;
        }

        .store-header-search button:hover {
            background: #1688f8;
            color: #ffffff;
        }

        /* HEADER ACTIONS */

        .store-header-actions {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .store-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 0 16px;
            border: 1px solid #35506a;
            border-radius: 9px;
            background: #14283f;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none !important;
            white-space: nowrap;
            cursor: pointer;
            transition:
                background .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }

        .store-action i {
            color: #59adff;
            font-size: 18px;
        }

        .store-action:hover {
            background: #244563;
            border-color: #59adff;
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        .store-action:focus-visible {
            outline: 3px solid #59adff;
            outline-offset: 2px;
        }

        /* CATEGORY NAVIGATION */

        .store-header-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0 14px;
            border-top: 1px solid rgba(255, 255, 255, .13);
            overflow-x: auto;
        }

        .store-nav-item {
            display: inline-flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 8px;
            color: #d7e5f5 !important;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none !important;
            white-space: nowrap;
            transition: background .2s, color .2s;
        }

        .store-nav-item:hover,
        .store-nav-item.active {
            background: rgba(82, 170, 255, .18);
            color: #ffffff !important;
        }

        /* ADD TO CART */

        .store-main .store-cart-fix {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100%;
            min-height: 44px;
            padding: 10px 16px !important;
            border: 1px solid #1688f8 !important;
            border-radius: 8px !important;
            background: #eaf4ff !important;
            background-image: none !important;
            box-shadow: none !important;
            color: #0875d6 !important;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
            text-decoration: none !important;
            appearance: none;
            outline: none;
            cursor: pointer;
            transition:
                background .2s ease,
                color .2s ease,
                border-color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .store-main .store-cart-fix:hover {
            background: #1688f8 !important;
            border-color: #1688f8 !important;
            color: #ffffff !important;
            box-shadow: 0 5px 14px rgba(22, 136, 248, .18) !important;
            transform: translateY(-2px);
        }

        .store-main .store-cart-fix:focus-visible {
            outline: 3px solid rgba(22, 136, 248, .4);
            outline-offset: 3px;
        }

        .store-main .store-cart-fix:disabled {
            background: #f1f5f9 !important;
            border-color: #d8e2ec !important;
            color: #94a3b8 !important;
            box-shadow: none !important;
            transform: none;
            cursor: not-allowed;
        }

        /* PRODUCT CARD HOVER */

        .store-main .product-card {
            transition:
                transform .2s ease,
                box-shadow .2s ease,
                border-color .2s ease;
        }

        .store-main .product-card:hover {
            transform: translateY(-3px);
            border-color: #b7dbff;
            box-shadow: 0 8px 22px rgba(18, 58, 105, .09);
        }

        /* RESPONSIVE */

        @media (max-width: 1199px) {
            .store-header-row {
                flex-wrap: wrap;
                gap: 14px;
            }

            .store-brand {
                flex: 1 1 260px;
            }

            .store-header-search {
                flex: 1 1 100%;
                order: 3;
            }
        }

        @media (max-width: 575px) {
            .store-brand-title {
                font-size: 15px;
            }

            .store-brand-icon {
                width: 40px;
                height: 40px;
            }

            .store-header-actions {
                gap: 6px;
            }

            .store-action {
                min-height: 40px;
                padding: 0 10px;
            }

            .store-action span {
                display: none;
            }

            .store-header-search {
                height: 44px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .store-main .store-cart-fix,
            .store-main .product-card,
            .store-action {
                transition: none;
            }
        }
    </style>
</head>

<body>

    @include('store.layouts.header')

    <main class="store-main">
        @yield('content')
    </main>

    @include('store.layouts.footer')

    @if (session('account_role') === 'user')
        @include('store.chat.widget')
    @endif

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    @if (session('account_role') === 'user')
        <script src="{{ asset('assets/store/chat/chat.js') }}" defer></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll(
                '.store-main button, .store-main a.btn, .store-main input[type="submit"]'
            );

            buttons.forEach(function(button) {
                const label = (
                    button.value ||
                    button.textContent ||
                    ''
                ).trim().toLowerCase().replace(/\s+/g, ' ');

                if (label.includes('thêm vào giỏ')) {
                    button.classList.add('store-cart-fix');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chỉ gắn bộ đếm cho thông báo đã hiện khi tải trang.
            // Feedback giỏ hàng được bật sau đó bởi AJAX và tự quản lý thời gian riêng.
            document.querySelectorAll(
                '.store-main .alert:not(.d-none):not(.buyer-order-cancellation-alert)'
            ).forEach(function(alert) {
                window.setTimeout(function() {
                    if (!alert.isConnected) {
                        return;
                    }

                    if (window.bootstrap && window.bootstrap.Alert) {
                        window.bootstrap.Alert.getOrCreateInstance(alert).close();
                    } else {
                        alert.remove();
                    }
                }, 3000);
            });
        });
    </script>
    <script
        src="{{ asset('assets/store/cart/add-to-cart.js') }}?v={{ filemtime(public_path('assets/store/cart/add-to-cart.js')) }}"
        defer></script>
    <script
        src="{{ asset('assets/store/products/auto-refresh.js') }}?v={{ filemtime(public_path('assets/store/products/auto-refresh.js')) }}"
        defer></script>
    @stack('scripts')
    <script src="{{ asset('assets/store/search/live-search.js') }}" defer></script>
</body>

</html>
