<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Seller Center')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .seller-sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #003b73;
        }

        .seller-main {
            min-width: 0;
        }

        .seller-content {
            min-height: calc(100vh - 70px - 58px);
        }

        .seller-brand {
            min-height: 70px;
        }

        .seller-nav-link {
            color: rgba(255, 255, 255, .8);
            transition: background-color .2s, color .2s;
        }

        .seller-nav-link:hover,
        .seller-nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .15);
        }

        .seller-nav-link.disabled {
            color: rgba(255, 255, 255, .45);
            cursor: default;
        }

        .seller-topbar {
            height: 70px;
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
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
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

            @include('seller.layouts.footer')
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
