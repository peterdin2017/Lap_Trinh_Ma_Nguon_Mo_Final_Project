{{-- Sidebar desktop --}}
<aside class="admin-sidebar d-none d-lg-flex flex-column flex-shrink-0 position-sticky top-0 vh-100">
    <a href="{{ route('admin.home') }}"
       class="admin-sidebar__brand d-flex align-items-center gap-3 px-4 py-4 text-decoration-none">
        <i class="bi bi-shield-lock fs-3" aria-hidden="true"></i>
        <span>
            <strong class="d-block fs-5">Admin Center</strong>
            <small>Quản trị hệ thống</small>
        </span>
    </a>

    <div class="admin-sidebar__section px-3 pt-4 pb-2 text-uppercase small fw-semibold">
        Menu chính
    </div>

    <nav class="nav flex-column px-3 gap-1" aria-label="Menu quản trị">
        <a href="{{ route('admin.home') }}"
           class="admin-nav-link nav-link rounded-3
                  {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill me-2" aria-hidden="true"></i>
            Tổng quan
        </a>

        <a href="{{ route('admin.sellers.index') }}"
           class="admin-nav-link nav-link rounded-3
                  {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
            <i class="bi bi-shop me-2" aria-hidden="true"></i>
            Duyệt cửa hàng
        </a>

        <a href="#" class="admin-nav-link nav-link rounded-3">
            <i class="bi bi-people-fill me-2" aria-hidden="true"></i>
            Tài khoản
        </a>

        <a href="#" class="admin-nav-link nav-link rounded-3">
            <i class="bi bi-box-seam me-2" aria-hidden="true"></i>
            Sản phẩm
        </a>
    </nav>

    <div class="admin-sidebar__bottom mt-auto p-3">
        <button id="adminThemeToggle"
                type="button"
                class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-2"
                aria-pressed="false">
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

        <div class="admin-sidebar__footer px-1 py-2 small">
            <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
            Khu vực quản trị
        </div>
    </div>
</aside>

{{-- Sidebar trượt trên điện thoại --}}
<div class="offcanvas offcanvas-start admin-sidebar admin-sidebar--mobile d-lg-none"
     tabindex="-1"
     id="adminSidebarMobile"
     aria-labelledby="adminSidebarMobileLabel">
    <div class="offcanvas-header admin-sidebar__mobile-header">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-shield-lock fs-3" aria-hidden="true"></i>
            <span>
                <strong id="adminSidebarMobileLabel" class="d-block fs-5">Admin Center</strong>
                <small>Quản trị hệ thống</small>
            </span>
        </div>

        <button type="button"
                class="btn-close admin-sidebar__close"
                data-bs-dismiss="offcanvas"
                aria-label="Đóng"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="admin-sidebar__section pt-2 pb-2 text-uppercase small fw-semibold">
            Menu chính
        </div>

        <nav class="nav flex-column gap-1" aria-label="Menu quản trị trên điện thoại">
            <a href="{{ route('admin.home') }}"
               class="admin-nav-link nav-link rounded-3
                      {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill me-2" aria-hidden="true"></i>
                Tổng quan
            </a>

            <a href="{{ route('admin.sellers.index') }}"
               class="admin-nav-link nav-link rounded-3
                      {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                <i class="bi bi-shop me-2" aria-hidden="true"></i>
                Duyệt cửa hàng
            </a>

            <a href="#" class="admin-nav-link nav-link rounded-3">
                <i class="bi bi-people-fill me-2" aria-hidden="true"></i>
                Tài khoản
            </a>

            <a href="#" class="admin-nav-link nav-link rounded-3">
                <i class="bi bi-box-seam me-2" aria-hidden="true"></i>
                Sản phẩm
            </a>
        </nav>

        <div class="admin-sidebar__bottom mt-auto pt-3">
            <button id="adminThemeToggleMobile"
                    type="button"
                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-2"
                    aria-pressed="false">
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

            <div class="admin-sidebar__footer px-1 py-2 small">
                <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
                Khu vực quản trị
            </div>
        </div>
    </div>
</div>

<style>
    .admin-sidebar {
        --admin-sidebar-bg: var(--app-card-bg, #fff);
        --admin-sidebar-text: var(--app-text-muted, #64748B);
        --admin-sidebar-border: var(--app-border, #E2E8F0);

        width: 260px;
        color: var(--app-text, #0F172A);
        background-color: var(--admin-sidebar-bg);
        border-right: 1px solid var(--admin-sidebar-border);
    }

    [data-bs-theme="dark"] .admin-sidebar {
        --admin-sidebar-bg: #0B192C;
        --admin-sidebar-text: #94A3B8;
        --admin-sidebar-border: #26364D;
    }

    .admin-sidebar--mobile {
        width: min(300px, 85vw);
    }

    .admin-sidebar__brand {
        color: var(--app-text, #0F172A);
        border-bottom: 1px solid var(--admin-sidebar-border);
    }

    .admin-sidebar__brand > i,
    .admin-sidebar__mobile-header > div > i {
        color: var(--app-primary, #1A94FF);
    }

    .admin-sidebar__brand small,
    .admin-sidebar__mobile-header small,
    .admin-sidebar__section,
    .admin-sidebar__footer {
        color: var(--admin-sidebar-text);
    }

    .admin-sidebar__section {
        letter-spacing: .04em;
    }

    .admin-sidebar__bottom {
        border-top: 1px solid var(--admin-sidebar-border);
    }

    .admin-sidebar__footer {
        padding-top: .5rem;
    }

    .admin-nav-link {
        position: relative;
        padding: .75rem .875rem;
        color: var(--admin-sidebar-text);
        transition: color .15s ease, background-color .15s ease;
    }

    .admin-nav-link:hover,
    .admin-nav-link:focus-visible,
    .admin-nav-link.active {
        color: var(--app-primary, #1A94FF);
        background-color: var(--app-primary-light, #EBF5FF);
    }

    .admin-nav-link.active {
        font-weight: 600;
    }

    .admin-nav-link.active::before {
        position: absolute;
        top: 25%;
        bottom: 25%;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
        background-color: var(--app-primary, #1A94FF);
        content: "";
    }

    .admin-sidebar__mobile-header {
        border-bottom: 1px solid var(--admin-sidebar-border);
    }

    [data-bs-theme="dark"] .admin-sidebar__close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    @media (prefers-reduced-motion: reduce) {
        .admin-nav-link {
            transition: none;
        }
    }
</style>

<script>
    (() => {
        const root = document.documentElement;
        const themeButtons = document.querySelectorAll(
            '#adminThemeToggle, #adminThemeToggleMobile'
        );

        if (!themeButtons.length) return;

        function syncThemeButtons() {
            const isDark = root.getAttribute('data-bs-theme') === 'dark';

            themeButtons.forEach((button) => {
                button.setAttribute('aria-pressed', String(isDark));

                const icon = button.querySelector('[data-theme-icon]');
                const label = button.querySelector('[data-theme-label]');

                if (icon) {
                    icon.className = isDark
                        ? 'bi bi-sun-fill'
                        : 'bi bi-moon-stars';
                }

                if (label) {
                    label.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
                }
            });
        }

        themeButtons.forEach((button) => {
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

                syncThemeButtons();
            });
        });

        new MutationObserver(syncThemeButtons).observe(root, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });

        syncThemeButtons();
    })();
</script>
