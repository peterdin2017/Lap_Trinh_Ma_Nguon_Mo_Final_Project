<header class="seller-topbar bg-white border-bottom d-flex align-items-center justify-content-between px-3 px-lg-4">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-primary d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sellerMobileMenu"
                aria-controls="sellerMobileMenu"
                aria-label="Mở danh mục">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div>
            <h1 class="h5 mb-0 fw-bold">Kênh Người Bán</h1>
            <small class="text-secondary">Quản lý hoạt động cửa hàng</small>
        </div>
    </div>

    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            {{ auth()->user()?->email ?? session('user_name', 'Người bán') }}
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
                <span class="dropdown-item-text small text-secondary">
                    Tài khoản người bán
                </span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('seller.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>
