<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-primary: #1a94ff;
            --bs-primary-rgb: 26, 148, 255;
            --auth-blue: #1a94ff;
            --auth-blue-dark: #0878d1;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--auth-blue) 0%, var(--auth-blue-dark) 100%);
        }

        .progress-bar {
            background-color: var(--auth-blue);
        }

        .text-primary {
            color: var(--auth-blue) !important;
        }

        .btn-primary {
            --bs-btn-bg: var(--auth-blue);
            --bs-btn-border-color: var(--auth-blue);
            --bs-btn-hover-bg: var(--auth-blue-dark);
            --bs-btn-hover-border-color: var(--auth-blue-dark);
        }
    </style>
</head>

<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="row justify-content-center w-100">
            {{-- Giữ cùng chiều rộng cho các bước xác minh --}}
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

                        <div class="progress mb-4"
                             role="progressbar"
                             aria-valuenow="@yield('step_number')"
                             aria-valuemin="1"
                             aria-valuemax="@yield('step_total')">
                            <div class="progress-bar"
                                 style="width: @yield('step_percent');"></div>
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
