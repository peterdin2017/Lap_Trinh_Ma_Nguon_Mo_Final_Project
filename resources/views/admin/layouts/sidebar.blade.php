@php
    $storeMenuOpen = request()->routeIs('admin.stores.*', 'admin.sellers.*');
    $accountMenuOpen = request()->routeIs('admin.accounts.*');
    $productMenuOpen = request()->routeIs('admin.products.*', 'admin.categories.*');
    $orderMenuOpen = request()->routeIs('admin.orders.*', 'admin.reports.*');
    $settingsActive = request()->routeIs('admin.settings.*');
@endphp

{{-- Sidebar desktop --}}
<aside class="admin-sidebar d-none d-lg-flex flex-column flex-shrink-0">
    <a href="{{ route('admin.home') }}" class="admin-sidebar__brand">
        <i class="bi bi-shield-lock" aria-hidden="true"></i>
        <span>
            <strong>Admin Center</strong>
            <small>Quản trị hệ thống</small>
        </span>
    </a>

    <div class="admin-sidebar__section">Menu chính</div>

    <nav class="admin-sidebar__nav" aria-label="Menu quản trị">
        <a href="{{ route('admin.home') }}"
           class="admin-nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i>
            <span>Tổng quan</span>
        </a>

        <button class="admin-nav-link admin-nav-toggle {{ $storeMenuOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminStoreMenu"
                aria-expanded="{{ $storeMenuOpen ? 'true' : 'false' }}"
                aria-controls="adminStoreMenu">
            <i class="bi bi-shop" aria-hidden="true"></i>
            <span>Quản lý Cửa hàng</span>
            <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
        </button>
        <div id="adminStoreMenu" class="collapse {{ $storeMenuOpen ? 'show' : '' }}">
            <nav class="admin-submenu" aria-label="Quản lý cửa hàng">
                <a href="#" class="admin-subnav-link {{ request()->routeIs('admin.stores.index') ? 'active' : '' }}">
                    Danh sách cửa hàng
                </a>
                <a href="{{ route('admin.sellers.index') }}"
                   class="admin-subnav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                    Duyệt hồ sơ
                </a>
                <a href="{{ route('admin.sellers.index') }}" class="admin-subnav-link">Cửa hàng vi phạm</a>
            </nav>
        </div>

        <button class="admin-nav-link admin-nav-toggle {{ $accountMenuOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminAccountMenu"
                aria-expanded="{{ $accountMenuOpen ? 'true' : 'false' }}"
                aria-controls="adminAccountMenu">
            <i class="bi bi-people-fill" aria-hidden="true"></i>
            <span>Quản lý Tài khoản</span>
            <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
        </button>
        <div id="adminAccountMenu" class="collapse {{ $accountMenuOpen ? 'show' : '' }}">
            <nav class="admin-submenu" aria-label="Quản lý tài khoản">
                <a href="{{ route('admin.accounts.index', ['role'=>'user']) }}" class="admin-subnav-link">Khách hàng</a>
                <a href="{{ route('admin.accounts.index', ['role'=>'seller']) }}" class="admin-subnav-link">Người bán</a>
                <a href="{{ route('admin.accounts.index', ['role'=>'admin']) }}" class="admin-subnav-link">Quản trị viên</a>
            </nav>
        </div>

        <button class="admin-nav-link admin-nav-toggle {{ $productMenuOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminProductMenu"
                aria-expanded="{{ $productMenuOpen ? 'true' : 'false' }}"
                aria-controls="adminProductMenu">
            <i class="bi bi-box-seam" aria-hidden="true"></i>
            <span>Quản lý Sản phẩm</span>
            <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
        </button>
        <div id="adminProductMenu" class="collapse {{ $productMenuOpen ? 'show' : '' }}">
            <nav class="admin-submenu" aria-label="Quản lý sản phẩm">
                <a href="{{ route('admin.products.index') }}" class="admin-subnav-link">Danh sách sản phẩm</a>
                <a href="{{ route('admin.categories.index') }}" class="admin-subnav-link">Danh mục ngành hàng</a>
                <a href="{{ route('admin.products.index', ['status'=>'locked']) }}" class="admin-subnav-link">Sản phẩm vi phạm</a>
            </nav>
        </div>

        <button class="admin-nav-link admin-nav-toggle {{ $orderMenuOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminOrderMenu"
                aria-expanded="{{ $orderMenuOpen ? 'true' : 'false' }}"
                aria-controls="adminOrderMenu">
            <i class="bi bi-receipt" aria-hidden="true"></i>
            <span>Đơn hàng &amp; Báo cáo</span>
            <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
        </button>
        <div id="adminOrderMenu" class="collapse {{ $orderMenuOpen ? 'show' : '' }}">
            <nav class="admin-submenu" aria-label="Đơn hàng và báo cáo">
                <a href="{{ route('admin.reports.index') }}" class="admin-subnav-link">Báo cáo vi phạm</a>
                <a href="{{ route('admin.home') }}" class="admin-subnav-link">Thống kê tổng quan</a>
            </nav>
        </div>

        <a href="#"
           class="admin-nav-link {{ $settingsActive ? 'active' : '' }}">
            <i class="bi bi-gear" aria-hidden="true"></i>
            <span>Cài đặt hệ thống</span>
        </a>
    </nav>

    <div class="admin-sidebar__bottom">
        <button id="adminThemeToggle"
                type="button"
                class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-2">
            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            <span data-theme-label>Giao diện tối</span>
        </button>

        <form action="{{ route('admin.logout') }}" method="POST" class="mb-2">
            @csrf
            <button type="submit"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-left" aria-hidden="true"></i>
                Đăng xuất
            </button>
        </form>

        <div class="admin-sidebar__meta">
            <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
            Khu vực quản trị · v1.0
        </div>
    </div>
