<footer class="site-footer mt-5">
    <div class="container py-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
            {{-- Cột 1: Thông tin cửa hàng --}}
            <section class="col">
                <div class="site-footer__column">
                    <a href="{{ url('/') }}" class="site-footer__brand">
                        <span class="site-footer__brand-icon">
                            <i class="bi bi-shop" aria-hidden="true"></i>
                        </span>
                        <span>Website Bán hàng Online</span>
                    </a>

                    <ul class="site-footer__contact list-unstyled">
                        <li>
                            <i class="bi bi-telephone" aria-hidden="true"></i>
                            <a href="tel:19000000">1900 0000</a>
                        </li>
                        <li>
                            <i class="bi bi-envelope" aria-hidden="true"></i>
                            <a href="mailto:support@example.com">support@example.com</a>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                            <span>TP. Hồ Chí Minh</span>
                        </li>
                    </ul>
                </div>
            </section>

            {{-- Cột 2: Chính sách --}}
            <section class="col">
                <div class="site-footer__column">
                    <h2 class="site-footer__title">Chính sách</h2>

                    <ul class="site-footer__links list-unstyled">
                        <li><a href="#">Chính sách giao hàng</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="{{ route('page.privacy') }}">Chính sách bảo mật</a></li>
                        <li><a href="{{ route('page.terms') }}">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>
            </section>

            {{-- Cột 3: Chăm sóc khách hàng --}}
            <section class="col">
                <div class="site-footer__column">
                    <h2 class="site-footer__title">Chăm sóc khách hàng</h2>

                    <ul class="site-footer__links list-unstyled">
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Câu hỏi thường gặp (FAQ)</a></li>
                        <li><a href="mailto:support@example.com">Liên hệ hỗ trợ</a></li>
                        <li><a href="mailto:support@example.com?subject=Báo%20lỗi%20hệ%20thống">Báo lỗi hệ thống</a></li>
                    </ul>
                </div>
            </section>

            {{-- Cột 4: Nhận tin và kết nối --}}
            <section class="col">
                <div class="site-footer__column">
                    <h2 class="site-footer__title">Nhận tin khuyến mãi</h2>
                    <p class="site-footer__description">
                        Đăng ký để nhận thông tin ưu đãi mới nhất.
                    </p>

                    <form class="site-footer__subscribe" method="POST" action="#">
                        @csrf
                        <label for="footer-email" class="visually-hidden">Email của bạn</label>
                        <div class="input-group">
                            <input
                                id="footer-email"
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Nhập email của bạn..."
                                autocomplete="email"
                                required
                            >
                            <button class="btn btn-primary" type="submit">Gửi</button>
                        </div>
                    </form>

                    <h3 class="site-footer__social-title">Kết nối với chúng tôi</h3>
                    <nav class="site-footer__social" aria-label="Mạng xã hội">
                        <a href="#" aria-label="Facebook" title="Facebook">
                            <i class="bi bi-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="#" aria-label="Zalo" title="Zalo" class="site-footer__zalo">
                            Za
                        </a>
                        <a href="#" aria-label="YouTube" title="YouTube">
                            <i class="bi bi-youtube" aria-hidden="true"></i>
                        </a>
                        <a href="#" aria-label="TikTok" title="TikTok">
                            <i class="bi bi-tiktok" aria-hidden="true"></i>
                        </a>
                    </nav>
                </div>
            </section>

            {{-- Cột 5: Thông tin đề tài --}}
            <section class="col">
                <div class="site-footer__column">
                    <h2 class="site-footer__title">Thông tin đề tài</h2>

                    <div class="site-footer__project">
                        <i class="bi bi-laptop" aria-hidden="true"></i>
                        <span>Đồ án Lập trình Mã Nguồn Mở</span>
                        <span class="site-footer__group">Nhóm 6</span>
                    </div>

                    <ul class="site-footer__members list-unstyled">
                        <li>
                            <span class="site-footer__member-name">Đinh Nguyễn Hoàng Phúc</span>
                            <span class="site-footer__mssv">2001240370</span>
                        </li>
                        <li>
                            <span class="site-footer__member-name">Đỗ Thị Hoài Phương</span>
                            <span class="site-footer__mssv">2001240381</span>
                        </li>
                        <li>
                            <span class="site-footer__member-name">Hồ Tiểu Điệp</span>
                            <span class="site-footer__mssv">2044240064</span>
                        </li>
                        <li>
                            <span class="site-footer__member-name">Nguyễn Huỳnh Bảo Trân</span>
                            <span class="site-footer__mssv">2001240509</span>
                        </li>
                        <li>
                            <span class="site-footer__member-name">Lê Đình Tấn</span>
                            <span class="site-footer__mssv">2044240304</span>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

        <div class="site-footer__bottom">
            <p class="site-footer__copyright">
                © {{ date('Y') }} Website Bán hàng Online. Đồ án môn Lập trình Mã Nguồn Mở — Nhóm 6.
            </p>
            <span class="site-footer__certificate">
                <i class="bi bi-patch-check" aria-hidden="true"></i>
                Dự án học tập
            </span>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        color: #0F172A;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
    }

    .site-footer__column {
        height: 100%;
    }

    .site-footer__brand {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
        color: #0F172A;
        font-size: .95rem;
        font-weight: 700;
        line-height: 1.35;
        text-decoration: none;
    }

    .site-footer__brand-icon {
        display: grid;
        width: 34px;
        height: 34px;
        flex: 0 0 auto;
        place-items: center;
        color: #1A94FF;
        background: #EAF5FF;
        border-radius: 9px;
    }

    .site-footer__title {
        margin: 3px 0 15px;
        color: #0F172A;
        font-size: .9rem;
        font-weight: 700;
    }

    .site-footer__contact,
    .site-footer__links {
        display: grid;
        gap: 11px;
        margin: 0;
        padding: 0;
        color: #64748B;
        font-size: .8rem;
    }

    .site-footer__contact li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .site-footer__contact i {
        flex: 0 0 auto;
        margin-top: 2px;
        color: #1A94FF;
    }

    .site-footer a {
        transition: color .18s ease;
    }

    .site-footer__contact a,
    .site-footer__links a {
        color: #64748B;
        text-decoration: none;
        overflow-wrap: anywhere;
    }

    .site-footer__contact a:hover,
    .site-footer__links a:hover {
        color: #1A94FF;
    }

    .site-footer__description {
        margin: -5px 0 12px;
        color: #64748B;
        font-size: .78rem;
        line-height: 1.5;
    }

    .site-footer__subscribe .form-control {
        min-width: 0;
        border-color: #CBD5E1;
        border-radius: 7px 0 0 7px;
        font-size: .75rem;
    }

    .site-footer__subscribe .btn {
        border-radius: 0 7px 7px 0;
        font-size: .78rem;
        font-weight: 600;
    }

    .site-footer__social-title {
        margin: 17px 0 9px;
        color: #334155;
        font-size: .78rem;
        font-weight: 650;
    }

    .site-footer__social {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .site-footer__social a {
        display: grid;
        width: 34px;
        height: 34px;
        place-items: center;
        color: #475569;
        font-size: 1rem;
        text-decoration: none;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 9px;
    }

    .site-footer__social a:hover {
        color: #1A94FF;
        border-color: #1A94FF;
    }

    .site-footer__social .bi-youtube:hover {
        color: #EF4444;
        border-color: #FECACA;
    }

    .site-footer__zalo {
        font-size: .7rem !important;
        font-weight: 800;
    }

    .site-footer__project {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 7px;
        margin-bottom: 11px;
        color: #334155;
        font-size: .78rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .site-footer__project > i {
        color: #1A94FF;
    }

    .site-footer__group {
        padding: 3px 7px;
        color: #0878D9;
        font-size: .68rem;
        font-weight: 700;
        background: #EAF5FF;
        border-radius: 20px;
    }

    .site-footer__members {
        display: grid;
        gap: 7px;
        margin: 0;
        padding: 0;
    }

    .site-footer__members li {
        display: flex;
        min-width: 0;
        flex-wrap: wrap;
        align-items: center;
        gap: 5px 7px;
        color: #475569;
        font-size: .72rem;
        line-height: 1.35;
    }

    .site-footer__member-name {
        overflow-wrap: anywhere;
    }

    .site-footer__mssv {
        padding: 2px 6px;
        color: #64748B;
        font-size: .64rem;
        font-variant-numeric: tabular-nums;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 5px;
        white-space: nowrap;
    }

    .site-footer__bottom {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 30px;
        padding-top: 16px;
        border-top: 1px solid #E2E8F0;
    }

    .site-footer__copyright {
        margin: 0;
        color: #64748B;
        font-size: .73rem;
    }

    .site-footer__certificate {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #64748B;
        font-size: .7rem;
    }

    .site-footer__certificate i {
        color: #1A94FF;
    }

    @media (max-width: 767.98px) {
        .site-footer__bottom {
            justify-content: center;
            text-align: center;
        }
    }
</style>
