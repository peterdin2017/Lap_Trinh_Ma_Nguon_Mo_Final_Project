<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tạo mật khẩu - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="progress mb-4" role="progressbar" aria-label="Tiến trình đăng ký">
                    <div class="progress-bar" style="width: 66%"></div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 fw-bold text-center text-primary mb-4">Hoàn tất thông tin</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.password.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên</label>
                                <input
                                    id="full_name"
                                    type="text"
                                    name="full_name"
                                    class="form-control form-control-lg"
                                    value="{{ old('full_name') }}"
                                    placeholder="Nhập họ và tên"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    placeholder="Nhập mật khẩu"
                                    required
                                >
                                <div class="form-text">
                                    Mật khẩu cần có chữ hoa, chữ thường, số và ký tự đặc biệt.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    Nhập lại mật khẩu
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control form-control-lg"
                                    placeholder="Nhập lại mật khẩu"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Hoàn tất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
