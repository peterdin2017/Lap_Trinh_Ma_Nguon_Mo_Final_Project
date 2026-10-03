<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điều khoản dịch vụ - Website Bán hàng Online</title>
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

                        <h2 class="fw-bold mb-4" style="color: #003B73;">Điều khoản dịch vụ</h2>

                        <h5 class="fw-bold mt-4">1. Chấp nhận điều khoản</h5>
                        <p>Khi tạo tài khoản và sử dụng Website Bán hàng Online, bạn đồng ý tuân thủ các điều khoản dưới
                            đây.</p>

                        <h5 class="fw-bold mt-4">2. Tài khoản người dùng</h5>
                        <p>Bạn cung cấp thông tin chính xác khi đăng ký và tự chịu trách nhiệm bảo mật mật khẩu cũng như
                            mã OTP của mình.</p>

                        <h5 class="fw-bold mt-4">3. Mua bán hàng hóa</h5>
                        <p>Người bán chịu trách nhiệm về thông tin sản phẩm đăng bán. Người mua cung cấp đúng địa chỉ
                            nhận hàng và thanh toán theo phương thức đã chọn.</p>

                        <h5 class="fw-bold mt-4">4. Hành vi bị cấm</h5>
                        <p>Nghiêm cấm đăng bán hàng hóa vi phạm pháp luật, gian lận, hoặc gây ảnh hưởng đến hoạt động
                            của hệ thống.</p>

                        <h5 class="fw-bold mt-4">5. Thay đổi điều khoản</h5>
                        <p>Chúng tôi có thể cập nhật điều khoản và sẽ thông báo trên website khi có thay đổi.</p>

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
