@extends('store.layouts.app')

@section('title', 'Cửa hàng')

@section('content')
    <div class="container py-4">
        <section class="retail-hero retail-hero--orange">
            <div>
                <div class="eyebrow">Mua sắm trực tuyến</div>
                <h1>Khám phá sản phẩm dành cho bạn</h1>
                <p>
                    Thời trang, điện thoại, laptop và nhiều danh mục
                    trong cùng một cửa hàng.
                </p>
            </div>

            <i class="bi bi-bag-heart retail-hero__icon" aria-hidden="true"></i>
        </section>

        @php
            $homeCategories = $categories
                ->whereNull('parent_id')
                ->take(8);
        @endphp

        @if ($homeCategories->isNotEmpty())
            <section class="mb-4" aria-labelledby="home-categories-title">
                <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                    <h2 id="home-categories-title" class="h5 fw-bold mb-0">
                        Danh mục nổi bật
                    </h2>

                    <a
                        href="{{ route('store.products.index') }}"
                        class="small text-decoration-none"
                    >
                        Xem tất cả sản phẩm
                    </a>
                </div>

                <div class="row row-cols-2 row-cols-md-4 g-2">
                    @foreach ($homeCategories as $category)
                        <div class="col">
                            <a
                                href="{{ route('store.products.index', ['category' => $category->category_id]) }}"
                                class="btn btn-outline-primary w-100 d-flex justify-content-between align-items-center gap-2"
                            >
                                <span class="text-truncate">
                                    {{ $category->category_name }}
                                </span>

                                @if (isset($category->products_count))
                                    <span class="badge text-bg-light flex-shrink-0">
                                        {{ $category->products_count }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section aria-labelledby="home-products-title">
            <div class="store-toolbar p-3 mb-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <h2 id="home-products-title" class="h5 fw-bold mb-0">
                        Sản phẩm mới nhất
                    </h2>

                    <span class="small text-secondary">
                        {{ number_format($products->total()) }} sản phẩm
                    </span>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="store-empty-state p-5">
                    <i class="bi bi-basket" aria-hidden="true"></i>
                    <strong>Chưa có sản phẩm để hiển thị</strong>
                    <span class="empty-note">
                        Sản phẩm sẽ xuất hiện tại đây khi cửa hàng có dữ liệu.
                        Bạn có thể quay lại sau.
                    </span>
                </div>
            @else
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @foreach ($products as $product)
                        <div class="col">
                            @include('store.partials.product-card', [
                                'product' => $product,
                            ])
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
@endsection
