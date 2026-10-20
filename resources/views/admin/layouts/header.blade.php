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
