@php
    $suffix = $suffix ?? '';
    $label = $label ?? 'Menu quản trị';

    $storeMenuOpen = request()->routeIs('admin.sellers.*');
    $accountMenuOpen = request()->routeIs('admin.accounts.*');
    $productMenuOpen = request()->routeIs('admin.products.*', 'admin.categories.*');

    $statisticsMenuOpen = request()->routeIs('admin.reports.statistics');
    $violationMenuOpen = request()->routeIs(
        'admin.reports.index',
        'admin.reports.handle'
    );
    $reportMenuOpen = $statisticsMenuOpen || $violationMenuOpen;

    $voucherMenuOpen = request()->routeIs('admin.vouchers.*');
    $supportMenuOpen = request()->routeIs('admin.support.*');
    $storeMenuId = 'adminStoreMenu' . $suffix;
    $accountMenuId = 'adminAccountMenu' . $suffix;
    $productMenuId = 'adminProductMenu' . $suffix;
    $reportMenuId = 'adminReportMenu' . $suffix;
@endphp

<nav class="admin-sidebar__nav" aria-label="{{ $label }}">
    <a href="{{ route('admin.home') }}"
       class="admin-nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
       @if (request()->routeIs('admin.home')) aria-current="page" @endif>
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

    <a href="{{ route('admin.vouchers.index') }}"
       class="admin-nav-link {{ $voucherMenuOpen ? 'active' : '' }}"
       @if ($voucherMenuOpen) aria-current="page" @endif>
        <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
        <span>Voucher sàn</span>
    </a>

    <a href="{{ route('admin.support.index') }}"
       class="admin-nav-link {{ $supportMenuOpen ? 'active' : '' }}"
       @if ($supportMenuOpen) aria-current="page" @endif>
        <i class="bi bi-headset" aria-hidden="true"></i>
        <span>Hỗ trợ khách hàng</span>
        <span class="admin-support-count-badge d-none"
              data-admin-support-count
              aria-live="polite"></span>
    </a>

    <button class="admin-nav-link admin-nav-toggle {{ $reportMenuOpen ? 'active' : '' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $reportMenuId }}"
            aria-expanded="{{ $reportMenuOpen ? 'true' : 'false' }}"
            aria-controls="{{ $reportMenuId }}">
        <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
        <span>Báo cáo &amp; thống kê</span>
        <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
    </button>

    <div id="{{ $reportMenuId }}" class="collapse {{ $reportMenuOpen ? 'show' : '' }}">
        <nav class="admin-submenu" aria-label="Báo cáo và thống kê">
            <a href="{{ route('admin.reports.statistics') }}"
               class="admin-subnav-link {{ $statisticsMenuOpen ? 'active' : '' }}"
               @if ($statisticsMenuOpen) aria-current="page" @endif>
                Thống kê toàn sàn
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="admin-subnav-link {{ $violationMenuOpen ? 'active' : '' }}"
               @if ($violationMenuOpen) aria-current="page" @endif>
                Báo cáo vi phạm
            </a>
        </nav>
    </div>
</nav>
