{{-- Sidebar desktop --}}
<aside class="seller-sidebar d-none d-lg-flex flex-column flex-shrink-0">
    <a href="{{ route('seller.home') }}"
       class="seller-brand d-flex align-items-center gap-3 px-4 border-bottom text-decoration-none">
        <i class="bi bi-shop-window fs-4 text-primary" aria-hidden="true"></i>
        <span>
            <strong class="d-block fs-5 text-body">Seller Center</strong>
            <small class="text-secondary">Quản lý cửa hàng</small>
        </span>
    </a>

    <div class="px-4 pt-4 pb-2 small text-uppercase text-secondary fw-semibold">
        Quản lý cửa hàng
    </div>

    <nav class="nav flex-column px-3 gap-1" aria-label="Menu người bán">
        <a href="{{ route('seller.home') }}"
           class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3
                  {{ request()->routeIs('seller.home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 fs-5" aria-hidden="true"></i>
            <span>Tổng quan</span>
        </a>

        {{-- Nút cha mở submenu, mục con đánh dấu active theo route --}}
        <button class="seller-nav-link nav-link w-100 d-flex align-items-center gap-3 rounded-3 py-3 px-3 border-0 bg-transparent"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sellerProductMenu"
                aria-expanded="{{ request()->routeIs('seller.products.*') ? 'true' : 'false' }}"
                aria-controls="sellerProductMenu">
            <i class="bi bi-box-seam fs-5" aria-hidden="true"></i>
            <span class="flex-grow-1 text-start">Sản phẩm</span>
            <i class="bi bi-chevron-down small" aria-hidden="true"></i>
        </button>

        <div class="collapse {{ request()->routeIs('seller.products.*') ? 'show' : '' }}"
             id="sellerProductMenu">
            <nav class="nav flex-column ms-4 ps-3 border-start"
                 style="border-color: var(--seller-border) !important;"
                 aria-label="Quản lý sản phẩm">
                <a href="{{ route('seller.products.index') }}"
                   class="seller-nav-link nav-link py-2 ps-3 rounded-2
                          {{ request()->routeIs('seller.products.index') ? 'active' : '' }}">
                    Danh sách sản phẩm
                </a>

                <a href="{{ route('seller.products.create') }}"
                   class="seller-nav-link nav-link py-2 ps-3 rounded-2
                          {{ request()->routeIs('seller.products.create') ? 'active' : '' }}">
                    Thêm sản phẩm
                </a>
            </nav>
        </div>

        <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3"
              aria-disabled="true">
            <i class="bi bi-receipt fs-5" aria-hidden="true"></i>
            <span>Đơn hàng</span>
            <span class="badge bg-light text-secondary ms-auto">Sắp có</span>
        </span>

        <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3"
              aria-disabled="true">
            <i class="bi bi-bar-chart-line fs-5" aria-hidden="true"></i>
            <span>Thống kê</span>
            <span class="badge bg-light text-secondary ms-auto">Sắp có</span>
        </span>

        <a href="{{ route('seller.shop-information.edit') }}"
           class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3
                  {{ request()->routeIs('seller.shop-information.*') ? 'active' : '' }}">
            <i class="bi bi-shop fs-5" aria-hidden="true"></i>
            <span>Thông tin Shop</span>
        </a>
    </nav>

    <div class="mt-auto p-3 border-top"
         style="border-color: var(--seller-border) !important;">
        <button id="sellerThemeToggle"
                type="button"
                class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-2">
            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            <span data-theme-label>Giao diện tối</span>
        </button>

        <form action="{{ route('seller.logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-left" aria-hidden="true"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>

{{-- Menu mobile --}}
<div class="offcanvas offcanvas-start d-lg-none seller-mobile-menu"
     tabindex="-1"
     id="sellerMobileMenu"
     aria-labelledby="sellerMobileMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h2 class="offcanvas-title h5" id="sellerMobileMenuLabel">
            <i class="bi bi-shop-window text-primary me-2" aria-hidden="true"></i>
            Seller Center
        </h2>
        <button type="button" class="btn-close"
                data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="small text-uppercase text-secondary fw-semibold mb-2">
            Quản lý cửa hàng
        </div>

        <nav class="nav flex-column gap-1" aria-label="Menu người bán trên điện thoại">
            <a href="{{ route('seller.home') }}"
               class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3
                      {{ request()->routeIs('seller.home') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2 fs-5" aria-hidden="true"></i>
                <span>Tổng quan</span>
            </a>

            <a href="{{ route('seller.products.index') }}"
               class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3
                      {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam fs-5" aria-hidden="true"></i>
                <span>Sản phẩm</span>
            </a>

            <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3"
                  aria-disabled="true">
                <i class="bi bi-receipt fs-5" aria-hidden="true"></i>
                <span>Đơn hàng</span>
                <span class="badge bg-light text-secondary ms-auto">Sắp có</span>
            </span>

            <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3"
                  aria-disabled="true">
                <i class="bi bi-bar-chart-line fs-5" aria-hidden="true"></i>
                <span>Thống kê</span>
                <span class="badge bg-light text-secondary ms-auto">Sắp có</span>
            </span>

            <a href="{{ route('seller.shop-information.edit') }}"
               class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3 px-3
                      {{ request()->routeIs('seller.shop-information.*') ? 'active' : '' }}">
                <i class="bi bi-shop fs-5" aria-hidden="true"></i>
                <span>Thông tin Shop</span>
            </a>
        </nav>

        <div class="mt-auto pt-3">
            <button id="sellerThemeToggleMobile"
                    type="button"
                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-2">
                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                <span data-theme-label>Giao diện tối</span>
            </button>

            <form action="{{ route('seller.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-left" aria-hidden="true"></i>
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</div>
