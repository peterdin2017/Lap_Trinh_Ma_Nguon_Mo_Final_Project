<footer class="shop-footer mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        <i class="bi bi-shop me-1" aria-hidden="true"></i>
                        Thông tin cửa hàng
                    </h2>

                    <p class="small text-secondary mb-2">
                        Website Bán hàng Online
                    </p>
                    <p class="small text-secondary mb-2">
                        <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                        Hotline: 1900 0000
                    </p>
                    <p class="small text-secondary mb-0 text-break">
                        <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                        support@example.com
                    </p>
                </div>
            </section>

            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        Chính sách
                    </h2>

                    <ul class="list-unstyled small d-grid gap-2 mb-0">
                        <li class="text-secondary">Chính sách giao hàng</li>
                        <li class="text-secondary">Chính sách đổi trả</li>
                        <li>
                            <a href="{{ route('page.privacy') }}">
                                Chính sách bảo mật
                            </a>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        Chăm sóc khách hàng
                    </h2>

                    <ul class="list-unstyled small d-grid gap-2 mb-0">
                        <li class="text-secondary">Hướng dẫn mua hàng</li>
                        <li class="text-secondary">Câu hỏi thường gặp</li>
                        <li class="text-secondary">Liên hệ hỗ trợ</li>
                    </ul>
                </div>
            </section>

            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        Nhận tin khuyến mãi
                    </h2>

                    <p class="small text-secondary mb-0">
                        Tính năng đăng ký nhận tin hiện chưa được bật.
                    </p>
                </div>
            </section>

            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        Thông tin đề tài
                    </h2>

                    <div class="shop-footer__project-title mb-3">
                        <i class="bi bi-laptop" aria-hidden="true"></i>
                        <span>Đồ án Lập trình Mã Nguồn Mở</span>
                        <span class="shop-footer__group-badge">Nhóm 6</span>
                    </div>

                    <ul class="shop-footer__members list-unstyled mb-0">
                        <li class="shop-footer__member"
                            title="Đinh Nguyễn Hoàng Phúc (2001240370)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">
                                Đinh Nguyễn Hoàng Phúc
                            </span>
                            <span class="shop-footer__student-id">(2001240370)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Đỗ Thị Hoài Phương (2001240381)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">
                                Đỗ Thị Hoài Phương
                            </span>
                            <span class="shop-footer__student-id">(2001240381)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Hồ Tiểu Điệp (2044240064)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">
                                Hồ Tiểu Điệp
                            </span>
                            <span class="shop-footer__student-id">(2044240064)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Nguyễn Huỳnh Bảo Trân (2001240509)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">
                                Nguyễn Huỳnh Bảo Trân
                            </span>
                            <span class="shop-footer__student-id">(2001240509)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Lê Đình Tấn (2044240304)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">
                                Lê Đình Tấn
                            </span>
                            <span class="shop-footer__student-id">(2044240304)</span>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

        <hr class="shop-footer__divider my-4">

        <p class="small text-center text-secondary mb-0">
            © {{ date('Y') }} Website Bán hàng Online. Đã đăng ký bản quyền.
        </p>
    </div>
</footer>
