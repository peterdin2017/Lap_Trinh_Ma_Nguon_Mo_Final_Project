@php
    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->orderBy('category_name')
        ->limit(8)
        ->get(['category_id', 'category_name']);
@endphp

<header class="shop-header text-white">
    <div class="container py-3">
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
                        <input id="store-search" name="q" type="search"
                            value="{{ request()->routeIs('store.products.index') ? request('q') : '' }}" maxlength="100"
                            class="form-control" placeholder="Tìm kiếm sản phẩm...">

                        <button class="btn shop-header__search-button" type="submit" aria-label="Tìm kiếm">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-5 col-lg-3 order-2 order-lg-3">
                <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3">
                    <a href="{{ route('store.cart.index') }}" class="shop-header__action btn" aria-label="Giỏ hàng">
                        <i class="bi bi-cart3" aria-hidden="true"></i>
                        <span>Giỏ hàng</span>
                    </a>

                    @if (session('account_id'))
                        <div class="dropdown">
                            <button class="shop-header__action btn dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person" aria-hidden="true"></i>
                                <span>Tài khoản</span>
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
                                    <a class="dropdown-item" href="{{ route('store.account.orders.index') }}">
                                        <i class="bi bi-receipt me-2" aria-hidden="true"></i>
                                        Đơn hàng của tôi
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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
                        <a href="{{ route('login.form') }}" class="shop-header__action btn" aria-label="Đăng nhập">
                            <i class="bi bi-person" aria-hidden="true"></i>
                            <span>Tài khoản</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <nav class="shop-header__nav d-flex gap-4 gap-lg-5 mt-3 pt-2 overflow-x-auto" aria-label="Danh mục sản phẩm">
            @foreach ($navCategories as $navCategory)
                <a href="{{ route('store.products.index', ['category' => $navCategory->category_id]) }}"
                    class="shop-header__nav-link text-nowrap">
                    {{ $navCategory->category_name }}
                </a>
            @endforeach
        </nav>
    </div>
</header>
