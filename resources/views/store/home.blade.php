@extends('store.layouts.app')

@section('title', 'Cửa hàng')

@section('content')
    <main class="container py-4">
        <div class="retail-hero retail-hero--orange">
            <div>
                <div class="eyebrow">Mua sắm trực tuyến</div>
                <h2>Khám phá sản phẩm dành cho bạn</h2>
                <p>Thời trang, điện thoại, laptop và nhiều danh mục trong cùng một cửa hàng.</p>
            </div>
            <i class="bi bi-bag-heart retail-hero__icon" aria-hidden="true"></i>
        </div>
        <div class="row g-3 g-lg-4">
            {{-- Sidebar bộ lọc --}}
            <aside class="col-12 col-lg-3">
                @include('store.partials.sidebar')
            </aside>

            {{-- Toolbar, lưới sản phẩm và phân trang --}}
            <section class="col-12 col-lg-9" aria-labelledby="store-title">
                <div class="store-toolbar p-3 mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h1 id="store-title" class="h5 fw-bold mb-1">
                                Sản phẩm nổi bật
                            </h1>
                            <span class="small text-secondary">
                                {{ $products->total() }} sản phẩm
                            </span>
                        </div>

                        <nav class="store-sort d-flex flex-wrap gap-2"
                             aria-label="Sắp xếp sản phẩm">
                            <a href="?sort=popular"
                               class="store-sort__link {{ request('sort', 'popular') === 'popular' ? 'is-active' : '' }}">
                                Phổ biến
                            </a>

                            <a href="?sort=best_selling"
                               class="store-sort__link {{ request('sort') === 'best_selling' ? 'is-active' : '' }}">
                                Bán chạy
                            </a>

                            <a href="?sort=newest"
                               class="store-sort__link {{ request('sort') === 'newest' ? 'is-active' : '' }}">
                                Hàng mới
                            </a>

                            <a href="?sort=price"
                               class="store-sort__link {{ request('sort') === 'price' ? 'is-active' : '' }}">
                                Giá
                            </a>
                        </nav>
                    </div>
                </div>

                {{-- Responsive: 2 cột mobile, 3 tablet, 4 desktop --}}
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @forelse ($products as $product)
                        <div class="col">
                            @include('store.partials.product-card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="store-empty-state p-5 text-center text-secondary">
                                <i class="bi bi-basket" aria-hidden="true"></i>
                                <strong>Chưa có sản phẩm để hiển thị</strong>
                                <span class="empty-note">Sản phẩm sẽ xuất hiện tại đây khi cửa hàng có dữ liệu. Bạn có thể quay lại sau.</span>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Phân trang Bootstrap --}}
                @if ($products->hasPages())
                    <nav class="d-flex justify-content-center mt-4" aria-label="Phân trang">
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                    </nav>
                @endif
            </section>
        </div>
    </main>

    <style>
        .store-toolbar,
        .store-empty-state {
            background: var(--shop-card-bg, #FFFFFF);
            border: 1px solid var(--shop-border, #E2E8F0);
            border-radius: 12px;
            box-shadow: var(--shop-shadow, 0 2px 8px rgba(0, 0, 0, .04));
        }

        .store-sort__link {
            padding: .5rem .75rem;
            color: var(--shop-text-muted, #64748B);
            text-decoration: none;
            border-radius: 8px;
            transition: color .15s ease, background-color .15s ease;
        }

        .store-sort__link:hover,
        .store-sort__link:focus-visible,
        .store-sort__link.is-active {
            color: var(--shop-primary, #1A94FF);
            background: var(--shop-primary-light, #EBF5FF);
        }

        .store-sort__link.is-active {
            font-weight: 600;
        }

        @media (prefers-reduced-motion: reduce) {
            .store-sort__link {
                transition: none;
            }
        }
    </style>
@endsection
