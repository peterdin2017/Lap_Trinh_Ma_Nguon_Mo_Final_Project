<header class="navbar bg-white border-bottom sticky-top px-3 px-lg-4 py-3">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-primary d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebarMobile"
                aria-controls="adminSidebarMobile"
                aria-label="Mở thanh điều hướng">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div>
            <h1 class="h5 fw-bold text-dark mb-0">@yield('page-title', 'Admin Center')</h1>
            <small class="text-secondary">Quản lý Website Bán hàng Online</small>
        </div>
    </div>

    <div class="dropdown">
        <button class="btn btn-light border dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-person-circle text-primary me-1"></i>
            <span class="d-none d-sm-inline">{{ auth()->user()?->email ?? 'Admin' }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>
