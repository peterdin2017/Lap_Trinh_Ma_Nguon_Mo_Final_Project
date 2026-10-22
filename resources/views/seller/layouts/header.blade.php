<header class="seller-topbar d-flex align-items-center justify-content-between px-3 px-lg-4">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-primary d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sellerMobileMenu"
                aria-controls="sellerMobileMenu"
                aria-label="Mở menu">
            <i class="bi bi-list fs-5" aria-hidden="true"></i>
        </button>

        <div>
            <h1 class="h5 fw-bold mb-0">Kênh Người Bán</h1>
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

        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <span class="dropdown-item-text seller-account-email small text-truncate d-block">
                    {{ auth()->user()?->email ?? 'Tài khoản Seller' }}
                </span>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <button id="sellerThemeToggle"
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
    .seller-account-button {
        color: var(--seller-text);
        background-color: var(--seller-card-bg);
        border: 1px solid var(--seller-border);
        white-space: nowrap;
    }

    .seller-account-button:hover,
    .seller-account-button:focus,
    .seller-account-button[aria-expanded="true"] {
        color: var(--seller-primary);
        background-color: var(--seller-primary-light);
        border-color: var(--seller-border);
    }

    .seller-account-button .bi {
        color: var(--seller-primary);
    }

    .seller-topbar .dropdown-menu {
        min-width: 240px;
        color: var(--seller-text);
        background-color: var(--seller-card-bg);
        border: 1px solid var(--seller-border);
        box-shadow: var(--seller-shadow);
    }

    .seller-topbar .dropdown-item {
        color: var(--seller-text);
    }

    .seller-topbar .dropdown-item:hover,
    .seller-topbar .dropdown-item:focus {
        color: var(--seller-primary);
        background-color: var(--seller-primary-light);
    }

    .seller-topbar .dropdown-item.text-danger {
        color: #DC3545 !important;
    }

    .seller-topbar .dropdown-divider {
        border-color: var(--seller-border);
    }

    .seller-topbar .dropdown-item-text {
        max-width: 240px;
        color: var(--seller-text-muted);
    }

    .seller-topbar [data-theme-icon] {
        color: var(--seller-primary);
    }

    [data-theme="dark"] .seller-topbar [data-theme-icon] {
        color: #FBBF24;
    }
</style>
