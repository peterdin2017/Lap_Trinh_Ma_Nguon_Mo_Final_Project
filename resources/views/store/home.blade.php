@extends('store.layouts.app')

@section('title', 'Cửa hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/store/css/home.css') }}">
@endpush

@section('content')
    @php
        $featuredCategories = $categories
            ->whereNull('parent_id')
            ->take(8);

        $categoryIcons = [
            'điện thoại' => 'bi-phone',
            'máy tính' => 'bi-laptop',
            'laptop' => 'bi-laptop',
            'điện tử' => 'bi-camera',
            'nhà cửa' => 'bi-house',
            'thời trang' => 'bi-handbag',
            'mẹ & bé' => 'bi-balloon-heart',
            'sắc đẹp' => 'bi-stars',
            'thể thao' => 'bi-bicycle',
            'sách' => 'bi-book',
            'thực phẩm' => 'bi-cup-hot',
        ];
    @endphp

    <div class="home-page">
        <div class="container py-4 py-lg-5">
            <section class="home-hero" aria-labelledby="home-hero-title">
                <div class="home-hero__content">
                    <span class="home-hero__eyebrow">Mua sắm trực tuyến</span>

                    <h1 id="home-hero-title" class="home-hero__title">
                        Tìm món đồ yêu thích của bạn
                    </h1>

                    <p class="home-hero__description">
                        Khám phá sản phẩm từ nhiều cửa hàng, chọn món phù hợp
                        và đặt mua dễ dàng.
                    </p>

                    <a href="{{ route('store.products.index') }}" class="btn home-hero__button">
                        Mua sắm ngay
                        <i class="bi bi-arrow-right ms-2" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="home-hero__art" aria-hidden="true">
                    <i class="bi bi-bag-heart"></i>
                </div>
            </section>

            <section class="home-policy-bar" aria-label="Thông tin dịch vụ">
                <div class="row g-0">
                    <div class="col-6 col-md-3">
                        <div class="home-policy-item">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                            <span>
                                <strong>Bảo vệ người mua</strong>
                                <small>Mua sắm an tâm</small>
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="home-policy-item">
                            <i class="bi bi-truck" aria-hidden="true"></i>
                            <span>
                                <strong>Giao hàng tiện lợi</strong>
                                <small>Phí ship hiển thị khi thanh toán</small>
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="home-policy-item">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            <span>
                                <strong>Hỗ trợ đổi trả</strong>
                                <small>Liên hệ cửa hàng để được hỗ trợ</small>
                            </span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="home-policy-item">
                            <i class="bi bi-credit-card" aria-hidden="true"></i>
                            <span>
                                <strong>Nhiều cách thanh toán</strong>
                                <small>Chọn phương thức khi đặt hàng</small>
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            @if ($featuredCategories->isNotEmpty())
                <section class="home-section" aria-labelledby="featured-categories-title">
                    <div class="home-section__heading">
                        <div>
                            <span class="home-section__eyebrow">Khám phá</span>
                            <h2 id="featured-categories-title" class="home-section__title">
                                Danh mục nổi bật
                            </h2>
                        </div>

                        <a href="{{ route('store.products.index') }}" class="home-section__more">
                            Xem tất cả
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="row row-cols-2 row-cols-sm-4 row-cols-lg-6 g-3">
                        @foreach ($featuredCategories as $category)
                            @php
                                $categoryName = mb_strtolower($category->category_name);
                                $categoryIcon = 'bi-grid';

                                foreach ($categoryIcons as $keyword => $icon) {
                                    if (str_contains($categoryName, $keyword)) {
                                        $categoryIcon = $icon;
                                        break;
                                    }
                                }
                            @endphp

                            <div class="col">
                                <a
                                    href="{{ route('store.products.index', ['category' => $category->category_id]) }}"
                                    class="home-category-card"
                                >
                                    <span class="home-category-card__icon">
                                        <i class="bi {{ $categoryIcon }}" aria-hidden="true"></i>
                                    </span>

                                    <span class="home-category-card__name">
                                        {{ $category->category_name }}
                                    </span>

                                    @if (($category->products_count ?? 0) > 0)
                                        <span class="home-category-card__count">
                                            {{ number_format($category->products_count) }} sản phẩm
                                        </span>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="home-section home-products" aria-labelledby="showcase-title">
                <div class="home-section__heading home-products__heading">
                    <div>
                        <span class="home-section__eyebrow">Dành cho bạn</span>
                        <h2 id="showcase-title" class="home-section__title">
                            Sản phẩm gợi ý
                        </h2>
                    </div>

                    <nav class="home-product-tabs" aria-label="Lọc sản phẩm">
                        <a
                            href="{{ route('store.products.index') }}"
                            class="home-product-tabs__link"
                        >
                            Tất cả
                        </a>
                        <a
                            href="{{ route('store.products.index', ['sort' => 'newest']) }}"
                            class="home-product-tabs__link is-active"
                            aria-current="page"
                        >
                            Sản phẩm mới
                        </a>
                        <a
                            href="{{ route('store.products.index', ['sort' => 'best_selling']) }}"
                            class="home-product-tabs__link"
                        >
                            Bán chạy
                        </a>
                    </nav>
                </div>

                @if ($products->isEmpty())
                    <div class="home-empty-state">
                        <i class="bi bi-basket" aria-hidden="true"></i>
                        <h3>Chưa có sản phẩm để hiển thị</h3>
                        <p>Sản phẩm sẽ xuất hiện tại đây khi cửa hàng có dữ liệu.</p>
                    </div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                        @foreach ($products as $product)
                            @php
                                $hasVariants = $product->variants->isNotEmpty();
                                $isNew = $product->created_at
                                    && $product->created_at->gt(now()->subDays(7));
                                $rating = min(5, max(0, (float) ($product->rating_avg ?? 0)));
                                $ratingCount = (int) ($product->rating_count ?? 0);
                            @endphp

                            <div class="col">
                                <article class="home-product-card">
                                    <a
                                        href="{{ route('store.products.show', ['slug' => $product->slug ?: $product->product_id]) }}"
                                        class="home-product-card__image-link"
                                        aria-label="Xem {{ $product->product_name }}"
                                    >
                                        <div class="home-product-card__image-wrap">
                                            @if ($product->image_url)
                                                <img
                                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                                                    alt="{{ $product->product_name }}"
                                                    class="home-product-card__image"
                                                    loading="lazy"
                                                >
                                            @else
                                                <span class="home-product-card__image-placeholder">
                                                    <i class="bi bi-image" aria-hidden="true"></i>
                                                    <span>Chưa có ảnh</span>
                                                </span>
                                            @endif

                                            @if ($isNew)
                                                <span class="home-product-card__badge">Mới</span>
                                            @elseif ($product->sold_count > 0)
                                                <span class="home-product-card__badge home-product-card__badge--popular">
                                                    Bán chạy
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                    <div class="home-product-card__body">
                                        <div class="home-product-card__category">
                                            {{ $product->category?->category_name ?? 'Chưa phân loại' }}
                                        </div>

                                        <h3 class="home-product-card__title">
                                            <a href="{{ route('store.products.show', ['slug' => $product->slug ?: $product->product_id]) }}">
                                                {{ $product->product_name }}
                                            </a>
                                        </h3>

                                        <div
                                            class="home-product-card__rating"
                                            aria-label="Đánh giá {{ number_format($rating, 1) }} trên 5"
                                        >
                                            <span class="home-product-card__stars" aria-hidden="true">
                                                @for ($star = 1; $star <= 5; $star++)
                                                    @php
                                                        $starValue = $rating - ($star - 1);
                                                    @endphp

                                                    @if ($starValue >= 0.75)
                                                        <i class="bi bi-star-fill"></i>
                                                    @elseif ($starValue >= 0.25)
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </span>

                                            @if ($ratingCount > 0)
                                                <span>({{ number_format($ratingCount) }})</span>
                                            @else
                                                <span>Chưa có đánh giá</span>
                                            @endif
                                        </div>

                                        <div class="home-product-card__shop">
                                            <i class="bi bi-shop" aria-hidden="true"></i>
                                            <span>{{ $product->seller?->shop_name ?? 'Cửa hàng' }}</span>
                                        </div>

                                        <div class="home-product-card__footer">
                                            <span class="home-product-card__price">
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            </span>

                                            @if ($product->sold_count > 0)
                                                <span class="home-product-card__sold">
                                                    Đã bán {{ number_format($product->sold_count) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if ($hasVariants)
                                            <a
                                                href="{{ route('store.products.show', ['slug' => $product->slug ?: $product->product_id]) }}"
                                                class="btn home-product-card__add"
                                            >
                                                Chọn phân loại
                                            </a>
                                        @else
                                            <form
                                                method="POST"
                                                action="{{ route('store.cart.items.store') }}"
                                                data-cart-ajax-form
                                            >
                                                @csrf
                                                <input
                                                    type="hidden"
                                                    name="product_id"
                                                    value="{{ $product->product_id }}"
                                                >
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="intent" value="cart">

                                                <button
                                                    type="submit"
                                                    class="btn home-product-card__add"
                                                    @disabled($product->stock_quantity < 1)
                                                >
                                                    {{ $product->stock_quantity < 1 ? 'Hết hàng' : 'Thêm vào giỏ' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    @if ($products->hasPages())
                        <nav
                            class="d-flex justify-content-center mt-4"
                            aria-label="Phân trang sản phẩm"
                        >
                            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    @endif
                @endif
            </section>
        </div>
    </div>
@endsection
