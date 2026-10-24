@php
    $productsOpen = request()->routeIs('seller.products.*');
    $ordersOpen = request()->routeIs('seller.orders.*');
    $reportsOpen = request()->routeIs('seller.reports.*', 'seller.analytics.*');

    $shopSettingsOpen = request()->routeIs(
        'seller.shop-information.*',
        'seller.shipping.*',
        'seller.promotions.*',
        'seller.vouchers.*',
    );

    $pendingOrderCount = $pendingOrdersCount ?? 0;
    $currentOrderStatus = request()->query('status', 'all');
    $onOrdersIndex = request()->routeIs('seller.orders.index');
@endphp

{{-- Sidebar desktop --}}
<aside class="seller-sidebar d-none d-lg-flex flex-column flex-shrink-0">
    <a href="{{ route('seller.home') }}" class="seller-brand">
        <i class="bi bi-shop-window" aria-hidden="true"></i>
        <span>
            <strong>Seller Center</strong>
            <small>Quản lý cửa hàng</small>
        </span>
    </a>

    <div class="seller-sidebar__section">Quản lý cửa hàng</div>

    <nav class="seller-sidebar__nav" aria-label="Menu người bán">
        <a href="{{ route('seller.home') }}"
           class="seller-nav-link {{ request()->routeIs('seller.home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2" aria-hidden="true"></i>
            <span>Tổng quan</span>
        </a>

        <a href="{{ url('/seller/chat') }}"
           class="seller-nav-link {{ request()->routeIs('seller.chat.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots" aria-hidden="true"></i>
            <span>Tin nhắn</span>
        </a>

        {{-- Quản lý sản phẩm --}}
        <button class="seller-nav-link seller-nav-toggle {{ $productsOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sellerProductMenu"
                aria-expanded="{{ $productsOpen ? 'true' : 'false' }}"
                aria-controls="sellerProductMenu">
            <i class="bi bi-box-seam" aria-hidden="true"></i>
            <span>Quản lý Sản phẩm</span>
            <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
        </button>

        <div id="sellerProductMenu" class="collapse {{ $productsOpen ? 'show' : '' }}">
            <nav class="seller-submenu" aria-label="Quản lý sản phẩm">
                <a href="{{ route('seller.products.index') }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.products.index') ? 'active' : '' }}">
                    Danh sách sản phẩm
                </a>

                <a href="{{ route('seller.products.create') }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.products.create') ? 'active' : '' }}">
                    Thêm sản phẩm mới
                </a>
            </nav>
        </div>

        {{-- Quản lý đơn hàng --}}
        <button class="seller-nav-link seller-nav-toggle {{ $ordersOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sellerOrderMenu"
                aria-expanded="{{ $ordersOpen ? 'true' : 'false' }}"
                aria-controls="sellerOrderMenu">
            <i class="bi bi-receipt" aria-hidden="true"></i>
            <span>Quản lý Đơn hàng</span>
            <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
        </button>

        <div id="sellerOrderMenu" class="collapse {{ $ordersOpen ? 'show' : '' }}">
            <nav class="seller-submenu" aria-label="Quản lý đơn hàng">
                <a href="{{ route('seller.orders.index', ['status' => 'all']) }}"
                   class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'all' ? 'active' : '' }}">
                    Tất cả đơn hàng
                </a>

                <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}"
                   class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'pending' ? 'active' : '' }}">
                    <span>Chờ xác nhận</span>
                    <span class="seller-menu-badge">{{ $pendingOrderCount }}</span>
                </a>

                <a href="{{ route('seller.orders.index', ['status' => 'shipping']) }}"
                   class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'shipping' ? 'active' : '' }}">
                    Đang vận chuyển
                </a>

                <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}"
                   class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'completed' ? 'active' : '' }}">
                    Đã hoàn thành
                </a>

                <a href="{{ route('seller.orders.index', ['status' => 'cancelled']) }}"
                   class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'cancelled' ? 'active' : '' }}">
                    Đơn hủy / Trả hàng
                </a>
            </nav>
        </div>

        {{-- Doanh thu và thống kê --}}
        <button class="seller-nav-link seller-nav-toggle {{ $reportsOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sellerReportsMenu"
                aria-expanded="{{ $reportsOpen ? 'true' : 'false' }}"
                aria-controls="sellerReportsMenu">
            <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
            <span>Doanh thu &amp; Thống kê</span>
            <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
        </button>

        <div id="sellerReportsMenu" class="collapse {{ $reportsOpen ? 'show' : '' }}">
            <nav class="seller-submenu" aria-label="Doanh thu và thống kê">
                <a href="{{ \Illuminate\Support\Facades\Route::has('seller.reports.revenue') ? route('seller.reports.revenue') : '#' }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.reports.revenue') ? 'active' : '' }}">
                    Báo cáo doanh thu
                </a>

                <a href="{{ \Illuminate\Support\Facades\Route::has('seller.analytics.index') ? route('seller.analytics.index') : '#' }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.analytics.*') ? 'active' : '' }}">
                    Phân tích bán hàng
                </a>
            </nav>
        </div>

        {{-- Thiết lập Shop --}}
        <button class="seller-nav-link seller-nav-toggle {{ $shopSettingsOpen ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sellerSettingsMenu"
                aria-expanded="{{ $shopSettingsOpen ? 'true' : 'false' }}"
                aria-controls="sellerSettingsMenu">
            <i class="bi bi-gear" aria-hidden="true"></i>
            <span>Thiết lập Shop</span>
            <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
        </button>

        <div id="sellerSettingsMenu" class="collapse {{ $shopSettingsOpen ? 'show' : '' }}">
            <nav class="seller-submenu" aria-label="Thiết lập Shop">
                <a href="{{ route('seller.shop-information.edit') }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.shop-information.*') ? 'active' : '' }}">
                    Hồ sơ cửa hàng
                </a>

                <a href="{{ \Illuminate\Support\Facades\Route::has('seller.shipping.edit') ? route('seller.shipping.edit') : '#' }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.shipping.*') ? 'active' : '' }}">
                    Cài đặt vận chuyển
                </a>

                <a href="{{ \Illuminate\Support\Facades\Route::has('seller.promotions.index') ? route('seller.promotions.index') : '#' }}"
                   class="seller-subnav-link {{ request()->routeIs('seller.promotions.*', 'seller.vouchers.*') ? 'active' : '' }}">
                    Mã giảm giá / Voucher
                </a>
            </nav>
        </div>
    </nav>

    <div class="seller-sidebar__footer">
        <button id="sellerThemeToggle"
                type="button"
                class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 mb-2"
                aria-pressed="false">
            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            <span data-theme-label>Giao diện tối</span>
        </button>

        <form action="{{ route('seller.logout') }}" method="POST" class="mb-2">
            @csrf
            <button type="submit"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                Đăng xuất
            </button>
        </form>

        <div class="seller-sidebar__caption">
            <i class="bi bi-shop-window me-1" aria-hidden="true"></i>
            Kênh quản lý cửa hàng · v1.0
        </div>
    </div>
