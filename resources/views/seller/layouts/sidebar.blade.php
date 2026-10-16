<aside class="seller-sidebar d-none d-lg-flex flex-column text-white flex-shrink-0">
    <a href="{{ route('seller.home') }}"
       class="seller-brand d-flex align-items-center px-4 border-bottom border-light border-opacity-25
              text-white text-decoration-none">
        <i class="bi bi-shop-window fs-4 me-3"></i>
        <span class="fw-bold fs-5">Seller Center</span>
    </a>

    <div class="px-3 pt-4 pb-2 small text-uppercase text-white-50">
        Quản lý cửa hàng
    </div>

    <nav class="nav flex-column px-3 gap-1">
        <a href="{{ route('seller.home') }}"
           class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3
                  {{ request()->routeIs('seller.home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 fs-5"></i>
            <span>Tổng quan</span>
        </a>

        <button
    class="seller-nav-link nav-link w-100 d-flex align-items-center gap-3 rounded-3 py-3 border-0
           {{ request()->routeIs('seller.products.*') ? 'active' : '' }}"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#sellerProductMenu"
    aria-expanded="{{ request()->routeIs('seller.products.*') ? 'true' : 'false' }}"
    aria-controls="sellerProductMenu"
>
    <i class="bi bi-box-seam fs-5"></i>
    <span class="flex-grow-1 text-start">Sản phẩm</span>
    <i class="bi bi-chevron-down small"></i>
</button>

<div
    class="collapse {{ request()->routeIs('seller.products.*') ? 'show' : '' }}"
    id="sellerProductMenu"
>
    <nav class="nav flex-column ms-4 ps-3 border-start border-light border-opacity-25">
        <a href="{{ route('seller.products.index') }}"
           class="seller-nav-link nav-link py-2
                  {{ request()->routeIs('seller.products.index') ? 'active' : '' }}">
            Danh sách sản phẩm
        </a>

        <a href="{{ route('seller.products.create') }}"
           class="seller-nav-link nav-link py-2
                  {{ request()->routeIs('seller.products.create') ? 'active' : '' }}">
            Thêm sản phẩm
        </a>
    </nav>
</div>

        <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3"
              aria-disabled="true">
            <i class="bi bi-receipt fs-5"></i>
            <span>Đơn hàng</span>
            <span class="badge text-bg-secondary ms-auto">Sắp có</span>
        </span>

        <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3"
              aria-disabled="true">
            <i class="bi bi-bar-chart-line fs-5"></i>
            <span>Thống kê</span>
            <span class="badge text-bg-secondary ms-auto">Sắp có</span>
        </span>

        <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3"
              aria-disabled="true">
            <i class="bi bi-shop fs-5"></i>
            <span>Thông tin Shop</span>
            <span class="badge text-bg-secondary ms-auto">Sắp có</span>
        </span>
    </nav>

    <div class="mt-auto p-3 border-top border-light border-opacity-25">
        <form action="{{ route('seller.logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-left"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>

<div class="offcanvas offcanvas-start text-bg-dark d-lg-none"
     tabindex="-1"
     id="sellerMobileMenu"
     aria-labelledby="sellerMobileMenuLabel"
     style="background-color: #003b73 !important;">
    <div class="offcanvas-header border-bottom border-light border-opacity-25">
        <h5 class="offcanvas-title" id="sellerMobileMenuLabel">
            <i class="bi bi-shop-window me-2"></i>Seller Center
        </h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="small text-uppercase text-white-50 mb-2">Quản lý cửa hàng</div>

        <nav class="nav flex-column gap-1">
            <a href="{{ route('seller.home') }}"
               class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3
                      {{ request()->routeIs('seller.home') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2 fs-5"></i>
                <span>Tổng quan</span>
            </a>

            <a href="{{ route('seller.products.index') }}"
               class="seller-nav-link nav-link d-flex align-items-center gap-3 rounded-3 py-3
                      {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam fs-5"></i>
                <span>Sản phẩm</span>
            </a>

            <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3"
                  aria-disabled="true">
                <i class="bi bi-receipt fs-5"></i>
                <span>Đơn hàng</span>
                <span class="badge text-bg-secondary ms-auto">Sắp có</span>
            </span>

            <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3"
                  aria-disabled="true">
                <i class="bi bi-bar-chart-line fs-5"></i>
                <span>Thống kê</span>
                <span class="badge text-bg-secondary ms-auto">Sắp có</span>
            </span>

            <span class="seller-nav-link disabled nav-link d-flex align-items-center gap-3 rounded-3 py-3"
                  aria-disabled="true">
                <i class="bi bi-shop fs-5"></i>
                <span>Thông tin Shop</span>
                <span class="badge text-bg-secondary ms-auto">Sắp có</span>
            </span>
        </nav>

        <div class="mt-auto pt-3">
            <form action="{{ route('seller.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-left"></i>
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</div>
