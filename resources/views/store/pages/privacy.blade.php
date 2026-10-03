<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chính sách bảo mật - Website Bán hàng Online</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Logo, bấm vào về trang chủ --}}
                <a href="{{ route('store.home') }}" class="text-decoration-none d-flex align-items-center mb-4"
                    style="color: #003B73; width: fit-content;">
                    <i class="bi bi-shop me-2" style="font-size: 1.8rem;"></i>
                    <span class="fw-bold" style="font-size: 1.3rem;">Website Bán hàng Online</span>
                </a>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">

                        <h2 class="fw-bold mb-4" style="color: #003B73;">Chính sách bảo mật</h2>

                        <h5 class="fw-bold mt-4">1. Thông tin chúng tôi thu thập</h5>
                        <p>Email, họ tên, số điện thoại, giới tính, ngày sinh, ảnh đại diện và địa chỉ giao hàng mà bạn
                            cung cấp khi đăng ký và mua hàng.</p>

                        <h5 class="fw-bold mt-4">2. Mục đích sử dụng</h5>
                        <p>Thông tin được dùng để xác thực tài khoản, xử lý đơn hàng và liên hệ với bạn khi cần thiết.
                        </p>

                        <h5 class="fw-bold mt-4">3. Bảo vệ mật khẩu</h5>
                        <p>Mật khẩu được mã hóa trước khi lưu, chúng tôi không lưu mật khẩu dạng văn bản thường.</p>

                        <h5 class="fw-bold mt-4">4. Chia sẻ thông tin</h5>
                        <p>Chúng tôi không bán hay chia sẻ thông tin cá nhân của bạn cho bên thứ ba, trừ khi cần thiết
                            để giao hàng hoặc theo yêu cầu của pháp luật.</p>

                        <h5 class="fw-bold mt-4">5. Quyền của bạn</h5>
                        <p>Bạn có thể xem và cập nhật thông tin cá nhân của mình trong trang "Tài Khoản Của Tôi".</p>

                        <p class="text-muted mt-4"><small>Đây là nội dung mẫu cho đồ án môn Lập trình mã nguồn
                                mở.</small></p>

                        <a href="{{ $backUrl }}" class="btn btn-lg text-white fw-semibold"
                            style="background-color: #003B73;">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
