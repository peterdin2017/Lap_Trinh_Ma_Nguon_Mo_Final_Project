{{-- footer layout chung --}}
<footer class="text-white mt-5" style="background: linear-gradient(135deg, #003B73 0%, #002347 100%);">

    {{-- Nội dung footer --}}
    <div class="container py-5">
        <div class="row">

            {{-- Về chúng tôi --}}
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold mb-3" style="letter-spacing: 1px;">
                    <i class="bi bi-shop me-2"></i>Website Bán hàng Online
                </h5>

                <p class="mb-3" style="color: #D6E9FF; line-height: 1.7;">
                    Website bán hàng online được xây dựng
                    nhằm cung cấp trải nghiệm mua sắm
                    thuận tiện cho khách hàng.
                </p>

            </div>


            {{-- Thông tin --}}
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold mb-3" style="letter-spacing: 1px;">THÔNG TIN</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Giới thiệu
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Điều khoản sử dụng
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Chính sách bảo mật
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Chính sách mua hàng
                        </a>
                    </li>
                </ul>
            </div>


            {{-- Hỗ trợ --}}
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold mb-3" style="letter-spacing: 1px;">HỖ TRỢ</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Trung tâm hỗ trợ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Hướng dẫn mua hàng
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Phương thức thanh toán
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: #D6E9FF;">
                            <i class="bi bi-chevron-right me-1" style="font-size: 0.75rem;"></i>Liên hệ
                        </a>
                    </li>
                </ul>
            </div>


            {{-- Thông tin nhóm --}}
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold mb-3" style="letter-spacing: 1px;">THÔNG TIN NHÓM</h5>

                <div class="p-3 rounded" style="background: rgba(255,255,255,0.07);">
                    <p class="mb-1">
                        <i class="bi bi-people-fill me-2"></i><strong>Nhóm 6</strong>
                    </p>
                    <p class="mb-1" style="color: #D6E9FF; font-size: 0.9rem;">
                        Môn: Lập trình mã nguồn mở
                    </p>
                    <p class="mb-3" style="color: #D6E9FF; font-size: 0.9rem;">
                        Đề tài: Website bán hàng online
                    </p>

                    <p class="fw-bold mb-2" style="font-size: 0.9rem;">Thành viên:</p>

                    <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                        <li class="mb-1" style="color: #D6E9FF;">Đinh Nguyễn Hoàng Phúc - 2001240370</li>
                        <li class="mb-1" style="color: #D6E9FF;">Nguyễn Huỳnh Bảo Trân - 2001240509</li>
                        <li class="mb-1" style="color: #D6E9FF;">Đỗ Thị Hoài Phương - 2001240381</li>
                        <li class="mb-1" style="color: #D6E9FF;">Hồ Tiểu Điệp - 2044240064</li>
                        <li class="mb-1" style="color: #D6E9FF;">Lê Đình Tấn - 2044240304</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>


    {{-- Copyright --}}
    <div style="border-top: 1px solid rgba(255,255,255,0.2);">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small style="color: #B8D8F8;">
                        © {{ date('Y') }} Website Bán hàng Online. All rights reserved.
                    </small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small style="color: #B8D8F8;">
                        Made with <i class="bi bi-heart-fill" style="color: #ff6b6b;"></i> by Nhóm 6 - Lập trình mã
                        nguồn mở
                    </small>
                </div>
            </div>
        </div>
    </div>

</footer>

{{-- CSS cho hiệu ứng hover của link, đặt ở đây cho tiện, hoặc chuyển ra file CSS riêng --}}
<style>
    .footer-link {
        transition: color 0.3s, padding-left 0.3s;
        display: inline-block;
    }

    .footer-link:hover {
        color: #ffffff !important;
        padding-left: 4px;
    }
</style>
