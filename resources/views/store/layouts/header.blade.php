@php
    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->orderBy('category_name')
        ->limit(8)
        ->get(['category_id', 'category_name']);
@endphp


<header class="shop-header text-white">
    <div class="container py-3">
        {{-- Logo, tìm kiếm và thao tác tài khoản --}}
        <div class="row align-items-center g-3">
            <div class="col-7 col-lg-3">
                <a href="{{ route('store.home') }}"
                   class="shop-header__logo d-inline-flex align-items-center gap-2 text-white text-decoration-none fw-bold">
                    <i class="bi bi-shop-window fs-3" aria-hidden="true"></i>
                    <span class="fs-5">Website Bán hàng Online</span>
                </a>
            </div>

            <div class="col-12 col-lg-6 order-3 order-lg-2">
                <form action="{{ route('store.products.index') }}" method="GET" role="search">
                    <label for="store-search" class="visually-hidden">
                        Tìm kiếm sản phẩm
                    </label>

                    <div class="input-group shop-header__search">
                        <input id="store-search"
                               name="q"
                               type="search"
                               value="{{ request()->routeIs('store.products.index') ? request('q') : '' }}"
                               maxlength="100"
                               class="form-control"
                               placeholder="Tìm kiếm sản phẩm...">

                        <button class="btn shop-header__search-button"
                                type="submit"
                                aria-label="Tìm kiếm">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Giỏ hàng và tài khoản --}}
            <div class="col-5 col-lg-3 order-2 order-lg-3">
                <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3">
                    <a href="{{ route('store.cart.index') }}"
                       class="shop-header__action btn">
                        <i class="bi bi-cart3" aria-hidden="true"></i>
                        <span>Giỏ hàng</span>
                    </a>

                    @if (session('account_id'))
                        <div class="dropdown">
                            <button class="shop-header__action btn dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="bi bi-person" aria-hidden="true"></i>
                                <span>Tài khoản</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('store.account.profile') }}">
                                        <i class="bi bi-person me-2" aria-hidden="true"></i>
                                        Hồ sơ của tôi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('store.account.addresses.index') }}">
                                        <i class="bi bi-geo-alt me-2" aria-hidden="true"></i>
                                        Địa chỉ nhận hàng
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('store.account.password') }}">
                                        <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>
                                        Đổi mật khẩu
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
                        <a href="{{ route('login.form') }}"
                           class="shop-header__action btn">
                            <i class="bi bi-person" aria-hidden="true"></i>
                            <span>Tài khoản</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Điều hướng danh mục --}}
        <nav class="shop-header__nav d-flex gap-4 gap-lg-5 mt-3 pt-2 overflow-x-auto"
             aria-label="Danh mục sản phẩm">
            @foreach ($navCategories as $navCategory)
                <a href="{{ route('store.products.index', ['category' => $navCategory->category_id]) }}"
                   class="shop-header__nav-link text-nowrap">{{ $navCategory->category_name }}</a>
            @endforeach
        </nav>
    </div>
</header>

<style>
    .shop-header {
        position: sticky;
        top: 0;
        z-index: 1020;
        background: linear-gradient(135deg, #1A94FF 0%, #0077E6 100%);
        box-shadow: 0 2px 8px rgba(15, 23, 42, .12);
    }

    .shop-header__search {
        min-height: 44px;
        overflow: hidden;
        background: #fff;
        border: 0;
        border-radius: 8px;
    }

    .shop-header__search .form-control {
        min-width: 0;
        min-height: 44px;
        padding-left: 1rem;
        border: 0;
        box-shadow: none;
    }

    .shop-header__search-button {
        min-width: 52px;
        color: var(--shop-primary);
        background: var(--shop-primary-light);
        border: 0;
    }

    .shop-header__search-button:hover,
    .shop-header__search-button:focus-visible {
        color: var(--shop-primary-hover);
        background: #D9EDFF;
    }

    .shop-header__action {
        display: inline-flex;
        min-height: 40px;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        padding-inline: .9rem;
        color: #fff;
        white-space: nowrap;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 999px;
        transition: background-color .15s ease, border-color .15s ease;
    }

    .shop-header__action:hover,
    .shop-header__action:focus-visible {
        color: #fff;
        background: rgba(255, 255, 255, .2);
        border-color: rgba(255, 255, 255, .45);
    }

    .shop-header .dropdown-menu {
        margin-top: .5rem;
        border: 1px solid var(--shop-border);
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .12);
    }

    .shop-header__nav {
        scrollbar-width: thin;
    }

    .shop-header__nav-link {
        padding-bottom: .3rem;
        color: rgba(255, 255, 255, .92);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: color .15s ease, border-color .15s ease;
    }

    .shop-header__nav-link:hover,
    .shop-header__nav-link:focus-visible {
        color: #fff;
        border-bottom-color: #fff;
    }

    @media (max-width: 575.98px) {
        .shop-header__logo span {
            font-size: .95rem !important;
        }

        .shop-header__action {
            gap: .3rem;
            padding-inline: .55rem;
            font-size: .875rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .shop-header__action,
        .shop-header__nav-link {
            transition: none;
        }
    }
</style>
