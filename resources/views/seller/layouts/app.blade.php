<!doctype html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Seller Center')</title>

    {{-- Khôi phục theme đã lưu trước khi render --}}
    <script>
        (() => {
            try {
                document.documentElement.dataset.theme =
                    localStorage.getItem('seller-theme') === 'dark' ? 'dark' : 'light';
            } catch {
                document.documentElement.dataset.theme = 'light';
            }
        })();
    </script>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root,
        [data-theme="light"] {
            color-scheme: light;

            --seller-primary: #1A94FF;
            --seller-primary-hover: #0077E6;
            --seller-primary-light: #EBF5FF;
            --seller-page-bg: #F4F7FA;
            --seller-card-bg: #FFFFFF;
            --seller-sidebar-bg: #FFFFFF;
            --seller-hover-bg: #F8FAFC;
            --seller-text: #0F172A;
            --seller-text-muted: #64748B;
            --seller-border: #E2E8F0;
            --seller-price: #EE4D2D;
            --seller-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            --seller-sidebar-width: 260px;
        }

        [data-theme="dark"] {
            color-scheme: dark;

            /* Đồng bộ palette tối với Admin Center */
            --seller-primary: #0D6EFD;
            --seller-primary-hover: #0B5ED7;
            --seller-primary-light: #172A46;
            --seller-page-bg: #0F172A;
            --seller-card-bg: #1E293B;
            --seller-sidebar-bg: #0B192C;
            --seller-hover-bg: #172A46;
            --seller-text: #F8FAFC;
            --seller-text-muted: #94A3B8;
            --seller-border: #26364D;
            --seller-price: #FB7185;
            --seller-shadow: 0 2px 8px rgba(0, 0, 0, .2);
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            color: var(--seller-text);
            background: var(--seller-page-bg);
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
            transition: color .18s ease, background-color .18s ease;
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--seller-primary);
            --bs-btn-border-color: var(--seller-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--seller-primary-hover);
            --bs-btn-hover-border-color: var(--seller-primary-hover);
            --bs-btn-active-bg: var(--seller-primary-hover);
            --bs-btn-active-border-color: var(--seller-primary-hover);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--seller-primary);
            --bs-btn-border-color: var(--seller-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--seller-primary);
            --bs-btn-hover-border-color: var(--seller-primary);
        }

        .text-primary,
        .link-primary {
            color: var(--seller-primary) !important;
        }

        .text-secondary,
        .form-text {
            color: var(--seller-text-muted) !important;
        }

        .seller-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            width: var(--seller-sidebar-width);
            height: 100vh;
            overflow-y: auto;
            color: var(--seller-text);
            background: var(--seller-sidebar-bg);
            border-right: 1px solid var(--seller-border);
        }

        .seller-main {
            min-width: 0;
            width: 100%;
        }

        .seller-content {
            min-height: calc(100vh - 70px - 58px);
        }

        .seller-brand {
            min-height: 70px;
            border-color: var(--seller-border) !important;
        }

        .seller-nav-link {
            position: relative;
            color: #475569;
            border-radius: .625rem;
            transition: color .18s ease, background-color .18s ease;
        }

        [data-theme="dark"] .seller-nav-link {
            color: var(--seller-text-muted);
        }

        .seller-nav-link.active {
            color: var(--seller-primary) !important;
            background: var(--seller-primary-light) !important;
            font-weight: 600;
        }

        .seller-nav-link.active::before {
            position: absolute;
            inset: .35rem auto .35rem 0;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--seller-primary);
            content: "";
        }

        .seller-nav-link.disabled {
            color: var(--seller-text-muted);
            opacity: .65;
            cursor: default;
        }

        .seller-topbar {
            min-height: 70px;
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border-bottom: 1px solid var(--seller-border);
        }

        .seller-mobile-menu {
            color: var(--seller-text);
            background: var(--seller-sidebar-bg);
        }

        .seller-content .card,
        .seller-content .dashboard-card,
        .seller-content .welcome-card,
        .seller-content .products-table-card {
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border: 1px solid var(--seller-border);
            border-radius: 12px;
            box-shadow: var(--seller-shadow);
        }

        .seller-content .dropdown-menu {
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border-color: var(--seller-border);
            box-shadow: var(--seller-shadow);
        }

        .seller-content .dropdown-item {
            color: var(--seller-text);
        }

        .seller-content .dropdown-item:hover {
            color: var(--seller-primary);
            background: var(--seller-primary-light);
        }

        .seller-footer {
            color: var(--seller-text-muted);
            background: var(--seller-card-bg);
            border-top: 1px solid var(--seller-border);
        }

        @media (min-width: 992px) {
            .seller-main {
                width: auto;
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-layout.css') }}">
    <style>
/* Seller Center visual system aligned with Admin Center. */
:root,[data-theme="light"] {
  --seller-primary:#1688ff;
  --seller-primary-hover:#0874e2;
  --seller-primary-light:#eaf4ff;
  --seller-sidebar-bg:#ffffff;
  --seller-card-bg:#ffffff;
  --seller-page-bg:#f4f7fb;
  --seller-text:#101f37;
  --seller-text-muted:#62758f;
  --seller-border:#e2e9f2;
  --seller-shadow:0 2px 12px rgba(22,52,93,.045);
}
[data-theme="dark"] {
  --seller-primary:#67aaff;
  --seller-primary-hover:#3d91f5;
  --seller-primary-light:#173354;
  --seller-sidebar-bg:#0b192c;
  --seller-card-bg:#16263d;
  --seller-page-bg:#101b2e;
  --seller-text:#eef4ff;
  --seller-text-muted:#9bafcb;
  --seller-border:#2d405c;
}
/* Prevent CSS from the old Seller Center affecting Admin. */
.seller-sidebar {
  --seller-sidebar-line:var(--seller-border);
  color:var(--seller-text)!important;
  background:var(--seller-sidebar-bg)!important;
  border-right:1px solid var(--seller-border)!important;
}
.seller-sidebar .seller-brand {
  display:flex;align-items:center;gap:13px;
  min-height:83px;padding:17px 20px;
  background:var(--seller-sidebar-bg);
  border-bottom:1px solid var(--seller-border);
  color:var(--seller-text);
  text-decoration:none;
}
.seller-sidebar .seller-brand>i {
  display:inline-grid;place-items:center;flex:0 0 25px;
  width:25px;font-size:23px;color:var(--seller-primary);
}
.seller-sidebar .seller-brand strong {font-size:15px;font-weight:750;line-height:1.4;color:var(--seller-text)}
.seller-sidebar .seller-brand small {font-size:12px;color:var(--seller-text-muted)}
.seller-sidebar .seller-sidebar__section {
  padding:19px 16px 9px;font-size:11px;letter-spacing:.06em;
  color:var(--seller-text-muted);font-weight:750;text-transform:uppercase;
}
.seller-sidebar .seller-sidebar__nav {gap:3px;padding:0 12px 15px}
.seller-sidebar .seller-nav-link {
  position:relative;display:flex;align-items:center;gap:12px;
  width:100%;min-height:47px;padding:11px 13px;
  border:0;border-radius:10px;background:transparent;
  color:var(--seller-text-muted)!important;
  font-size:14px;font-weight:450;line-height:1.35;
  text-align:left;text-decoration:none;cursor:pointer;
  transition:background-color .18s ease,color .18s ease,transform .18s ease,box-shadow .18s ease;
}
.seller-sidebar .seller-nav-link>i:first-child {
  display:inline-flex;align-items:center;justify-content:center;
  flex:0 0 21px;width:21px;height:22px;font-size:17px;line-height:1;
}
.seller-sidebar .seller-nav-link>span:first-of-type {min-width:0;flex:1 1 auto}
.seller-sidebar .seller-nav-link:hover,
.seller-sidebar .seller-nav-link:focus-visible,
.seller-sidebar .seller-nav-link.active {
  color:var(--seller-primary)!important;
  background:var(--seller-primary-light)!important;
}
.seller-sidebar .seller-nav-link:hover:not(.active) {transform:translateX(2px)}
.seller-sidebar .seller-nav-link.active {font-weight:650}
.seller-sidebar .seller-nav-link.active::before {display:none}
.seller-sidebar .seller-nav-link:focus-visible,
.seller-sidebar .seller-subnav-link:focus-visible {outline:2px solid var(--seller-primary);outline-offset:2px}
.seller-sidebar .seller-nav-chevron {
  flex:0 0 15px;width:15px;margin-left:auto;font-size:12px;
  transition:transform .2s ease;
}
.seller-sidebar .seller-nav-toggle[aria-expanded="true"] .seller-nav-chevron {transform:rotate(180deg)}
.seller-sidebar .seller-submenu {
  display:flex;flex-direction:column;gap:3px;
  margin:3px 0 9px 21px;padding-left:9px;
  border-left:1px solid var(--seller-border);
}
.seller-sidebar .seller-subnav-link {
  display:flex;min-height:37px;align-items:center;gap:8px;
  border-radius:7px;padding:8px 10px;font-size:13px;line-height:1.4;
  color:var(--seller-text-muted);text-decoration:none;
  transition:color .16s ease,background-color .16s ease;
}
.seller-sidebar .seller-subnav-link:hover,
.seller-sidebar .seller-subnav-link:focus-visible,
.seller-sidebar .seller-subnav-link.active {
  color:var(--seller-primary)!important;background:var(--seller-primary-light)!important;
}
.seller-sidebar .seller-menu-badge {
  flex-shrink:0;margin-left:auto;border-radius:6px;padding:3px 7px;
  color:var(--seller-primary);background:var(--seller-primary-light);
}
.seller-sidebar .seller-sidebar__footer {
  padding:15px 16px;border-top:1px solid var(--seller-border);background:var(--seller-sidebar-bg);
}
.seller-sidebar .seller-sidebar__footer .btn {
  min-height:39px;border-radius:6px;font-size:12px;
  transition:background-color .18s ease,color .18s ease,border-color .18s ease;
}
.seller-sidebar .seller-sidebar__footer .btn[id^="sellerThemeToggle"] {
  color:var(--seller-primary);border-color:var(--seller-primary);
}
.seller-sidebar .seller-sidebar__footer .btn[id^="sellerThemeToggle"]:hover {
  background:var(--seller-primary-light);color:var(--seller-primary);
}
.seller-sidebar .seller-sidebar__caption {
  font-size:11px;text-align:left;color:var(--seller-text-muted);
}
.app-main.seller-main {background:var(--seller-page-bg);color:var(--seller-text)}
.app-main.seller-main>.seller-topbar {
  min-height:60px;height:60px;border-bottom:1px solid var(--seller-border);
  background:var(--seller-card-bg);
}
.seller-topbar h1 {font-size:17px!important;letter-spacing:-.01em}
.seller-topbar .seller-account-button {border-radius:7px;border-color:var(--seller-primary);color:var(--seller-primary)}
.seller-topbar .seller-account-button:hover {background:var(--seller-primary-light)}
.seller-content .card,.seller-content .dashboard-card,
.seller-content .welcome-card,.seller-content .products-table-card {
  border-radius:10px;box-shadow:var(--seller-shadow);
}
.seller-content .btn,.seller-content .form-control,.seller-content .form-select {border-radius:7px}
.seller-content .table thead th {background:var(--seller-primary-light);color:var(--seller-text);font-size:12px}
/* Keep mobile offcanvas fluid, prevent desktop fixed sidebar overlap. */
@media (max-width:991.98px) {
  .seller-sidebar--mobile {width:min(310px,88vw)!important}
  .seller-sidebar--mobile .seller-sidebar__nav {overflow-y:visible}
  .app-main.seller-main {margin-left:0;width:100%}
}
@media (prefers-reduced-motion:reduce) {
  .seller-sidebar .seller-nav-link,
  .seller-sidebar .seller-subnav-link,
  .seller-sidebar .seller-nav-chevron {transition:none!important;transform:none!important}
}

</style>
</head>

<body>
    <div class="app-wrapper">
        @include('seller.layouts.sidebar')

        <div class="app-main seller-main">
           

            <main class="content-body seller-content container-fluid px-3 px-lg-4 py-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="status">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                        {{ session('success') }}
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Đóng"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ session('error') }}
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Đóng"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('seller.layouts.footer')
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Một bộ xử lý duy nhất cho nút theme seller --}}
    <script>
        (() => {
            const root = document.documentElement;
            const themeSelector =
                '#sellerThemeToggle, #sellerThemeToggleMobile, #sellerThemeToggleHeader';

            function syncThemeButtons() {
                const isDark = root.dataset.theme === 'dark';

                document.querySelectorAll(themeSelector).forEach((button) => {
                    button.setAttribute('aria-pressed', String(isDark));

                    const icon = button.querySelector('[data-theme-icon]');
                    const label = button.querySelector('[data-theme-label]');

                    if (icon) {
                        icon.className = isDark
                            ? 'bi bi-sun'
                            : 'bi bi-moon-stars';
                    }

                    if (label) {
                        label.textContent = isDark
                            ? 'Giao diện sáng'
                            : 'Giao diện tối';
                    }
                });
            }

            document.addEventListener('click', (event) => {
                const button = event.target.closest(themeSelector);
                if (!button) return;

                const nextTheme =
                    root.dataset.theme === 'dark' ? 'light' : 'dark';

                root.dataset.theme = nextTheme;

                try {
                    localStorage.setItem('seller-theme', nextTheme);
                } catch {
                    // Giao diện vẫn đổi trong phiên hiện tại nếu localStorage bị chặn.
                }

                syncThemeButtons();
            });

            syncThemeButtons();

            document.querySelectorAll('.alert.alert-dismissible').forEach((alert) => {
                window.setTimeout(() => {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }, 4500);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