</aside>

{{-- Sidebar mobile --}}
<div class="offcanvas offcanvas-start seller-sidebar seller-sidebar--mobile d-lg-none"
     tabindex="-1"
     id="sellerMobileMenu"
     aria-labelledby="sellerMobileMenuLabel">
    <div class="offcanvas-header seller-sidebar__mobile-header">
        <a href="{{ route('seller.home') }}" class="seller-brand">
            <i class="bi bi-shop-window" aria-hidden="true"></i>
            <span>
                <strong id="sellerMobileMenuLabel">Seller Center</strong>
                <small>Quản lý cửa hàng</small>
            </span>
        </a>

        <button type="button"
                class="btn-close seller-sidebar__close"
                data-bs-dismiss="offcanvas"
                aria-label="Đóng"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="seller-sidebar__section">Quản lý cửa hàng</div>

        <nav class="seller-sidebar__nav" aria-label="Menu người bán trên điện thoại">
            <a href="{{ route('seller.home') }}"
               class="seller-nav-link {{ request()->routeIs('seller.home') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2" aria-hidden="true"></i>
                <span>Tổng quan</span>
            </a>

            <a href="{{ url('/seller/chat') }}"
               class="seller-nav-link {{ request()->routeIs('seller.chat.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots" aria-hidden="true"></i>
                <span>Tin nhắn</span>
            </a>

            <button class="seller-nav-link seller-nav-toggle {{ $productsOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sellerProductMenuMobile"
                    aria-expanded="{{ $productsOpen ? 'true' : 'false' }}"
                    aria-controls="sellerProductMenuMobile">
                <i class="bi bi-box-seam" aria-hidden="true"></i>
                <span>Quản lý Sản phẩm</span>
                <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
            </button>

            <div id="sellerProductMenuMobile" class="collapse {{ $productsOpen ? 'show' : '' }}">
                <nav class="seller-submenu" aria-label="Sản phẩm">
                    <a href="{{ route('seller.products.index') }}"
                       class="seller-subnav-link {{ request()->routeIs('seller.products.index') ? 'active' : '' }}">
                        Danh sách sản phẩm
                    </a>

                    <a href="{{ route('seller.products.create') }}"
                       class="seller-subnav-link {{ request()->routeIs('seller.products.create') ? 'active' : '' }}">
                        Thêm sản phẩm mới
                    </a>

                    <a href="{{ \Illuminate\Support\Facades\Route::has('seller.products.hidden') ? route('seller.products.hidden') : '#' }}"
                       class="seller-subnav-link {{ request()->routeIs('seller.products.hidden') ? 'active' : '' }}">
                        Sản phẩm hết hàng / ẩn
                    </a>
                </nav>
            </div>

            <button class="seller-nav-link seller-nav-toggle {{ $ordersOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sellerOrderMenuMobile"
                    aria-expanded="{{ $ordersOpen ? 'true' : 'false' }}"
                    aria-controls="sellerOrderMenuMobile">
                <i class="bi bi-receipt" aria-hidden="true"></i>
                <span>Quản lý Đơn hàng</span>
                <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
            </button>

            <div id="sellerOrderMenuMobile" class="collapse {{ $ordersOpen ? 'show' : '' }}">
                <nav class="seller-submenu" aria-label="Đơn hàng">
                    <a href="{{ route('seller.orders.index', ['status' => 'all']) }}"
                       class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'all' ? 'active' : '' }}">
                        Tất cả đơn hàng
                    </a>

                    <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}"
                       class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'pending' ? 'active' : '' }}">
                        <span>Chờ xác nhận</span>
                        <span class="seller-menu-badge">{{ $pendingOrderCount }}</span>
                    </a>

                    <a href="{{ route('seller.orders.index', ['status' => 'shipping']) }}"
                       class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'shipping' ? 'active' : '' }}">
                        Đang vận chuyển
                    </a>

                    <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}"
                       class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'completed' ? 'active' : '' }}">
                        Đã hoàn thành
                    </a>

                    <a href="{{ route('seller.orders.index', ['status' => 'cancelled']) }}"
                       class="seller-subnav-link {{ $onOrdersIndex && $currentOrderStatus === 'cancelled' ? 'active' : '' }}">
                        Đơn hủy / Trả hàng
                    </a>
                </nav>
            </div>

            <button class="seller-nav-link seller-nav-toggle {{ $reportsOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sellerReportsMenuMobile"
                    aria-expanded="{{ $reportsOpen ? 'true' : 'false' }}"
                    aria-controls="sellerReportsMenuMobile">
                <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                <span>Doanh thu &amp; Thống kê</span>
                <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
            </button>

            <div id="sellerReportsMenuMobile" class="collapse {{ $reportsOpen ? 'show' : '' }}">
                <nav class="seller-submenu" aria-label="Báo cáo">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('seller.reports.revenue') ? route('seller.reports.revenue') : '#' }}"
                       class="seller-subnav-link">
                        Báo cáo doanh thu
                    </a>

                    <a href="{{ \Illuminate\Support\Facades\Route::has('seller.analytics.index') ? route('seller.analytics.index') : '#' }}"
                       class="seller-subnav-link">
                        Phân tích bán hàng
                    </a>
                </nav>
            </div>

            <button class="seller-nav-link seller-nav-toggle {{ $shopSettingsOpen ? 'active' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sellerSettingsMenuMobile"
                    aria-expanded="{{ $shopSettingsOpen ? 'true' : 'false' }}"
                    aria-controls="sellerSettingsMenuMobile">
                <i class="bi bi-gear" aria-hidden="true"></i>
                <span>Thiết lập Shop</span>
                <i class="bi bi-chevron-down seller-nav-chevron" aria-hidden="true"></i>
            </button>

            <div id="sellerSettingsMenuMobile" class="collapse {{ $shopSettingsOpen ? 'show' : '' }}">
                <nav class="seller-submenu" aria-label="Thiết lập Shop">
                    <a href="{{ route('seller.shop-information.edit') }}"
                       class="seller-subnav-link {{ request()->routeIs('seller.shop-information.*') ? 'active' : '' }}">
                        Hồ sơ cửa hàng
                    </a>

                    <a href="{{ \Illuminate\Support\Facades\Route::has('seller.shipping.edit') ? route('seller.shipping.edit') : '#' }}"
                       class="seller-subnav-link">
                        Cài đặt vận chuyển
                    </a>

                    <a href="{{ \Illuminate\Support\Facades\Route::has('seller.promotions.index') ? route('seller.promotions.index') : '#' }}"
                       class="seller-subnav-link">
                        Mã giảm giá / Voucher
                    </a>
                </nav>
            </div>
        </nav>

        <div class="seller-sidebar__footer mt-auto">
            <button id="sellerThemeToggleMobile"
                    type="button"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 mb-2"
                    aria-pressed="false">
                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                <span data-theme-label>Giao diện tối</span>
            </button>

            <form action="{{ route('seller.logout') }}" method="POST" class="mb-2">
                @csrf
                <button type="submit"
                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    Đăng xuất
                </button>
            </form>

            <div class="seller-sidebar__caption">
                <i class="bi bi-shop-window me-1" aria-hidden="true"></i>
                Kênh quản lý cửa hàng · v1.0
            </div>
        </div>
    </div>
