<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo mật khẩu - SHOP ONLINE</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .toggle-password {
            cursor: pointer;
            user-select: none;
            color: #6c757d;
        }
        .toggle-password:hover {
            color: #003B73;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                @include('auth.partials.step-indicator', ['current' => 2])

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-4 text-center" style="color: #003B73;">Hoàn tất thông tin</h4>

                        <form method="POST" action="{{ route('register.password.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Họ và tên</label>
                                <input type="text" name="full_name"
                                       class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                                       value="{{ old('full_name') }}" placeholder="Nhập họ và tên">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Mật khẩu --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           placeholder="Nhập mật khẩu">
                                    <span class="input-group-text bg-white toggle-password" data-target="password">
                                        <i class="bi bi-eye" id="icon-password"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Tối thiểu 6 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt</div>
                            </div>

                            {{-- Nhập lại mật khẩu --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Nhập lại mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control form-control-lg" placeholder="Nhập lại mật khẩu">
                                    <span class="input-group-text bg-white toggle-password" data-target="password_confirmation">
                                        <i class="bi bi-eye" id="icon-password_confirmation"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 btn-lg text-white fw-semibold"
                                    style="background-color: #003B73;">
                                HOÀN TẤT
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Bấm vào icon con mắt -> đổi ô input giữa 2 kiểu: password (ẩn) và text (hiện)
        document.querySelectorAll('.toggle-password').forEach(function (span) {
            span.addEventListener('click', function () {
                const targetId = span.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = document.getElementById('icon-' + targetId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash'); // icon mắt gạch chéo, báo đang hiện mật khẩu
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye'); // icon mắt mở, báo đang ẩn mật khẩu
                }
            });
        });
    </script>

</body>
</html>
