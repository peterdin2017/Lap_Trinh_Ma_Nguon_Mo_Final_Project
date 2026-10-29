<header class="seller-topbar d-flex align-items-center justify-content-between px-3 px-lg-4">
    <div class="d-flex min-w-0 align-items-center gap-3">
        <button class="btn btn-outline-primary d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sellerMobileMenu"
                aria-controls="sellerMobileMenu"
                aria-label="Mở thanh điều hướng">
            <i class="bi bi-list fs-5" aria-hidden="true"></i>
        </button>

        <div class="seller-topbar__heading">
            <h1 class="fw-bold mb-0">@yield('page-title', 'Kênh Người Bán')</h1>
            <small class="text-secondary">Quản lý hoạt động cửa hàng</small>
        </div>
    </div>

    <div class="dropdown">
        <button class="seller-account-button btn dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
            <span class="d-none d-sm-inline">Tài khoản</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end seller-topbar__menu">
            <li>
                <span class="dropdown-item-text small text-truncate d-block">
                    {{ auth()->user()?->email ?? 'Tài khoản Seller' }}
                </span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <button id="sellerThemeToggleHeader"
                        type="button"
                        class="dropdown-item d-flex align-items-center gap-2">
                    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                    <span data-theme-label>Giao diện tối</span>
                </button>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="{{ route('seller.shop-information.edit') }}">
                    <i class="bi bi-shop me-2" aria-hidden="true"></i>
                    Thông tin Shop
                </a>
            </li>
            <li>
                <form action="{{ route('seller.logout') }}" method="POST">
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
    .seller-topbar__heading { min-width: 0; }
    .seller-topbar__heading h1 { color: var(--seller-text); font-size: 17px; letter-spacing: -.01em; }
    .seller-topbar__heading small { display: block; margin-top: 2px; }
    .seller-account-button {
        color: var(--seller-primary);
        background: var(--seller-card-bg);
        border: 1px solid var(--seller-primary);
        border-radius: 7px;
        white-space: nowrap;
    }
    .seller-account-button:hover,
    .seller-account-button:focus-visible,
    .seller-account-button[aria-expanded="true"] {
        color: var(--seller-primary-hover);
        background: var(--seller-primary-light);
        border-color: var(--seller-primary);
    }
    .seller-topbar__menu {
        min-width: 230px;
        color: var(--seller-text);
        background: var(--seller-card-bg);
        border-color: var(--seller-border);
        box-shadow: var(--seller-shadow);
    }
    .seller-topbar__menu .dropdown-item,
    .seller-topbar__menu .dropdown-item-text { color: var(--seller-text); }
    .seller-topbar__menu .dropdown-item:hover,
    .seller-topbar__menu .dropdown-item:focus {
        color: var(--seller-primary);
        background: var(--seller-primary-light);
    }
    .seller-topbar__menu .dropdown-divider { border-color: var(--seller-border); }
    @media (max-width: 575.98px) {
        .seller-topbar__heading h1 { font-size: 15px; }
        .seller-topbar__heading small { font-size: 11px; }
    }
</style>
