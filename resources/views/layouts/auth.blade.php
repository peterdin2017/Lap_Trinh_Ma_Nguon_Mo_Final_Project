<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SHOP ONLINE</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #003B73 0%, #002347 100%);
            min-height: 100vh;
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="container pt-4">
        <a href="{{ url('/') }}" class="text-white text-decoration-none d-flex align-items-center"
            style="width: fit-content;">
            <i class="bi bi-shop me-2" style="font-size: 1.8rem;"></i>
            <span class="fw-bold" style="font-size: 1.3rem;">SHOP ONLINE</span>
        </a>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