</aside>

{{-- Sidebar mobile --}}
<div class="offcanvas offcanvas-start admin-sidebar admin-sidebar--mobile d-lg-none"
     tabindex="-1"
     id="adminSidebarMobile"
     aria-labelledby="adminSidebarMobileLabel">
    <div class="offcanvas-header admin-sidebar__mobile-header">
        <a href="{{ route('admin.home') }}" class="admin-sidebar__brand">
            <i class="bi bi-shield-lock" aria-hidden="true"></i>
            <span>
                <strong id="adminSidebarMobileLabel">Admin Center</strong>
                <small>Quản trị hệ thống</small>
            </span>
        </a>

        <button type="button"
                class="btn-close admin-sidebar__close"
                data-bs-dismiss="offcanvas"
                aria-label="Đóng"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="admin-sidebar__section">Menu chính</div>

        <nav class="admin-sidebar__nav" aria-label="Menu quản trị trên điện thoại">
            <a href="{{ route('admin.home') }}"
               class="admin-nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i>
                <span>Tổng quan</span>
            </a>

            <button class="admin-nav-link admin-nav-toggle {{ $storeMenuOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#adminStoreMenuMobile"
                    aria-expanded="{{ $storeMenuOpen ? 'true' : 'false' }}"
                    aria-controls="adminStoreMenuMobile">
                <i class="bi bi-shop" aria-hidden="true"></i>
                <span>Quản lý Cửa hàng</span>
                <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
            </button>
            <div id="adminStoreMenuMobile" class="collapse {{ $storeMenuOpen ? 'show' : '' }}">
                <nav class="admin-submenu" aria-label="Quản lý cửa hàng">
                    <a href="{{ route('admin.sellers.index') }}" class="admin-subnav-link">Danh sách cửa hàng</a>
                    <a href="{{ route('admin.sellers.index') }}"
                       class="admin-subnav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                        Duyệt hồ sơ
                    </a>
                    <a href="{{ route('admin.sellers.index') }}" class="admin-subnav-link">Cửa hàng vi phạm</a>
                </nav>
            </div>

            <button class="admin-nav-link admin-nav-toggle {{ $accountMenuOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#adminAccountMenuMobile"
                    aria-expanded="{{ $accountMenuOpen ? 'true' : 'false' }}"
                    aria-controls="adminAccountMenuMobile">
                <i class="bi bi-people-fill" aria-hidden="true"></i>
                <span>Quản lý Tài khoản</span>
                <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
            </button>
            <div id="adminAccountMenuMobile" class="collapse {{ $accountMenuOpen ? 'show' : '' }}">
                <nav class="admin-submenu" aria-label="Quản lý tài khoản">
                    <a href="{{ route('admin.accounts.index', ['role'=>'user']) }}" class="admin-subnav-link">Khách hàng</a>
                    <a href="{{ route('admin.accounts.index', ['role'=>'seller']) }}" class="admin-subnav-link">Người bán</a>
                    <a href="{{ route('admin.accounts.index', ['role'=>'admin']) }}" class="admin-subnav-link">Quản trị viên</a>
                </nav>
            </div>

            <button class="admin-nav-link admin-nav-toggle {{ $productMenuOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#adminProductMenuMobile"
                    aria-expanded="{{ $productMenuOpen ? 'true' : 'false' }}"
                    aria-controls="adminProductMenuMobile">
                <i class="bi bi-box-seam" aria-hidden="true"></i>
                <span>Quản lý Sản phẩm</span>
                <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
            </button>
            <div id="adminProductMenuMobile" class="collapse {{ $productMenuOpen ? 'show' : '' }}">
                <nav class="admin-submenu" aria-label="Quản lý sản phẩm">
                    <a href="{{ route('admin.products.index') }}" class="admin-subnav-link">Danh sách sản phẩm</a>
                    <a href="{{ route('admin.categories.index') }}" class="admin-subnav-link">Danh mục ngành hàng</a>
                    <a href="{{ route('admin.products.index', ['status'=>'locked']) }}" class="admin-subnav-link">Sản phẩm vi phạm</a>
                </nav>
            </div>

            <button class="admin-nav-link admin-nav-toggle {{ $orderMenuOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#adminOrderMenuMobile"
                    aria-expanded="{{ $orderMenuOpen ? 'true' : 'false' }}"
                    aria-controls="adminOrderMenuMobile">
                <i class="bi bi-receipt" aria-hidden="true"></i>
                <span>Đơn hàng &amp; Báo cáo</span>
                <i class="bi bi-chevron-down admin-nav-chevron" aria-hidden="true"></i>
            </button>
            <div id="adminOrderMenuMobile" class="collapse {{ $orderMenuOpen ? 'show' : '' }}">
                <nav class="admin-submenu" aria-label="Đơn hàng và báo cáo">
                    <a href="{{ route('admin.reports.index') }}" class="admin-subnav-link">Báo cáo vi phạm</a>
                    <a href="{{ route('admin.home') }}" class="admin-subnav-link">Thống kê tổng quan</a>
                </nav>
            </div>

            <a href="#" class="admin-nav-link {{ $settingsActive ? 'active' : '' }}">
                <i class="bi bi-gear" aria-hidden="true"></i>
                <span>Cài đặt hệ thống</span>
            </a>
        </nav>

        <div class="admin-sidebar__bottom mt-auto">
            <button id="adminThemeToggleMobile"
                    type="button"
                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-2">
                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                <span data-theme-label>Giao diện tối</span>
            </button>

            <form action="{{ route('admin.logout') }}" method="POST" class="mb-2">
                @csrf
                <button type="submit"
                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-left" aria-hidden="true"></i>
                    Đăng xuất
                </button>
            </form>

            <div class="admin-sidebar__meta">
                <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
                Khu vực quản trị · v1.0
            </div>
        </div>
    </div>