</div>

<style>
    .seller-sidebar {
        --seller-sidebar-line: var(--seller-border, #E2E8F0);
        width: 260px;
        min-height: 100vh;
        color: var(--seller-text, #0F172A);
        background: var(--seller-sidebar-bg, #fff);
        border-right: 1px solid var(--seller-sidebar-line);
    }

    .seller-sidebar--mobile {
        width: min(320px, 88vw);
    }

    .seller-brand {
        display: flex;
        align-items: center;
        gap: .85rem;
        min-height: 70px;
        padding: 1rem 1.25rem;
        color: var(--seller-text, #0F172A);
        text-decoration: none;
        border-bottom: 1px solid var(--seller-sidebar-line);
    }

    .seller-brand > i {
        color: var(--seller-primary, #1A94FF);
        font-size: 1.5rem;
    }

    .seller-brand strong,
    .seller-brand small {
        display: block;
    }

    .seller-brand strong {
        color: var(--seller-text, #0F172A);
    }

    .seller-brand small {
        margin-top: .15rem;
        color: var(--seller-text-muted, #64748B);
    }

    .seller-sidebar__section {
        padding: 1.2rem 1rem .5rem;
        color: var(--seller-text-muted, #64748B);
        font-size: .73rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .seller-sidebar__nav {
        display: flex;
        flex-direction: column;
        gap: .2rem;
        padding: 0 .75rem;
    }

    .seller-nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: .75rem;
        width: 100%;
        padding: .72rem .8rem;
        border: 0;
        border-radius: .6rem;
        background: transparent;
        color: var(--seller-text-muted, #64748B);
        font: inherit;
        text-align: left;
        text-decoration: none;
        transition: color .15s ease, background-color .15s ease;
    }

    .seller-nav-link > i:first-child {
        width: 1.25rem;
        color: currentColor;
        text-align: center;
    }

    .seller-nav-link > span:first-of-type {
        flex: 1;
    }

    .seller-nav-link:hover,
    .seller-nav-link:focus-visible {
        color: var(--seller-primary, #1A94FF);
        background: var(--seller-hover-bg, #F8FAFC);
    }

    .seller-nav-link.active {
        color: var(--seller-primary, #1A94FF);
        background: var(--seller-primary-light, #EBF5FF);
        font-weight: 600;
    }

    .seller-nav-link.active::before {
        position: absolute;
        top: 25%;
        bottom: 25%;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
        background: var(--seller-primary, #1A94FF);
        content: "";
    }

    .seller-nav-chevron {
        margin-left: auto;
        transition: transform .2s ease;
    }

    .seller-nav-toggle[aria-expanded="true"] .seller-nav-chevron {
        transform: rotate(180deg);
    }

    .seller-submenu {
        display: flex;
        flex-direction: column;
        gap: .1rem;
        margin: .2rem 0 .4rem 1.25rem;
        padding-left: .75rem;
        border-left: 1px solid var(--seller-sidebar-line);
    }

    .seller-subnav-link {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem .7rem;
        border-radius: .45rem;
        color: var(--seller-text-muted, #64748B);
        font-size: .9rem;
        text-decoration: none;
        transition: color .15s ease, background-color .15s ease;
    }

    .seller-subnav-link:hover,
    .seller-subnav-link:focus-visible,
    .seller-subnav-link.active {
        color: var(--seller-primary, #1A94FF);
        background: var(--seller-primary-light, #EBF5FF);
    }

    .seller-subnav-link.active {
        font-weight: 600;
    }

    .seller-menu-badge {
        margin-left: auto;
        padding: .2rem .48rem;
        border-radius: 999px;
        background: var(--seller-primary-light, #EBF5FF);
        color: var(--seller-primary, #1A94FF);
        font-size: .72rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .seller-sidebar__footer {
        margin-top: auto;
        padding: 1rem;
        border-top: 1px solid var(--seller-sidebar-line);
    }

    .seller-sidebar__caption {
        padding-top: .35rem;
        color: var(--seller-text-muted, #64748B);
        font-size: .78rem;
        text-align: center;
    }

    .seller-sidebar .btn-outline-secondary {
        --bs-btn-color: var(--seller-text-muted, #64748B);
        --bs-btn-border-color: #CBD5E1;
        --bs-btn-hover-color: var(--seller-text, #0F172A);
        --bs-btn-hover-bg: var(--seller-hover-bg, #F8FAFC);
        --bs-btn-hover-border-color: #94A3B8;
    }

    [data-theme="dark"] .seller-sidebar .btn-outline-secondary {
        --bs-btn-color: var(--seller-text-muted);
        --bs-btn-border-color: #475569;
        --bs-btn-hover-color: var(--seller-text);
        --bs-btn-hover-bg: var(--seller-hover-bg);
        --bs-btn-hover-border-color: #64748B;
    }

    .seller-sidebar__mobile-header {
        border-bottom: 1px solid var(--seller-sidebar-line);
    }

    [data-theme="dark"] .seller-sidebar--mobile .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    @media (prefers-reduced-motion: reduce) {
        .seller-nav-link,
        .seller-subnav-link,
        .seller-nav-chevron {
            transition: none;
        }
    }
</style>

<script>
    (() => {
        const root = document.documentElement;
        const buttons = document.querySelectorAll(
            '#sellerThemeToggle, #sellerThemeToggleMobile'
        );

        const syncThemeButtons = () => {
            const isDark = root.dataset.theme === 'dark';

            buttons.forEach((button) => {
                button.setAttribute('aria-pressed', String(isDark));

                const icon = button.querySelector('[data-theme-icon]');
                const label = button.querySelector('[data-theme-label]');

                if (icon) {
                    icon.className = isDark
                        ? 'bi bi-sun'
                        : 'bi bi-moon-stars';
                }

                if (label) {
                    label.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
                }
            });
        };

        window.toggleAppTheme = window.toggleAppTheme || (() => {
            const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            root.dataset.theme = nextTheme;

            try {
                localStorage.setItem('seller-theme', nextTheme);
            } catch {
                // Theme vẫn đổi trong phiên hiện tại nếu localStorage không khả dụng.
            }

            syncThemeButtons();
            return nextTheme;
        });

        buttons.forEach((button) => {
            button.addEventListener('click', window.toggleAppTheme);
        });

        new MutationObserver(syncThemeButtons).observe(root, {
            attributes: true,
            attributeFilter: ['data-theme'],
        });

        syncThemeButtons();
    })();
</script>
