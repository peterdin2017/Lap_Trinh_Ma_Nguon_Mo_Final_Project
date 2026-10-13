<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body style="background-color: #003B73;">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="row justify-content-center w-100">
            {{-- Độ rộng khai báo 1 lần ở đây => mọi trang bằng nhau --}}
            <div class="col-12 col-sm-10 col-md-8 col-lg-7 col-xl-6">
                <section class="card border-0 rounded-4 shadow-lg">
                    <div class="card-body p-4 p-md-5">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                                @yield('step')
                            </span>
                            <span class="small text-secondary">
                                @yield('step_label')
                            </span>
                        </div>

                        <div
                            class="progress mb-4"
                            role="progressbar"
                            aria-valuenow="@yield('step_number')"
                            aria-valuemin="1"
                            aria-valuemax="@yield('step_total')"
                        >
                            <div class="progress-bar" style="width: @yield('step_percent');"></div>
                        </div>

                        @yield('content')

                    </div>
                </section>
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
