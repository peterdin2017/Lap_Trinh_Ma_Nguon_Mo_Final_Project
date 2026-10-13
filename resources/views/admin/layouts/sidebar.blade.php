{{-- Sidebar desktop --}}
<aside class="d-none d-lg-flex flex-column flex-shrink-0 text-white position-sticky top-0 vh-100"
       style="width: 260px; background: linear-gradient(180deg, #003B73 0%, #002347 100%);">
    <a href="{{ route('admin.home') }}" class="d-flex align-items-center gap-3 text-white text-decoration-none px-4 py-4 border-bottom border-light border-opacity-25">
        <i class="bi bi-shield-lock fs-3"></i>
        <span>
            <strong class="d-block fs-5">Admin Center</strong>
            <small class="text-white-50">Quản trị hệ thống</small>
        </span>
    </a>

    <div class="px-3 pt-4 pb-2 text-uppercase text-white-50 small fw-semibold">Menu chính</div>
    <nav class="nav flex-column px-3 gap-1">
        <a href="{{ route('admin.home') }}" class="nav-link text-white rounded-3 {{ request()->routeIs('admin.home') ? 'active bg-white bg-opacity-25 fw-semibold' : '' }}">
            <i class="bi bi-grid-1x2-fill me-2"></i>Tổng quan
        </a>
        <a href="{{ route('admin.sellers.index') }}" class="nav-link text-white rounded-3 {{ request()->routeIs('admin.sellers.*') ? 'active bg-white bg-opacity-25 fw-semibold' : '' }}">
            <i class="bi bi-shop me-2"></i>Duyệt cửa hàng
        </a>
        <a href="#" class="nav-link text-white rounded-3">
            <i class="bi bi-people-fill me-2"></i>Tài khoản
        </a>
        <a href="#" class="nav-link text-white rounded-3">
            <i class="bi bi-box-seam me-2"></i>Sản phẩm
        </a>
    </nav>

    <div class="mt-auto px-4 py-3 border-top border-light border-opacity-25 small text-white-50">
        <i class="bi bi-shield-check me-1"></i>Khu vực quản trị
    </div>
</aside>

{{-- Sidebar trượt trên điện thoại --}}
<div class="offcanvas offcanvas-start text-white d-lg-none" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel" style="background: linear-gradient(180deg, #003B73 0%, #002347 100%);">
    <div class="offcanvas-header border-bottom border-light border-opacity-25">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-shield-lock fs-3"></i>
            <span>
                <strong id="adminSidebarMobileLabel" class="d-block fs-5">Admin Center</strong>
                <small class="text-white-50">Quản trị hệ thống</small>
            </span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>
    <div class="offcanvas-body">
        <div class="pt-2 pb-2 text-uppercase text-white-50 small fw-semibold">Menu chính</div>
        <nav class="nav flex-column gap-1">
            <a href="{{ route('admin.home') }}" class="nav-link text-white rounded-3 {{ request()->routeIs('admin.home') ? 'active bg-white bg-opacity-25 fw-semibold' : '' }}">
                <i class="bi bi-grid-1x2-fill me-2"></i>Tổng quan
            </a>
            <a href="{{ route('admin.sellers.index') }}" class="nav-link text-white rounded-3 {{ request()->routeIs('admin.sellers.*') ? 'active bg-white bg-opacity-25 fw-semibold' : '' }}">
                <i class="bi bi-shop me-2"></i>Duyệt cửa hàng
            </a>
            <a href="#" class="nav-link text-white rounded-3">
                <i class="bi bi-people-fill me-2"></i>Tài khoản
            </a>
            <a href="#" class="nav-link text-white rounded-3">
                <i class="bi bi-box-seam me-2"></i>Sản phẩm
            </a>
        </nav>
    </div>
</div>
