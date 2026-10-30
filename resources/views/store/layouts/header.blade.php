@php
    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->orderBy('category_name')
        ->limit(8)
        ->get(['category_id', 'category_name']);

    $unreadNotificationCount = 0;

    if (
        session('account_id')
        && in_array(session('account_role'), ['user', 'seller'], true)
    ) {
        $unreadNotificationCount = \App\Models\CustomerNotification::query()
            ->where('account_id', session('account_id'))
            ->whereNull('read_at')
            ->count();
    }
@endphp

<header class="shop-header">
    <div class="container py-3">
        <div class="row align-items-center g-3">
            <div class="col-7 col-lg-3">
                <a href="{{ route('store.home') }}"
                    class="shop-header__logo d-inline-flex align-items-center gap-2 text-decoration-none fw-bold">
                    <i class="bi bi-shop-window fs-3" aria-hidden="true"></i>
                    <span class="fs-5">Website Bán hàng Online</span>
                </a>
            </div>

            <div class="col-12 col-lg-5 order-3 order-lg-2">
                <form action="{{ route('store.products.index') }}" method="GET" role="search">
                    <label for="store-search" class="visually-hidden">
                        Tìm kiếm sản phẩm
                    </label>

                    <div class="input-group shop-header__search">
                        <input
                            id="store-search"
                            name="q"
                            type="search"
                            value="{{ request()->routeIs('store.products.index') ? request('q') : '' }}"
                            maxlength="100"
                            class="form-control"
                            placeholder="Tìm kiếm sản phẩm..."
                        >

                        <button class="btn shop-header__search-button" type="submit" aria-label="Tìm kiếm">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-5 col-lg-4 order-2 order-lg-3">
                <div class="shop-header__actions d-flex align-items-center justify-content-end">
                    <a
                        href="{{ route('store.support.index') }}"
                        class="shop-header__action btn"
                        aria-label="Trung tâm hỗ trợ"
                    >
                        <i class="bi bi-question-circle" aria-hidden="true"></i>
                        <span class="shop-header__action-label">Hỗ trợ</span>
                    </a>

                    <a
                        href="{{ route('store.cart.index') }}"
                        class="shop-header__action btn"
                        aria-label="Giỏ hàng"
                    >
                        <i class="bi bi-cart3" aria-hidden="true"></i>
                        <span class="shop-header__action-label">Giỏ hàng</span>
                    </a>

                    @if (session('account_id') && in_array(session('account_role'), ['user', 'seller'], true))
                        <a
                            href="{{ route('store.notifications.index') }}"
                            class="shop-header__action btn shop-header__notification-action"
                            aria-label="Thông báo{{ $unreadNotificationCount > 0 ? ', ' . $unreadNotificationCount . ' thông báo chưa đọc' : '' }}"
                        >
                            <i class="bi bi-bell" aria-hidden="true"></i>
                            <span class="shop-header__action-label">Thông báo</span>

                            @if ($unreadNotificationCount > 0)
                                <span class="shop-header__notification-badge">
                                    {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                                </span>
                            @endif
                        </a>
                    @endif

                    @if (session('account_id'))
                        <div class="dropdown">
                            <button
                                class="shop-header__action btn dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                aria-label="Tài khoản"
                            >
                                <i class="bi bi-person" aria-hidden="true"></i>
                                <span class="shop-header__action-label">Tài khoản</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('store.account.profile') }}">
                                        <i class="bi bi-person me-2" aria-hidden="true"></i>
                                        Hồ sơ của tôi
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('store.account.addresses.index') }}">
                                        <i class="bi bi-geo-alt me-2" aria-hidden="true"></i>
                                        Địa chỉ nhận hàng
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('store.account.password') }}">
                                        <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>
                                        Đổi mật khẩu
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('store.account.vouchers.index') }}">
                                        <i class="bi bi-ticket-perforated me-2" aria-hidden="true"></i>
                                        Voucher của tôi
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('store.account.orders.index') }}">
                                        <i class="bi bi-receipt me-2" aria-hidden="true"></i>
                                        Đơn hàng của tôi
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>
                                            Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a
                            href="{{ route('login.form') }}"
                            class="shop-header__action btn"
                            aria-label="Đăng nhập tài khoản"
                        >
                            <i class="bi bi-person" aria-hidden="true"></i>
                            <span class="shop-header__action-label">Tài khoản</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <nav
            class="shop-header__nav d-flex gap-4 gap-lg-5 mt-3 pt-2 overflow-x-auto"
            aria-label="Danh mục sản phẩm"
        >
            @foreach ($navCategories as $navCategory)
                <a
                    href="{{ route('store.products.index', ['category' => $navCategory->category_id]) }}"
                    class="shop-header__nav-link text-nowrap"
                >
                    {{ $navCategory->category_name }}
                </a>
            @endforeach
        </nav>
    </div>
</header>

<style>
    .shop-header__actions {
        flex-wrap: nowrap;
        gap: .4rem;
        min-width: 0;
    }

    .shop-header__action {
        position: relative;
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        white-space: nowrap;
    }

    .shop-header__notification-action {
        overflow: visible;
    }

    .shop-header__notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        z-index: 2;
        display: inline-flex !important;
        min-width: 17px;
        height: 17px;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid #fff;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
        pointer-events: none;
    }

    @media (min-width: 992px) and (max-width: 1399.98px) {
        .shop-header__actions {
            gap: .25rem;
        }

        .shop-header__action {
            width: 36px;
            min-width: 36px;
            height: 36px;
            padding: 0 !important;
        }

        .shop-header__action-label,
        .shop-header__action.dropdown-toggle::after {
            display: none;
        }
    }

    @media (max-width: 991.98px) {
        .shop-header__actions {
            gap: .25rem;
        }

        .shop-header__action {
            width: 29px;
            min-width: 29px;
            height: 32px;
            padding: 0 !important;
        }

        .shop-header__action-label,
        .shop-header__action.dropdown-toggle::after {
            display: none;
        }
    }

    @media (max-width: 380px) {
        .shop-header__actions {
            gap: 2px;
        }

        .shop-header__action {
            width: 24px;
            min-width: 24px;
            height: 30px;
        }

        .shop-header__notification-badge {
            top: -4px;
            right: -5px;
            min-width: 15px;
            height: 15px;
            font-size: 9px;
        }
    }
</style>
