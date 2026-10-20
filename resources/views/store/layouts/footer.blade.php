<footer class="shop-footer mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">

            {{-- Thông tin cửa hàng --}}
            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        <i class="bi bi-shop me-1" aria-hidden="true"></i>
                        Thông tin cửa hàng
                    </h2>

                    <p class="small text-secondary mb-2">Website Bán hàng Online</p>
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

            {{-- Chính sách --}}
            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">Chính sách</h2>

                    <ul class="list-unstyled small d-grid gap-2 mb-0">
                        <li><a href="#">Chính sách giao hàng</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                    </ul>
                </div>
            </section>

            {{-- Chăm sóc khách hàng --}}
            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        Chăm sóc khách hàng
                    </h2>

                    <ul class="list-unstyled small d-grid gap-2 mb-0">
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                        <li><a href="#">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>
            </section>

            {{-- Đăng ký nhận tin --}}
            <section class="col">
                <div class="shop-footer__column">
                    <h2 class="shop-footer__title h6 fw-bold mb-3">
                        Nhận tin khuyến mãi
                    </h2>

                    <p class="small text-secondary mb-3">
                        Đăng ký email để nhận thông tin ưu đãi mới.
                    </p>

                    <form action="#" method="POST" class="input-group shop-footer__newsletter">
                        @csrf

                        <label for="newsletter-email" class="visually-hidden">Email</label>
                        <input
                            id="newsletter-email"
                            name="email"
                            type="email"
                            class="form-control"
                            placeholder="Email của bạn"
                        >

                        <button
                            class="btn shop-button"
                            type="submit"
                            aria-label="Đăng ký nhận tin"
                        >
                            <i class="bi bi-send" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </section>

            {{-- Thông tin đề tài và thành viên --}}
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
                            <span class="shop-footer__member-name">Đinh Nguyễn Hoàng Phúc</span>
                            <span class="shop-footer__student-id">(2001240370)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Đỗ Thị Hoài Phương (2001240381)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">Đỗ Thị Hoài Phương</span>
                            <span class="shop-footer__student-id">(2001240381)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Hồ Tiểu Điệp (2044240064)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">Hồ Tiểu Điệp</span>
                            <span class="shop-footer__student-id">(2044240064)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Nguyễn Huỳnh Bảo Trân (2001240509)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">Nguyễn Huỳnh Bảo Trân</span>
                            <span class="shop-footer__student-id">(2001240509)</span>
                        </li>

                        <li class="shop-footer__member"
                            title="Lê Đình Tấn (2044240304)">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <span class="shop-footer__member-name">Lê Đình Tấn</span>
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

<style>
    .shop-footer {
        color: var(--shop-text, #0F172A);
        background: var(--shop-card-bg, #FFFFFF);
        border-top: 1px solid var(--shop-border, #E2E8F0);
    }

    .shop-footer__column {
        width: 100%;
        height: 100%;
    }

    .shop-footer__title {
        color: #1E293B;
        line-height: 1.35;
    }

    .shop-footer .text-secondary {
        color: #64748B !important;
    }

    .shop-footer a {
        color: #64748B;
        text-decoration: none;
        transition: color .15s ease;
    }

    .shop-footer a:hover,
    .shop-footer a:focus-visible {
        color: #1A94FF;
    }

    .shop-footer__newsletter {
        overflow: hidden;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
    }

    .shop-footer__newsletter .form-control {
        min-width: 0;
        min-height: 40px;
        border: 0;
        box-shadow: none;
    }

    .shop-footer__newsletter .form-control:focus {
        box-shadow: inset 0 0 0 1px #1A94FF;
    }

    .shop-footer__newsletter .shop-button {
        min-width: 42px;
        color: #fff;
        background: #1A94FF;
        border: 0;
        border-radius: 0;
    }

    .shop-footer__newsletter .shop-button:hover {
        color: #fff;
        background: #0077E6;
    }

    .shop-footer__project-title {
        display: flex;
        align-items: center;
        gap: .35rem;
        color: #64748B;
        font-size: .76rem;
        line-height: 1.35;
    }

    .shop-footer__project-title > i {
        flex: 0 0 auto;
        color: #1A94FF;
        font-size: .9rem;
    }

    .shop-footer__group-badge {
        flex: 0 0 auto;
        padding: .2rem .45rem;
        color: #1A94FF;
        font-size: .68rem;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
        background: #EBF5FF;
        border: 1px solid #D0E4FF;
        border-radius: 999px;
    }

    .shop-footer__members {
        display: grid;
        gap: .45rem;
    }

    .shop-footer__member {
        display: flex;
        min-width: 0;
        align-items: center;
        gap: .3rem;
        padding-left: .45rem;
        overflow: hidden;
        border-left: 2px solid #E2E8F0;
        line-height: 1.25;
        white-space: nowrap;
    }

    .shop-footer__member > i {
        flex: 0 0 auto;
        color: #1A94FF;
        font-size: .72rem;
    }

    .shop-footer__member-name {
        min-width: 0;
        overflow: hidden;
        color: #0F172A;
        font-size: .67rem;
        font-weight: 600;
        text-overflow: ellipsis;
    }

    .shop-footer__student-id {
        flex: 0 0 auto;
        color: #64748B;
        font-size: .62rem;
    }

    .shop-footer__divider {
        border-color: #E2E8F0;
        opacity: 1;
    }

    @media (max-width: 991.98px) {
        .shop-footer__column {
            height: auto;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .shop-footer a {
            transition: none;
        }
    }
</style>
