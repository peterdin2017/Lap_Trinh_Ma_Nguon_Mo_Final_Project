<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Center - Website Bán hàng Online')</title>

    {{-- Khôi phục theme đã chọn trước khi trình duyệt hiển thị trang --}}
    <script>
        (() => {
            try {
                const theme = localStorage.getItem('app_theme') === 'dark'
                    ? 'dark'
                    : 'light';

                document.documentElement.setAttribute('data-bs-theme', theme);
            } catch {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
/* Giao diện riêng khu vực Admin, giữ nguyên Store và Seller */
.admin-hero{position:relative;display:flex;justify-content:space-between;align-items:center;overflow:hidden;background:linear-gradient(110deg,#153c63,#176ea0 72%,#178bbb);color:white;padding:29px 32px;margin-bottom:22px;border-radius:10px}.admin-hero h2{font-size:clamp(21px,2.4vw,30px);font-weight:800;letter-spacing:-.6px;margin:7px 0}.admin-hero p{color:#dceefa;margin:0}.admin-eyebrow{color:#ffb86a;font-size:11px;font-weight:800;letter-spacing:2px}.admin-hero-icon{font-size:80px;opacity:.19;margin-right:20px}.admin-kpi{position:relative;overflow:hidden;padding:23px;background:var(--app-card-bg);border:1px solid var(--app-border);border-radius:9px;min-height:125px;box-shadow:0 4px 20px #112f4c09}.admin-kpi-label{color:var(--app-text-muted);font-size:13px;font-weight:600}.admin-kpi-number{font-size:31px;font-weight:800;margin-top:12px;color:var(--app-text);line-height:1}.admin-kpi-symbol{position:absolute;right:20px;top:22px;font-size:27px;color:#fa913b;opacity:.85}.admin-panel{background:var(--app-card-bg);border:1px solid var(--app-border);border-radius:9px;overflow:hidden}.admin-panel-heading{padding:20px 22px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--app-border)}.admin-panel-heading h3{font-size:17px;font-weight:800;margin:0}.admin-panel-heading a{font-size:13px;text-decoration:none;font-weight:700}.admin-quick-links a{display:flex;align-items:center;gap:13px;padding:15px 22px;border-bottom:1px solid var(--app-border);color:var(--app-text);text-decoration:none;font-weight:600}.admin-quick-links a:hover{background:var(--app-primary-light)}.admin-quick-links a .bi:first-child{color:#ec8734;font-size:18px}.admin-quick-links a .bi:last-child{margin-left:auto;color:var(--app-text-muted)}.admin-panel .table{--bs-table-bg:transparent;--bs-table-color:var(--app-text);margin:0}.admin-panel .table th{background:var(--app-primary-light);color:var(--app-text);font-size:12px;letter-spacing:.3px;white-space:nowrap;padding:15px}.admin-panel .table td{padding:15px;vertical-align:middle;border-color:var(--app-border)}.admin-pill{display:inline-block;padding:5px 10px;font-size:12px;font-weight:750;border-radius:5px;white-space:nowrap}.admin-pill.success{background:#e2f6e9;color:#15733b}.admin-pill.danger{background:#fee9e7;color:#b52527}.admin-pill.warning{background:#fff1d9;color:#a45d08}.admin-pill.muted{background:#eef1f5;color:#657184}.admin-section-head{display:flex;justify-content:space-between;margin-bottom:18px}.admin-section-head h2{font-size:24px;font-weight:800;margin:0 0 5px}.admin-section-head p{color:var(--app-text-muted);margin:0}.admin-filter{display:flex;flex-wrap:wrap;gap:10px;padding:16px;background:var(--app-card-bg);border:1px solid var(--app-border);border-radius:9px;margin-bottom:17px}.admin-filter .form-control{flex:2 1 190px}.admin-filter .form-select{flex:1 1 140px}.admin-filter .btn{min-width:100px}.admin-product-thumb{width:55px;height:55px;object-fit:cover;border-radius:7px;flex-shrink:0}.admin-thumb-empty{display:flex;justify-content:center;align-items:center;background:var(--app-primary-light);color:#67809c}.admin-details summary{cursor:pointer;color:var(--app-primary);font-weight:700;white-space:nowrap}.admin-details form{min-width:175px}.admin-panel td{font-size:13px}[data-bs-theme="dark"] .admin-pill.success{background:#173e2c;color:#85d5a4}[data-bs-theme="dark"] .admin-pill.warning{background:#403117;color:#ffca75}[data-bs-theme="dark"] .admin-pill.danger{background:#481f24;color:#ff929c}[data-bs-theme="dark"] .admin-pill.muted{background:#344054;color:#d0d5dd}@media(max-width:767px){.admin-hero{padding:22px}.admin-hero-icon{display:none}.admin-kpi{padding:17px;min-height:105px}.admin-kpi-number{font-size:25px}.admin-filter .btn{width:100%}}

    </style>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            color-scheme: light;

            --app-primary: #1A94FF;
            --app-primary-hover: #0077E6;
            --app-primary-light: #EBF5FF;
            --app-page-bg: #F4F7FA;
            --app-card-bg: #FFFFFF;
            --app-header-bg: linear-gradient(135deg, #1A94FF 0%, #0077E6 100%);
            --app-text: #0F172A;
            --app-text-muted: #64748B;
            --app-border: #E2E8F0;
        }

        [data-bs-theme="dark"] {
            color-scheme: dark;

            --app-primary: #0D6EFD;
            --app-primary-hover: #0B5ED7;
            --app-primary-light: #172A46;
            --app-page-bg: #0F172A;
            --app-card-bg: #1E293B;
            --app-header-bg: linear-gradient(135deg, #0B192C 0%, #1E293B 100%);
            --app-text: #F8FAFC;
            --app-text-muted: #94A3B8;
            --app-border: #334155;
        }

        body {
            min-height: 100vh;
            background: var(--app-page-bg);
            color: var(--app-text);
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
            transition: background-color .2s ease, color .2s ease;
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--app-primary-hover);
            --bs-btn-hover-border-color: var(--app-primary-hover);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--app-primary-hover);
            --bs-btn-active-border-color: var(--app-primary-hover);
            --bs-btn-disabled-bg: var(--app-primary);
            --bs-btn-disabled-border-color: var(--app-primary);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--app-primary);
            --bs-btn-hover-border-color: var(--app-primary);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--app-primary-hover);
            --bs-btn-active-border-color: var(--app-primary-hover);
        }

        .text-primary,
        .link-primary {
            color: var(--app-primary) !important;
        }

        .text-secondary,
        .text-muted {
            color: var(--app-text-muted) !important;
        }

        .form-check-input:checked {
            border-color: var(--app-primary);
            background-color: var(--app-primary);
        }

        .page-item.active .page-link {
            color: #fff;
            border-color: var(--app-primary);
            background-color: var(--app-primary);
        }

        .page-link {
            color: var(--app-primary);
        }

        .card,
        .admin-card {
            color: var(--app-text);
            border-color: var(--app-border);
            background-color: var(--app-card-bg);
        }

        .form-control,
        .form-select {
            color: var(--app-text);
            border-color: var(--app-border);
            background-color: var(--app-card-bg);
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            color: var(--app-text);
        }

        [data-bs-theme="dark"] .form-control::placeholder {
            color: var(--app-text-muted);
        }

        [data-bs-theme="dark"] .table {
            --bs-table-color: var(--app-text);
            --bs-table-bg: var(--app-card-bg);
            --bs-table-border-color: var(--app-border);
            --bs-table-striped-bg: #172338;
            --bs-table-striped-color: var(--app-text);
            --bs-table-hover-bg: #25344A;
            --bs-table-hover-color: var(--app-text);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex min-vh-100">
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

        <div class="flex-grow-1 min-vh-100">
            <header class="admin-header navbar sticky-top px-3 px-lg-4 py-3">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-primary d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebarMobile"
                aria-controls="adminSidebarMobile"
                aria-label="Mở thanh điều hướng">
            <i class="bi bi-list fs-5" aria-hidden="true"></i>
        </button>

        <div>
            <h1 class="h5 fw-bold mb-0">
                @yield('page-title', 'Admin Center')
            </h1>
            <small class="text-secondary">Quản lý Website Bán hàng Online</small>
        </div>
    </div>

    <div class="dropdown">
        <button class="admin-header__account btn btn-outline-primary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
            <span>Admin</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <button type="button"
                        class="dropdown-item d-flex align-items-center justify-content-between gap-3"
                        data-admin-theme-toggle>
                    <span>
                        <i class="bi bi-moon-stars me-2" data-admin-theme-icon aria-hidden="true"></i>
                        <span data-admin-theme-label>Giao diện tối</span>
                    </span>
                    <input class="form-check-input m-0"
                           type="checkbox"
                           role="switch"
                           tabindex="-1"
                           aria-label="Bật hoặc tắt giao diện tối"
                           data-admin-theme-switch>
                </button>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <a class="dropdown-item" href="{{ route('admin.account.password.edit') }}">
                    <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>
                    Đổi mật khẩu
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>
                        Đăng xuất
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>

<style>
    .admin-header {
        color: var(--app-text);
        background-color: var(--app-card-bg);
        border-bottom: 1px solid var(--app-border);
    }

    .admin-header__account {
        white-space: nowrap;
    }

    .admin-header .dropdown-menu {
        min-width: 230px;
        color: var(--app-text);
        background-color: var(--app-card-bg);
        border-color: var(--app-border);
    }

    .admin-header .dropdown-item {
        color: var(--app-text);
    }

    .admin-header .dropdown-item:hover,
    .admin-header .dropdown-item:focus {
        background-color: var(--app-primary-light);
        color: var(--app-primary);
    }

    .admin-header .dropdown-divider {
        border-color: var(--app-border);
    }

    .admin-header [data-admin-theme-toggle] {
        cursor: pointer;
    }

    .admin-header [data-admin-theme-icon] {
        color: var(--app-primary);
    }

    [data-bs-theme="dark"] .admin-header [data-admin-theme-icon] {
        color: #FBBF24;
    }

    .admin-header [data-admin-theme-switch] {
        pointer-events: none;
    }
</style>

<script>
    (() => {
        const root = document.documentElement;
        const toggleButton = document.querySelector('[data-admin-theme-toggle]');
        const themeSwitch = document.querySelector('[data-admin-theme-switch]');
        const themeLabel = document.querySelector('[data-admin-theme-label]');
        const themeIcon = document.querySelector('[data-admin-theme-icon]');

        if (!toggleButton) return;

        function syncThemeControl() {
            const isDark = root.getAttribute('data-bs-theme') === 'dark';

            if (themeSwitch) themeSwitch.checked = isDark;
            if (themeLabel) {
                themeLabel.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
            }
            if (themeIcon) {
                themeIcon.className = isDark
                    ? 'bi bi-sun me-2'
                    : 'bi bi-moon-stars me-2';
            }
        }

        toggleButton.addEventListener('click', () => {
            if (typeof window.toggleAppTheme === 'function') {
                window.toggleAppTheme();
            } else {
                const nextTheme =
                    root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';

                root.setAttribute('data-bs-theme', nextTheme);

                try {
                    localStorage.setItem('app_theme', nextTheme);
                } catch {
                    // Giao diện vẫn đổi nếu trình duyệt không cho lưu localStorage.
                }
            }

            syncThemeControl();
        });

        syncThemeControl();

        new MutationObserver(syncThemeControl).observe(root, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });
    })();
</script>

            <main class="container-fluid px-3 px-lg-4 py-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="status">
                        {{ session('success') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="admin-footer px-3 px-lg-4 py-3">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small">
        <span>© {{ date('Y') }} Admin Center · Website Bán hàng Online</span>
        <span>
            <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
            Khu vực quản trị
        </span>
    </div>
</footer>

<style>
    .admin-footer {
        color: var(--app-text-muted);
        background-color: var(--app-card-bg);
        border-top: 1px solid var(--app-border);
    }

    .admin-footer .bi-shield-check {
        color: var(--app-primary);
    }
</style>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Bật/tắt theme và lưu lựa chọn --}}
    <script>
        (() => {
            const root = document.documentElement;

            function applyTheme(theme) {
                root.setAttribute('data-bs-theme', theme);

                try {
                    localStorage.setItem('app_theme', theme);
                } catch {
                    // Vẫn đổi giao diện nếu localStorage không khả dụng.
                }

                document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                    const isDark = theme === 'dark';
                    button.setAttribute('aria-pressed', String(isDark));

                    const icon = button.querySelector('[data-theme-icon]');
                    if (icon) {
                        icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
                    }

                    const label = button.querySelector('[data-theme-label]');
                    if (label) {
                        label.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
                    }
                });
            }

            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-theme-toggle]');
                if (!button) return;

                const currentTheme = root.getAttribute('data-bs-theme');
                applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
            });

            applyTheme(root.getAttribute('data-bs-theme') || 'light');
        })();
    </script>

    @stack('scripts')
</body>
</html>
