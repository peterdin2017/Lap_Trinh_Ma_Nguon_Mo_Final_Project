<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #003B73 0%, #002347 100%);
            min-height: 100vh;
        }
        .social-btn {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .social-btn:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>

    {{-- Header đơn giản, có logo --}}
    <div class="container pt-4">
        <a href="{{ url('/') }}" class="text-white text-decoration-none d-flex align-items-center" style="width: fit-content;">
            <i class="bi bi-shop me-2" style="font-size: 1.8rem;"></i>
            <span class="fw-bold" style="font-size: 1.3rem;">Website Bán hàng Online</span>
        </a>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center align-items-center" style="min-height: 75vh;">

            {{-- Cột trái: banner giới thiệu --}}
            <div class="col-lg-6 d-none d-lg-block text-white">
                <div class="px-4">
                    <i class="bi bi-bag-heart" style="font-size: 4rem;"></i>
                    <h1 class="fw-bold mt-3">Mua sắm thả ga</h1>
                    <p style="color: #D6E9FF; font-size: 1.1rem;">
                        Hàng ngàn sản phẩm chất lượng, giá tốt mỗi ngày.<br>
                        Tham gia Website Bán hàng Online ngay hôm nay.
                    </p>
                </div>
            </div>

            {{-- Cột phải: card đăng ký --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-4" style="color: #003B73;">Đăng ký</h4>

                        {{--  --}}
                        <form method="POST" action="{{ route('register.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="Nhập email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nút tiếp theo --}}
                            <button type="submit" class="btn w-100 btn-lg text-white fw-semibold"
                                    style="background-color: #003B73;">
                                TIẾP THEO
                            </button>
                        </form>

                        <div class="text-center my-4" style="color: #adb5bd;">
                            <hr class="d-inline-block" style="width: 40%; vertical-align: middle;">
                            <span class="mx-2">HOẶC</span>
                            <hr class="d-inline-block" style="width: 40%; vertical-align: middle;">
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="social-btn">
                                    <i class="bi bi-facebook me-1" style="color: #1877F2;"></i>Facebook
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="social-btn">
                                    <i class="bi bi-google me-1" style="color: #EA4335;"></i>Google
                                </div>
                            </div>
                        </div>

                        <p class="text-center mt-4 mb-2" style="font-size: 0.85rem; color: #6c757d;">
                            Bằng việc đăng ký, bạn đã đồng ý với
                            <a href="#" style="color: #003B73;">Điều khoản dịch vụ</a> &
                            <a href="#" style="color: #003B73;">Chính sách bảo mật</a>
                        </p>

                        <p class="text-center mb-0" style="color: #6c757d;">
                            Bạn đã có tài khoản?
                            <a href="{{ route('login.form') }}" class="fw-semibold text-decoration-none" style="color: #003B73;">
                                Đăng nhập
                            </a>
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
