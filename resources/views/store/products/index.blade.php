@extends('store.layouts.app')

@section('title', $filters['q'] !== ''
    ? 'Tìm kiếm: ' . $filters['q']
    : ($currentCategory?->category_name ?? 'Tất cả sản phẩm'))

@push('styles')
    <style>
        .product-list-page {
            --pl-primary: #1A94FF;
            --pl-primary-light: #EDF7FF;
            --pl-text: #17243B;
            --pl-muted: #64748B;
            --pl-border: #E2E8F0;
            --pl-bg: #F4F7FA;
            --pl-danger: #FF424E;
            color: var(--pl-text);
        }

        .product-list-page__layout {
            align-items: flex-start;
        }

        .product-list-page__panel,
        .product-list-page__topbar {
            background: #fff;
            border: 1px solid var(--pl-border);
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
        }

        .product-list-page__panel {
            position: sticky;
            top: 16px;
            padding: 18px;
        }

        .product-list-page__panel-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .product-list-page__label {
            display: block;
            margin-bottom: 7px;
            color: var(--pl-text);
            font-size: .82rem;
            font-weight: 600;
        }

        .product-list-page .form-control {
            min-height: 41px;
            border-color: var(--pl-border);
            border-radius: 8px;
            font-size: .875rem;
        }

        .product-list-page .form-control:focus {
            border-color: var(--pl-primary);
            box-shadow: 0 0 0 .2rem rgba(26, 148, 255, .13);
        }

        .product-list-page__price-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .product-list-page__price-tag {
            padding: 6px 9px;
            color: #475569;
            font-size: .75rem;
            text-decoration: none;
            background: #F8FAFC;
            border: 1px solid var(--pl-border);
            border-radius: 20px;
            transition: color .18s ease, background .18s ease, border-color .18s ease;
        }

        .product-list-page__price-tag:hover {
            color: var(--pl-primary);
            background: var(--pl-primary-light);
            border-color: #B9E0FF;
        }

        .product-list-page__divider {
            margin: 17px 0;
            border-color: var(--pl-border);
            opacity: 1;
        }

        .product-list-page__category-list {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .product-list-page__category-list::-webkit-scrollbar {
            width: 4px;
        }

        .product-list-page__category-list::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 8px;
        }

        .product-list-page__category-list a {
            display: block;
            padding: 6px 8px;
            color: #475569;
            font-size: .82rem;
            line-height: 1.4;
            text-decoration: none;
            border-radius: 6px;
            transition: color .18s ease, background .18s ease;
        }

        .product-list-page__category-list a:hover,
        .product-list-page__category-list a.is-active {
            color: var(--pl-primary);
            background: var(--pl-primary-light);
        }

        .product-list-page__category-list .category-children {
            margin: 3px 0 5px 10px;
            padding-left: 8px;
            border-left: 1px solid var(--pl-border);
        }

        .product-list-page__apply {
            color: #fff;
            font-size: .875rem;
            font-weight: 700;
            background: var(--pl-primary);
            border-color: var(--pl-primary);
            border-radius: 8px;
        }

        .product-list-page__apply:hover,
        .product-list-page__apply:focus {
            color: #fff;
            background: #087FE0;
            border-color: #087FE0;
        }

        .product-list-page__clear {
            font-size: .875rem;
            border-radius: 8px;
        }

        .product-list-page__topbar {
            margin-bottom: 16px;
            padding: 17px 18px;
        }

        .product-list-page__heading {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 15px;
        }

        .product-list-page__title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 750;
        }

        .product-list-page__count {
            color: var(--pl-muted);
            font-size: .82rem;
            white-space: nowrap;
        }

        .product-list-page__sorts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .product-list-page__sort {
            padding: 7px 11px;
            color: #475569;
            font-size: .8rem;
            text-decoration: none;
            background: #F8FAFC;
            border: 1px solid var(--pl-border);
            border-radius: 7px;
            transition: color .18s ease, background .18s ease, border-color .18s ease;
        }

        .product-list-page__sort:hover,
        .product-list-page__sort.is-active {
            color: var(--pl-primary);
            background: var(--pl-primary-light);
            border-color: #B9E0FF;
        }

        .product-list-page__grid > .col {
            min-width: 0;
        }

        .product-list-page .product-card {
            height: 100%;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--pl-border) !important;
            border-radius: 12px;
            box-shadow: 0 2px 7px rgba(15, 23, 42, .035) !important;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .product-list-page .product-card:hover {
            box-shadow: 0 10px 22px rgba(15, 23, 42, .1) !important;
            transform: translateY(-4px);
        }

        .product-list-page .product-card__image-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            padding: 12px;
            background: #fff;
        }

        .product-list-page .product-card__image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-list-page .product-card__discount {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 7px;
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            background: var(--pl-danger);
            border-radius: 5px;
        }

        .product-list-page .product-card__body {
            display: flex;
            min-height: 178px;
            flex-direction: column;
            padding: 13px;
        }

        .product-list-page .product-card__category {
            margin-bottom: 5px;
            color: var(--pl-muted);
            font-size: .72rem;
        }

        .product-list-page .product-card__name {
            display: -webkit-box;
            min-height: 2.65em;
            overflow: hidden;
            margin: 0 0 7px;
            color: var(--pl-text);
            font-size: .86rem;
            font-weight: 600;
            line-height: 1.35;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .product-list-page .product-card__seller {
            overflow: hidden;
            margin-bottom: 8px;
            color: var(--pl-muted);
            font-size: .73rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-list-page .product-card__seller i {
            color: var(--pl-primary);
        }

        .product-list-page .product-card__rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: auto;
            color: #475569;
            font-size: .73rem;
        }

        .product-list-page .product-card__rating .bi-star-fill {
            color: #F59E0B;
        }

        .product-list-page .product-card__sold {
            margin-left: auto;
            color: var(--pl-muted);
            white-space: nowrap;
        }

        .product-list-page .product-card__prices {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 7px;
            margin-top: 7px;
        }

        .product-list-page .product-card__price {
            color: var(--pl-danger);
            font-size: 1rem;
            font-weight: 750;
        }

        .product-list-page .product-card__old-price {
            color: #94A3B8;
            font-size: .75rem;
            text-decoration: line-through;
        }

        .product-list-page__empty {
            padding: 48px 20px;
            color: var(--pl-muted);
            text-align: center;
            background: #fff;
            border: 1px dashed #CBD5E1;
            border-radius: 12px;
        }

        .product-list-page__empty i {
            display: block;
            margin-bottom: 10px;
            color: #94A3B8;
            font-size: 2rem;
        }

        .product-list-page__pagination {
            margin-top: 26px;
            padding: 15px 18px;
            border: 1px solid var(--pl-border);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(25, 55, 95, .045);
        }

        @media (max-width: 991.98px) {
            .product-list-page__panel {
                position: static;
            }
        }

        @media (max-width: 575.98px) {
            .product-list-page__panel,
            .product-list-page__topbar {
                padding: 14px;
            }

            .product-list-page__heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .product-list-page__sorts {
                gap: 6px;
            }

            .product-list-page__sort {
                padding: 6px 8px;
                font-size: .73rem;
            }

            .product-list-page .product-card__body {
                min-height: 165px;
                padding: 10px;
            }

            .product-list-page .product-card__rating {
                flex-wrap: wrap;
                font-size: .68rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .product-list-page *,
            .product-list-page *::before,
            .product-list-page *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $currentQuery = $filters['q'] ?? '';
        $currentMinPrice = $filters['min_price'] ?? '';
        $currentMaxPrice = $filters['max_price'] ?? '';
        $currentSort = $filters['sort'] ?? 'newest';

        $priceRanges = [
            ['label' => 'Dưới 5 triệu', 'min' => null, 'max' => 5000000],
            ['label' => '5 - 10 triệu', 'min' => 5000000, 'max' => 10000000],
            ['label' => 'Trên 10 triệu', 'min' => 10000000, 'max' => null],
        ];
    @endphp

    <main class="product-list-page">
        <div class="container py-4">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="row g-4 product-list-page__layout">
                <aside class="col-12 col-lg-3" aria-label="Bộ lọc sản phẩm">
                    <div class="product-list-page__panel">
                        <h2 class="product-list-page__panel-title mb-3">
                            <i class="bi bi-funnel me-2 text-primary" aria-hidden="true"></i>
                            Tìm kiếm và lọc
                        </h2>

                        <form method="GET" action="{{ route('store.products.index') }}">
                            <div class="mb-3">
                                <label for="product-filter-q" class="product-list-page__label">
                                    Từ khóa
                                </label>
                                <input
                                    id="product-filter-q"
                                    type="search"
                                    name="q"
                                    value="{{ $currentQuery }}"
                                    maxlength="100"
                                    class="form-control"
                                    placeholder="Tên sản phẩm, thương hiệu"
                                >
                            </div>

                            <div class="mb-2">
                                <span class="product-list-page__label">Khoảng giá</span>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="product-filter-min" class="visually-hidden">Giá từ</label>
                                        <input
                                            id="product-filter-min"
                                            type="number"
                                            name="min_price"
                                            value="{{ $currentMinPrice }}"
                                            min="0"
                                            class="form-control"
                                            placeholder="Từ"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <label for="product-filter-max" class="visually-hidden">Giá đến</label>
                                        <input
                                            id="product-filter-max"
                                            type="number"
                                            name="max_price"
                                            value="{{ $currentMaxPrice }}"
                                            min="0"
                                            class="form-control"
                                            placeholder="Đến"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="product-list-page__price-tags mt-2">
                                @foreach ($priceRanges as $range)
                                    @php
                                        $rangeQuery = request()->except(['min_price', 'max_price', 'page']);
                                        if ($range['min'] !== null) {
                                            $rangeQuery['min_price'] = $range['min'];
                                        }
                                        if ($range['max'] !== null) {
                                            $rangeQuery['max_price'] = $range['max'];
                                        }
                                    @endphp
                                    <a
                                        class="product-list-page__price-tag"
                                        href="{{ route('store.products.index', $rangeQuery) }}"
                                    >
                                        {{ $range['label'] }}
                                    </a>
                                @endforeach
                            </div>

                            @if (!empty($filters['category']))
                                <input type="hidden" name="category" value="{{ $filters['category'] }}">
                            @endif

                            <hr class="product-list-page__divider">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h3 class="product-list-page__label mb-0">Danh mục</h3>
                                <button
                                    class="btn btn-sm btn-light"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#product-category-filter"
                                    aria-expanded="true"
                                    aria-controls="product-category-filter"
                                    aria-label="Thu gọn danh mục"
                                >
                                    <i class="bi bi-chevron-up" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="collapse show" id="product-category-filter">
                                <nav class="product-list-page__category-list" aria-label="Danh mục sản phẩm">
                                    <a
                                        href="{{ route('store.products.index', request()->except(['category', 'page'])) }}"
                                        class="{{ empty($filters['category']) ? 'is-active fw-semibold' : '' }}"
                                    >
                                        Tất cả sản phẩm
                                    </a>

                                    @include('store.products._category-tree', [
                                        'nodes' => $categoryTree,
                                        'activeId' => $currentCategory?->category_id,
                                    ])
                                </nav>
                            </div>

                            <button type="submit" class="btn product-list-page__apply w-100 mt-3">
                                <i class="bi bi-sliders me-1" aria-hidden="true"></i>
                                Áp dụng bộ lọc
                            </button>

                            <a
                                href="{{ route('store.products.index') }}"
                                class="btn btn-outline-secondary product-list-page__clear w-100 mt-2"
                            >
                                Xóa bộ lọc
                            </a>
                        </form>
                    </div>
                </aside>

                <section class="col-12 col-lg-9" aria-labelledby="product-list-title">
                    <div class="product-list-page__topbar">
                        <div class="product-list-page__heading">
                            <h1 id="product-list-title" class="product-list-page__title">
                                {{ $currentQuery !== '' ? 'Kết quả tìm kiếm' : ($currentCategory?->category_name ?? 'Tất cả sản phẩm') }}
                            </h1>
                            <span class="product-list-page__count">
                                {{ number_format($products->total()) }} sản phẩm
                            </span>
                        </div>

                        <nav class="product-list-page__sorts" aria-label="Sắp xếp sản phẩm">
                            @foreach ([
                                'newest' => 'Mới nhất',
                                'best_selling' => 'Bán chạy',
                                'price_asc' => 'Giá: Thấp đến Cao',
                                'price_desc' => 'Giá: Cao đến Thấp',
                            ] as $sortValue => $sortLabel)
                                <a
                                    href="{{ route('store.products.index', array_merge(request()->except(['sort', 'page']), ['sort' => $sortValue])) }}"
                                    class="product-list-page__sort {{ $currentSort === $sortValue ? 'is-active' : '' }}"
                                    @if ($currentSort === $sortValue) aria-current="true" @endif
                                >
                                    {{ $sortLabel }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    @if ($products->isEmpty())
                        <div class="product-list-page__empty">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <strong class="d-block text-dark mb-1">Không tìm thấy sản phẩm</strong>
                            <span>Thử đổi từ khóa hoặc điều chỉnh bộ lọc.</span>
                        </div>
                    @else
                        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3 product-list-page__grid">
                            @foreach ($products as $product)
                                <div class="col">
                                    @php
                                        $oldPrice = $product->original_price ?? $product->compare_at_price ?? null;
                                        $discountPercent = $oldPrice && $oldPrice > $product->price
                                            ? (int) round((($oldPrice - $product->price) / $oldPrice) * 100)
                                            : 0;
                                    @endphp

                                    <a
                                        href="{{ route('store.products.show', ['slug' => $product->slug ?: $product->product_id]) }}"
                                        class="text-decoration-none text-dark d-block h-100"
                                    >
                                        <article class="card product-card">
                                            <div class="product-card__image-wrap">
                                                @if ($product->image_url)
                                                    <img
                                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                                                        alt="{{ $product->product_name }}"
                                                        class="product-card__image"
                                                        loading="lazy"
                                                    >
                                                @else
                                                    <div class="d-flex h-100 flex-column align-items-center justify-content-center text-secondary">
                                                        <i class="bi bi-image fs-2" aria-hidden="true"></i>
                                                        <span class="small">Chưa có ảnh</span>
                                                    </div>
                                                @endif

                                                @if ($discountPercent > 0)
                                                    <span class="product-card__discount">-{{ $discountPercent }}%</span>
                                                @endif

                                                @if ($product->stock_quantity < 1)
                                                    <span class="badge text-bg-secondary position-absolute top-0 end-0 m-2">
                                                        Hết hàng
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="product-card__body">
                                                <div class="product-card__category">
                                                    {{ $product->category?->category_name ?? 'Chưa phân loại' }}
                                                </div>

                                                <h2 class="product-card__name">
                                                    {{ $product->product_name }}
                                                </h2>

                                                <div class="product-card__seller">
                                                    <i class="bi bi-shop me-1" aria-hidden="true"></i>
                                                    {{ $product->seller?->shop_name ?? 'Cửa hàng' }}
                                                </div>

                                                <div class="product-card__rating">
                                                    @if ((int) $product->rating_count > 0)
                                                        <i class="bi bi-star-fill" aria-hidden="true"></i>
                                                        <span>{{ number_format((float) $product->rating_avg, 1) }}</span>
                                                        <span class="text-secondary">
                                                            ({{ number_format((int) $product->rating_count) }})
                                                        </span>
                                                    @else
                                                        <span class="text-secondary small">Chưa có đánh giá</span>
                                                    @endif

                                                    <span class="product-card__sold">
                                                        Đã bán {{ number_format((int) $product->sold_count) }}
                                                    </span>
                                                </div>

                                                <div class="product-card__prices">
                                                    <span class="product-card__price">
                                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                                    </span>

                                                    @if ($discountPercent > 0)
                                                        <span class="product-card__old-price">
                                                            {{ number_format($oldPrice, 0, ',', '.') }}₫
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </article>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    @endif
                    <div class="product-list-page__pagination">
                        {{ $products->withQueryString()->links() }}
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
