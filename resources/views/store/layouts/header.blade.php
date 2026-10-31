@php
    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->orderBy('category_name')
        ->limit(8)
        ->get(['category_id', 'category_name']);

    $accountId = session('account_id');
    $accountRole = session('account_role');
    $isStoreAccount = $accountId && in_array($accountRole, ['user', 'seller'], true);

    $unreadNotificationCount = 0;

    if ($isStoreAccount) {
        $unreadNotificationCount = \App\Models\CustomerNotification::query()
            ->where('account_id', $accountId)
            ->whereNull('read_at')
            ->count();
    }

    $cartItemCount = 0;

    if ($accountId) {
        $cartItemCount = (int) \App\Models\CartItem::query()
            ->whereHas('cart', fn ($query) => $query->where('account_id', $accountId))
            ->sum('quantity');
    }

    $storeProfile = null;

    if ($accountId && $accountRole === 'user') {
        $storeProfile = \App\Models\User::query()
            ->where('account_id', $accountId)
            ->first(['full_name', 'avatar']);
    }

    $profileName = $storeProfile?->full_name
        ?: session('user_name')
        ?: 'Tài khoản';

    $avatarPath = $storeProfile?->avatar
        ?: session('avatar');
@endphp

<header class="shop-header">
    <div class="shop-header__utility">
        <div class="container shop-header__utility-inner">
            <div class="shop-header__utility-actions">
                <a href="{{ route('store.support.index') }}" class="shop-header__utility-link">
                    <i class="bi bi-question-circle" aria-hidden="true"></i>
                    <span>Hỗ trợ</span>
                </a>

                @if ($isStoreAccount)
                    <a
                        href="{{ route('store.notifications.index') }}"
                        class="shop-header__utility-link shop-header__notification-link"
                        aria-label="Thông báo{{ $unreadNotificationCount > 0 ? ', ' . $unreadNotificationCount . ' chưa đọc' : '' }}"
                    >
                        <i class="bi bi-bell" aria-hidden="true"></i>
                        <span>Thông báo</span>

                        @if ($unreadNotificationCount > 0)
                            <span class="shop-header__notification-badge">
                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                            </span>
                        @endif
                    </a>
                @endif

                @if ($accountId)
                    <div class="dropdown shop-header__account-dropdown">
                        <button
                            type="button"
                            class="shop-header__profile dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            @if ($avatarPath)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($avatarPath) }}"
                                    alt="Ảnh đại diện {{ $profileName }}"
                                    class="shop-header__avatar"
                                >
                            @else
                                <span
                                    class="shop-header__avatar shop-header__avatar--fallback"
                                    aria-hidden="true"
                                >
                                    {{ mb_strtoupper(mb_substr($profileName, 0, 1)) }}
                                </span>
                            @endif

                            <span class="shop-header__profile-name">{{ $profileName }}</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shop-header__profile-menu">
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
                    <a href="{{ route('login.form') }}" class="shop-header__utility-link">
                        <i class="bi bi-person-circle" aria-hidden="true"></i>
                        <span>Đăng nhập</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <div class="shop-header__main">
            <a href="{{ route('store.home') }}" class="shop-header__logo">
                <span class="shop-header__logo-icon">
                    <i class="bi bi-shop-window" aria-hidden="true"></i>
                </span>
                <span class="shop-header__logo-text">Website Bán hàng Online</span>
            </a>

            <form
                action="{{ route('store.products.index') }}"
                method="GET"
                class="shop-header__search"
                role="search"
            >
                <label for="store-search" class="visually-hidden">Tìm kiếm sản phẩm</label>
                <input
                    id="store-search"
                    name="q"
                    type="search"
                    value="{{ request()->routeIs('store.products.index') ? request('q') : '' }}"
                    maxlength="100"
                    placeholder="Tìm kiếm sản phẩm..."
                    autocomplete="off"
                    role="combobox"
                    aria-autocomplete="list"
                    aria-expanded="false"
                    aria-controls="store-search-suggestions"
                >
                <div id="store-search-suggestions"
                     class="shop-search-suggestions d-none"
                     role="listbox"
                     aria-label="Gợi ý sản phẩm"></div>
                <button type="submit" aria-label="Tìm kiếm">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </form>

            <a
                href="{{ route('store.cart.index') }}"
                class="shop-header__cart"
                aria-label="Giỏ hàng, {{ $cartItemCount }} sản phẩm"
            >
                <span class="shop-header__cart-icon">
                    <i class="bi bi-cart3" aria-hidden="true"></i>
                    <span
                        id="store-cart-count"
                        class="shop-header__cart-count"
                        @if ($cartItemCount < 1) hidden @endif
                    >{{ $cartItemCount > 99 ? '99+' : $cartItemCount }}</span>
                </span>

                <span class="shop-header__cart-label">Giỏ hàng</span>
            </a>
        </div>

        <nav class="shop-header__nav" aria-label="Danh mục sản phẩm">
            @foreach ($navCategories as $navCategory)
                <a
                    href="{{ route('store.products.index', ['category' => $navCategory->category_id]) }}"
                    class="shop-header__nav-link {{ request('category') == $navCategory->category_id ? 'is-active' : '' }}"
                >
                    {{ $navCategory->category_name }}
                </a>
            @endforeach
        </nav>
    </div>
