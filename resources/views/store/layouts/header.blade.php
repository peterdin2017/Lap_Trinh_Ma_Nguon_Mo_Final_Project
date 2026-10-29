
@php
    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->orderBy('category_name')
        ->limit(8)
        ->get(['category_id', 'category_name']);
@endphp

<header class="shop-header store-topbar">
    <div class="container">

        <div class="store-header-row">

            <a href="{{ route('store.home') }}"
               class="store-brand">

                <span class="store-brand-icon">
                    <i class="bi bi-shop-window"></i>
                </span>

                <span class="store-brand-title">
                    Website Bán hàng Online
                </span>
            </a>

            <form action="{{ route('store.products.index') }}"
                  method="GET"
                  class="store-header-search">

                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Tìm kiếm sản phẩm..."
                    aria-label="Tìm kiếm sản phẩm"
                >

                <button type="submit" aria-label="Tìm kiếm">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="store-header-actions">

                <a href="{{ route('store.cart.index') }}"
                   class="store-action">
                    <i class="bi bi-cart3"></i>
                    <span>Giỏ hàng</span>
                </a>

                @if(session('account_id'))

                    <div class="dropdown">
                        <button
                            type="button"
                            class="store-action dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="bi bi-person-circle"></i>
                            <span>Tài khoản</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('store.account.profile') }}">
                                    <i class="bi bi-person me-2"></i>
                                    Hồ sơ
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('store.account.orders.index') }}">
                                    <i class="bi bi-receipt me-2"></i>
                                    Đơn hàng
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form action="{{ route('logout') }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                @else

                    <a href="{{ route('login.form') }}"
                       class="store-action">
                        <i class="bi bi-person"></i>
                        <span>Tài khoản</span>
                    </a>

                @endif

            </div>
        </div>

        <nav class="store-header-nav"
             aria-label="Danh mục sản phẩm">

            @foreach($navCategories as $category)
                <a
                    href="{{ route('store.products.index', ['category' => $category->category_id]) }}"
                    class="store-nav-item {{ request('category') == $category->category_id ? 'active' : '' }}"
                >
                    {{ $category->category_name }}
                </a>
            @endforeach

        </nav>
    </div>
</header>
