<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - SHOP ONLINE</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .toggle-password { cursor: pointer; user-select: none; color: #6c757d; }
        .toggle-password:hover { color: #003B73; }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-2 text-center" style="color: #003B73;">Đặt lại mật khẩu</h4>
                        <p class="text-center text-muted mb-4">Nhập mật khẩu mới cho tài khoản của bạn.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('forgot.password.reset') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mật khẩu mới</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           placeholder="Nhập mật khẩu mới">
                                    <span class="input-group-text bg-white toggle-password" data-target="password">
                                        <i class="bi bi-eye" id="icon-password"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Tối thiểu 6 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Nhập lại mật khẩu mới</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control form-control-lg" placeholder="Nhập lại mật khẩu mới">
                                    <span class="input-group-text bg-white toggle-password" data-target="password_confirmation">
                                        <i class="bi bi-eye" id="icon-password_confirmation"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 btn-lg text-white fw-semibold"
                                    style="background-color: #003B73;">
                                ĐẶT LẠI MẬT KHẨU
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (span) {
            span.addEventListener('click', function () {
                const targetId = span.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = document.getElementById('icon-' + targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });
    </script>

</body>
</html>
