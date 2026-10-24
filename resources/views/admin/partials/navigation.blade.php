@php
    $suffix = $suffix ?? '';
    $label = $label ?? 'Menu quản trị';

    $storeMenuOpen = request()->routeIs('admin.sellers.*');
    $accountMenuOpen = request()->routeIs('admin.accounts.*');
    $productMenuOpen = request()->routeIs('admin.products.*', 'admin.categories.*');
    $reportMenuOpen = request()->routeIs('admin.reports.*');

    $storeMenuId = 'adminStoreMenu' . $suffix;
    $accountMenuId = 'adminAccountMenu' . $suffix;
    $productMenuId = 'adminProductMenu' . $suffix;
@endphp

<nav class="admin-sidebar__nav" aria-label="{{ $label }}">
    <a href="{{ route('admin.home') }}"
       class="admin-nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i>
        <span>Tổng quan</span>
    </a>

    <button class="admin-nav-link admin-nav-toggle {{ $storeMenuOpen ? 'active' : '' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $storeMenuId }}"
            aria-expanded="{{ $storeMenuOpen ? 'true' : 'false' }}"
            aria-controls="{{ $storeMenuId }}">
        <i class="bi bi-shop" aria-hidden="true"></i>
        <span>Quản lý cửa hàng</span>
        <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
    </button>
    <div id="{{ $storeMenuId }}" class="collapse {{ $storeMenuOpen ? 'show' : '' }}">
        <nav class="admin-submenu" aria-label="Quản lý cửa hàng">
            <a href="{{ route('admin.sellers.index') }}"
               class="admin-subnav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                Duyệt hồ sơ
            </a>
        </nav>
    </div>

    <button class="admin-nav-link admin-nav-toggle {{ $accountMenuOpen ? 'active' : '' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $accountMenuId }}"
            aria-expanded="{{ $accountMenuOpen ? 'true' : 'false' }}"
            aria-controls="{{ $accountMenuId }}">
        <i class="bi bi-people-fill" aria-hidden="true"></i>
        <span>Quản lý tài khoản</span>
        <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
    </button>
    <div id="{{ $accountMenuId }}" class="collapse {{ $accountMenuOpen ? 'show' : '' }}">
        <nav class="admin-submenu" aria-label="Quản lý tài khoản">
            <a href="{{ route('admin.accounts.index', ['role' => 'user']) }}"
               class="admin-subnav-link">Khách hàng</a>
            <a href="{{ route('admin.accounts.index', ['role' => 'seller']) }}"
               class="admin-subnav-link">Người bán</a>
            <a href="{{ route('admin.accounts.index', ['role' => 'admin']) }}"
               class="admin-subnav-link">Quản trị viên</a>
        </nav>
    </div>

    <button class="admin-nav-link admin-nav-toggle {{ $productMenuOpen ? 'active' : '' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $productMenuId }}"
            aria-expanded="{{ $productMenuOpen ? 'true' : 'false' }}"
            aria-controls="{{ $productMenuId }}">
        <i class="bi bi-box-seam" aria-hidden="true"></i>
        <span>Quản lý sản phẩm</span>
        <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
    </button>
    <div id="{{ $productMenuId }}" class="collapse {{ $productMenuOpen ? 'show' : '' }}">
        <nav class="admin-submenu" aria-label="Quản lý sản phẩm">
            <a href="{{ route('admin.products.index') }}"
               class="admin-subnav-link">Danh sách sản phẩm</a>
            <a href="{{ route('admin.categories.index') }}"
               class="admin-subnav-link">Danh mục ngành hàng</a>
            <a href="{{ route('admin.products.index', ['status' => 'locked']) }}"
               class="admin-subnav-link">Sản phẩm đã khóa</a>
        </nav>
    </div>

    <a href="{{ route('admin.reports.index') }}"
       class="admin-nav-link {{ $reportMenuOpen ? 'active' : '' }}">
        <i class="bi bi-flag" aria-hidden="true"></i>
        <span>Báo cáo vi phạm</span>
    </a>
</nav>