</div>

<style>
    .admin-sidebar {
        --sidebar-bg: var(--app-card-bg, #fff);
        --sidebar-text: var(--app-text-muted, #64748B);
        --sidebar-border: var(--app-border, #E2E8F0);

        width: 260px;
        color: var(--app-text, #0F172A);
        background: var(--sidebar-bg);
        border-right: 1px solid var(--sidebar-border);
    }

    [data-bs-theme="dark"] .admin-sidebar {
        --sidebar-bg: #0B192C;
        --sidebar-text: #94A3B8;
        --sidebar-border: #26364D;
    }

    .admin-sidebar--mobile {
        width: min(300px, 88vw);
    }

    .admin-sidebar__brand {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: 1.1rem 1.25rem;
        color: var(--app-text, #0F172A);
        text-decoration: none;
        border-bottom: 1px solid var(--sidebar-border);
    }

    .admin-sidebar__brand > i {
        color: var(--app-primary, #1A94FF);
        font-size: 1.6rem;
    }

    .admin-sidebar__brand strong,
    .admin-sidebar__brand small {
        display: block;
    }

    .admin-sidebar__brand small {
        margin-top: .15rem;
        color: var(--sidebar-text);
    }

    .admin-sidebar__section {
        padding: 1.1rem 1rem .5rem;
        color: var(--sidebar-text);
        font-size: .73rem;
        font-weight: 700;
        letter-spacing: .055em;
        text-transform: uppercase;
    }

    .admin-sidebar__nav {
        display: flex;
        flex-direction: column;
        gap: .2rem;
        padding: 0 .75rem;
    }

    .admin-nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: .75rem;
        width: 100%;
        padding: .7rem .75rem;
        border: 0;
        border-radius: .6rem;
        background: transparent;
        color: var(--sidebar-text);
        font: inherit;
        text-align: left;
        text-decoration: none;
        transition: color .15s ease, background-color .15s ease;
    }

    .admin-nav-link > i:first-child {
        width: 1.2rem;
        color: currentColor;
        text-align: center;
    }

    .admin-nav-link > span {
        flex: 1;
    }

    .admin-nav-link:hover,
    .admin-nav-link:focus-visible,
    .admin-nav-link.active {
        color: var(--app-primary, #1A94FF);
        background: var(--app-primary-light, #EBF5FF);
    }

    .admin-nav-link.active {
        font-weight: 600;
    }

    .admin-nav-chevron {
        transition: transform .2s ease;
    }

    .admin-nav-toggle[aria-expanded="true"] .admin-nav-chevron {
        transform: rotate(180deg);
    }

    .admin-submenu {
        display: flex;
        flex-direction: column;
        gap: .1rem;
        margin: .2rem 0 .35rem 1.35rem;
        padding-left: .7rem;
        border-left: 1px solid var(--sidebar-border);
    }

    .admin-subnav-link {
        padding: .5rem .65rem;
        border-radius: .45rem;
        color: var(--sidebar-text);
        font-size: .88rem;
        text-decoration: none;
    }

    .admin-subnav-link:hover,
    .admin-subnav-link.active {
        color: var(--app-primary, #1A94FF);
        background: var(--app-primary-light, #EBF5FF);
    }

    .admin-sidebar__bottom {
        margin-top: auto;
        padding: 1rem;
        border-top: 1px solid var(--sidebar-border);
    }

    .admin-sidebar__meta {
        padding: .35rem .1rem 0;
        color: var(--sidebar-text);
        font-size: .78rem;
    }

    .admin-sidebar__mobile-header {
        border-bottom: 1px solid var(--sidebar-border);
    }

    [data-bs-theme="dark"] .admin-sidebar__close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    @media (max-width: 991.98px) {
        .admin-sidebar--mobile {
            height: 100vh;
        }

        .admin-sidebar--mobile .offcanvas-body {
            overflow-y: auto;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .admin-nav-link,
        .admin-nav-chevron {
            transition: none;
        }
    }
</style>

<script>
    (() => {
        const root = document.documentElement;
        const buttons = document.querySelectorAll(
            '#adminThemeToggle, #adminThemeToggleMobile'
        );

        if (!buttons.length) return;

        function syncThemeButtons() {
            const isDark = root.getAttribute('data-bs-theme') === 'dark';

            buttons.forEach((button) => {
                button.setAttribute('aria-pressed', String(isDark));

                const icon = button.querySelector('[data-theme-icon]');
                const label = button.querySelector('[data-theme-label]');

                if (icon) {
                    icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
                }

                if (label) {
                    label.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
                }
            });
        }

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                if (typeof window.toggleAppTheme === 'function') {
                    window.toggleAppTheme();
                } else {
                    const nextTheme =
                        root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';

                    root.setAttribute('data-bs-theme', nextTheme);

                    try {
                        localStorage.setItem('app_theme', nextTheme);
                    } catch {
                        // Theme vẫn đổi trong phiên hiện tại nếu không lưu được.
                    }
                }
            });
        });

        new MutationObserver(syncThemeButtons).observe(root, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });

        syncThemeButtons();
    })();
</script>
