@extends('store.layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <main class="container py-4">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Sản phẩm nổi bật</h1>
            <p class="text-secondary mb-0">
                Khám phá các sản phẩm từ cửa hàng đang hoạt động.
            </p>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
            @forelse ($products as $product)
                <div class="col">
                    <a
                        href="{{ route('store.products.show', $product->product_id) }}"
                        class="text-decoration-none text-dark d-block h-100"
                    >
                        <article class="card h-100 border-0 shadow-sm product-card">
                            <div class="ratio ratio-1x1 bg-light overflow-hidden">
                                @if ($product->image_url)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-100 h-100 object-fit-contain p-2"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-secondary">
                                        <i class="bi bi-image fs-2"></i>
                                        <span class="small">Chưa có ảnh</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <div class="small text-secondary mb-2">
                                    {{ $product->category?->category_name ?? 'Chưa phân loại' }}
                                </div>

                                <h2 class="h6 product-title mb-2">
                                    {{ $product->product_name }}
                                </h2>

                                <div class="small text-secondary text-truncate mb-3">
                                    <i class="bi bi-shop me-1" aria-hidden="true"></i>
                                    {{ $product->seller?->shop_name ?? 'Cửa hàng' }}
                                </div>

                                <div class="mt-auto d-flex justify-content-between align-items-end gap-2">
                                    <span class="fw-bold text-danger text-nowrap">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </span>

                                    <span class="small text-secondary text-end text-nowrap">
                                        Đã bán {{ number_format($product->sold_count) }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border text-center py-5">
                        Hiện chưa có sản phẩm nào đang được bán.
                    </div>
                </div>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </main>

    <style>
        .product-card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        a:hover .product-card {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .product-title {
            display: -webkit-box;
            min-height: 2.7em;
            overflow: hidden;
            line-height: 1.35;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
    </style>
@endsection
