<header class="seller-topbar d-flex align-items-center justify-content-between px-3 px-lg-4">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-primary d-lg-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sellerMobileMenu" aria-controls="sellerMobileMenu" aria-label="Mở menu">
            <i class="bi bi-list fs-5" aria-hidden="true"></i>
        </button>

        <div>
            <h1 class="h5 fw-bold mb-0">Kênh Người Bán</h1>
            <small class="text-secondary">Quản lý hoạt động cửa hàng</small>
        </div>
    </div>

    <div class="dropdown">
        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-person-circle text-primary me-1" aria-hidden="true"></i>
            <span class="d-none d-sm-inline">Tài khoản</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
                <span class="dropdown-item-text small text-secondary text-truncate d-block" style="max-width: 240px;">
                    {{ auth()->user()?->email ?? 'Tài khoản Seller' }}
                </span>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <button id="sellerThemeToggle" type="button" class="dropdown-item d-flex align-items-center gap-2">
                    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                    <span data-theme-label>Giao diện tối</span>
                </button>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

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
    .seller-topbar .dropdown-menu {
        min-width: 240px;
        color: var(--seller-text, var(--app-text, #0F172A));
        background-color: var(--seller-card-bg, var(--app-card-bg, #fff));
        border-color: var(--seller-border, var(--app-border, #E2E8F0));
    }

    .seller-topbar .dropdown-item {
        color: var(--seller-text, var(--app-text, #0F172A));
    }

    .seller-topbar .dropdown-item:hover,
    .seller-topbar .dropdown-item:focus {
        color: var(--seller-primary, var(--app-primary, #1A94FF));
        background-color: var(--seller-primary-light, var(--app-primary-light, #EBF5FF));
    }

    .seller-topbar [data-theme-icon] {
        color: var(--seller-primary, var(--app-primary, #1A94FF));
    }

    [data-bs-theme="dark"] .seller-topbar [data-theme-icon] {
        color: #FBBF24;
    }

    .seller-topbar [data-theme-switch] {
        pointer-events: none;
    }
</style>

<script>
    (() => {
        const root = document.documentElement;
        const button = document.getElementById('sellerHeaderThemeToggle');

        if (!button) return;

        const themeSwitch = button.querySelector('[data-theme-switch]');
        const themeIcon = button.querySelector('[data-theme-icon]');
        const themeLabel = button.querySelector('[data-theme-label]');

        function syncThemeControl() {
            const isDark = root.getAttribute('data-bs-theme') === 'dark';

            if (themeSwitch) themeSwitch.checked = isDark;

            if (themeLabel) {
                themeLabel.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
            }

            if (themeIcon) {
                themeIcon.className = isDark ?
                    'bi bi-sun me-2' :
                    'bi bi-moon-stars me-2';
            }
        }

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
                    // Giao diện vẫn đổi nếu localStorage không khả dụng.
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
