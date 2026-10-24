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

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
                <button type="button"
                        class="dropdown-item d-flex align-items-center justify-content-between gap-3 theme-toggle-item"
                        data-theme-toggle
                        aria-pressed="false">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-moon-stars"
                           data-theme-icon
                           aria-hidden="true"></i>
                        <span data-theme-label>Giao diện: Sáng</span>
                    </span>

                    <span class="form-check form-switch mb-0">
                        <input class="form-check-input m-0"
                               type="checkbox"
                               tabindex="-1"
                               aria-label="Bật giao diện tối"
                               data-theme-switch>
                    </span>
                </button>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <a class="dropdown-item"
                   href="{{ route('admin.account.password.edit') }}">
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
        min-width: 240px;
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

    .theme-toggle-item {
        transition: background-color .18s ease, color .18s ease;
    }

    .theme-toggle-item [data-theme-icon] {
        width: 1rem;
        color: var(--app-primary);
        text-align: center;
    }

    .theme-toggle-item .form-check-input {
        cursor: pointer;
        pointer-events: none;
    }

    [data-bs-theme="dark"] .admin-header .dropdown-menu {
        --bs-dropdown-bg: var(--app-card-bg);
        --bs-dropdown-link-color: var(--app-text);
        --bs-dropdown-link-hover-bg: var(--app-primary-light);
        --bs-dropdown-link-hover-color: var(--app-primary);
    }
</style>

<script>
    (() => {
        const root = document.documentElement;
        const storageKey = 'app_theme';
        const toggle = document.querySelector('[data-theme-toggle]');

        function applyTheme(theme) {
            const isDark = theme === 'dark';
            const normalizedTheme = isDark ? 'dark' : 'light';

            root.setAttribute('data-bs-theme', normalizedTheme);

            if (!toggle) return;

            toggle.setAttribute('aria-pressed', String(isDark));

            const icon = toggle.querySelector('[data-theme-icon]');
            const label = toggle.querySelector('[data-theme-label]');
            const themeSwitch = toggle.querySelector('[data-theme-switch]');

            if (icon) {
                icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
            }

            if (label) {
                label.textContent = `Giao diện: ${isDark ? 'Tối' : 'Sáng'}`;
            }

            if (themeSwitch) {
                themeSwitch.checked = isDark;
                themeSwitch.setAttribute(
                    'aria-label',
                    isDark ? 'Tắt giao diện tối' : 'Bật giao diện tối'
                );
            }
        }

        let savedTheme = 'light';

        try {
            savedTheme = localStorage.getItem(storageKey) === 'dark'
                ? 'dark'
                : 'light';
        } catch {
            // Giao diện vẫn hoạt động trong phiên hiện tại nếu localStorage bị chặn.
        }

        applyTheme(savedTheme);

        if (!toggle) return;

        toggle.addEventListener('click', () => {
            const nextTheme =
                root.getAttribute('data-bs-theme') === 'dark'
                    ? 'light'
                    : 'dark';

            applyTheme(nextTheme);

            try {
                localStorage.setItem(storageKey, nextTheme);
            } catch {
                // Giao diện vẫn đổi trong phiên hiện tại nếu không lưu được.
            }
        });
    })();
</script>