</header>

<style>
    .shop-header {
        position: relative;
        z-index: 20;
        color: #fff;
        background: linear-gradient(105deg, #1687f8 0%, #20b4e8 100%);
        box-shadow: 0 4px 16px rgba(25, 91, 150, .14);
    }

    .shop-header__utility {
        height: 34px;
        min-height: 34px;
        border-bottom: 1px solid rgba(255, 255, 255, .2);
        background: rgba(0, 83, 160, .12);
    }

    .shop-header__utility-inner {
        display: flex;
        height: 33px;
        align-items: center;
        justify-content: flex-end;
    }

    .shop-header__utility-actions {
        display: flex;
        height: 100%;
        align-items: center;
        gap: 1.15rem;
    }

    .shop-header__utility-link {
        position: relative;
        display: inline-flex;
        height: 100%;
        align-items: center;
        gap: .35rem;
        color: rgba(255, 255, 255, .94);
        font-size: .78rem;
        line-height: 1;
        text-decoration: none;
        white-space: nowrap;
    }

    .shop-header__utility-link:hover,
    .shop-header__profile:hover {
        color: #fff;
        opacity: .82;
    }

    .shop-header__notification-link {
        overflow: visible;
    }

    .shop-header__notification-badge {
        position: absolute;
        top: 0;
        right: -12px;
        display: inline-flex;
        min-width: 16px;
        height: 16px;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 1px solid #1687f8;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        line-height: 1;
    }

    .shop-header__account-dropdown {
        display: flex;
        height: 100%;
        align-items: center;
    }

    .shop-header__profile {
        display: inline-flex;
        height: 100%;
        align-items: center;
        gap: .45rem;
        margin: 0;
        padding: 0;
        border: 0;
        background: transparent;
        color: #fff;
        font-family: inherit;
        font-size: .78rem;
        line-height: 1;
        vertical-align: middle;
    }

    .shop-header__profile.dropdown-toggle::after {
        display: inline-block;
        margin: 0 0 0 .15rem;
        vertical-align: middle;
    }

    .shop-header__avatar {
        display: block;
        width: 23px;
        height: 23px;
        flex: 0 0 23px;
        margin: 0;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, .7);
        border-radius: 50%;
        background: #e8f3ff;
        color: #526b80;
        font-size: 10px;
        font-weight: 700;
        line-height: 21px;
        object-fit: cover;
        text-align: center;
        vertical-align: middle;
    }

    .shop-header__avatar--fallback {
        background: #e7f0f8;
    }

    .shop-header__profile-name {
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .shop-header__profile-menu {
        min-width: 210px;
        margin-top: .55rem !important;
        border: 0;
        border-radius: 10px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .18);
    }

    .shop-header__main {
        display: grid;
        grid-template-columns: minmax(210px, 280px) minmax(280px, 1fr) auto;
        align-items: center;
        gap: 1.25rem;
        min-height: 64px;
        padding: .5rem 0;
    }

    .shop-header__logo {
        display: inline-flex;
        min-width: 0;
        align-items: center;
        gap: .65rem;
        color: #fff;
        text-decoration: none;
    }

    .shop-header__logo:hover {
        color: #fff;
    }

    .shop-header__logo-icon {
        display: grid;
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .55);
        border-radius: 11px;
        background: rgba(255, 255, 255, .14);
        font-size: 1.3rem;
    }

    .shop-header__logo-text {
        font-size: 1.02rem;
        font-weight: 750;
        line-height: 1.2;
    }

    .shop-header__search {
        position: relative;
        display: flex;
        width: 100%;
        min-width: 0;
        height: 40px;
        overflow: visible;
        border: 2px solid #fff;
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 70, 140, .12);
    }

    .shop-search-suggestions {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        left: 0;
        z-index: 1080;
        overflow: hidden;
        padding: 6px;
        color: #17243B;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, .16);
    }

    .shop-search-suggestions__item {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        padding: 9px;
        color: inherit;
        text-decoration: none;
        border-radius: 8px;
    }

    .shop-search-suggestions__item:hover,
    .shop-search-suggestions__item:focus-visible {
        color: inherit;
        background: #F0F7FF;
        outline: none;
    }

    .shop-search-suggestions__image {
        display: grid;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        overflow: hidden;
        place-items: center;
        color: #94A3B8;
        background: #F4F7FA;
        border-radius: 8px;
    }

    .shop-search-suggestions__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-search-suggestions__copy { min-width: 0; flex: 1; }
    .shop-search-suggestions__name,
    .shop-search-suggestions__meta {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .shop-search-suggestions__name { font-size: .84rem; font-weight: 650; }
    .shop-search-suggestions__meta { margin-top: 2px; color: #64748B; font-size: .74rem; }
    .shop-search-suggestions__price { flex: 0 0 auto; color: #EE4D2D; font-size: .82rem; font-weight: 700; }
    .shop-search-suggestions__state { padding: 14px 10px; color: #64748B; font-size: .84rem; }
    .shop-search-suggestions__all {
        display: block;
        margin-top: 4px;
        padding: 11px 10px 8px;
        color: #1A94FF;
        border-top: 1px solid #E2E8F0;
        font-size: .82rem;
        font-weight: 650;
        text-align: center;
        text-decoration: none;
    }
    .shop-search-suggestions__all:hover { color: #0877D1; }

    .shop-header__search input {
        width: 100%;
        min-width: 0;
        flex: 1 1 auto;
        padding: 0 .9rem;
        border: 0;
        outline: 0;
        color: #24364b;
        background: #fff;
    }

    .shop-header__search input:focus {
        box-shadow: none;
    }

    .shop-header__search button {
        display: grid;
        width: 46px;
        flex: 0 0 46px;
        place-items: center;
        border: 0;
        background: #087df1;
        color: #fff;
        font-size: 1.05rem;
    }

    .shop-header__search button:hover {
        background: #066bd0;
    }

    .shop-header__cart {
        display: inline-flex;
        min-width: 94px;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        color: #fff;
        font-size: .9rem;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
    }

    .shop-header__cart:hover {
        color: #fff;
        opacity: .82;
    }

    .shop-header__cart-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .shop-header__cart-icon i {
        font-size: 1.4rem;
    }

    .shop-header__cart-count {
        position: absolute;
        top: -9px;
        right: -12px;
        display: inline-flex;
        min-width: 18px;
        height: 18px;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid #20a9df;
        border-radius: 999px;
        background: #ee2b42;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .shop-header__nav {
        display: flex;
        min-height: 32px;
        align-items: center;
        gap: 1.8rem;
        overflow-x: auto;
        border-top: 1px solid rgba(255, 255, 255, .2);
        scrollbar-width: none;
    }

    .shop-header__nav::-webkit-scrollbar {
        display: none;
    }

    .shop-header__nav-link {
        position: relative;
        display: inline-flex;
        min-height: 32px;
        flex: 0 0 auto;
        align-items: center;
        color: rgba(255, 255, 255, .94);
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .shop-header__nav-link:hover,
    .shop-header__nav-link.is-active {
        color: #fff;
    }

    .shop-header__nav-link.is-active::after {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 2px;
        border-radius: 2px;
        background: #fff;
        content: "";
    }

    @media (max-width: 991.98px) {
        .shop-header__main {
            grid-template-columns: 38px minmax(0, 1fr) 38px;
            gap: .55rem;
            min-height: 58px;
            padding: .45rem 0;
        }

        .shop-header__logo {
            grid-column: 1;
            grid-row: 1;
            justify-content: center;
        }

        .shop-header__logo-icon {
            width: 34px;
            height: 34px;
            flex-basis: 34px;
            font-size: 1.1rem;
        }

        .shop-header__logo-text {
            display: none;
        }

        .shop-header__search {
            grid-column: 2;
            grid-row: 1;
            height: 38px;
        }

        .shop-header__cart {
            grid-column: 3;
            grid-row: 1;
            justify-self: end;
            min-width: 38px;
            width: 38px;
        }

        .shop-header__cart-label {
            display: none;
        }

        .shop-header__nav {
            gap: 1.25rem;
        }
    }

    @media (max-width: 575.98px) {
        .shop-header__utility-actions {
            gap: .85rem;
        }

        .shop-header__utility-link,
        .shop-header__profile {
            font-size: .73rem;
        }

        .shop-header__profile-name {
            max-width: 95px;
        }

        .shop-header__main {
            grid-template-columns: 34px minmax(0, 1fr) 34px;
            gap: .4rem;
        }

        .shop-header__logo-icon {
            width: 31px;
            height: 31px;
            flex-basis: 31px;
        }

        .shop-header__cart {
            min-width: 34px;
            width: 34px;
        }

        .shop-header__nav {
            gap: 1rem;
        }

        .shop-header__nav-link {
            font-size: .76rem;
        }
    }
</style>
