{{-- Thẻ sản phẩm. Cần biến $product. --}}
@php
    $cardRating = max(0, min(5, (float) ($product->rating_avg ?? 0)));
    $displayRating = round($cardRating * 2) / 2;
    $cardReviewCount = (int) ($product->rating_count ?? 0);
@endphp

<div class="col">
    <a
        href="{{ route('store.products.show', ['slug' => $product->slug ?: $product->product_id]) }}"
        class="text-decoration-none text-dark d-block h-100"
    >
        <article class="card h-100 border-0 shadow-sm product-card">
            <div class="ratio ratio-1x1 bg-light overflow-hidden position-relative">
                @if ($product->image_url)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                        alt="{{ $product->product_name }}"
                        class="w-100 h-100 object-fit-contain p-2"
                        loading="lazy"
                    >
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center text-secondary">
                        <i class="bi bi-image fs-2" aria-hidden="true"></i>
                        <span class="small">Chưa có ảnh</span>
                    </div>
                @endif

                @if ($product->stock_quantity < 1)
                    <span
                        class="badge text-bg-secondary position-absolute top-0 start-0 m-2"
                        style="width: fit-content; height: fit-content;"
                    >
                        Hết hàng
                    </span>
                @endif
            </div>

            <div class="card-body d-flex flex-column p-3">
                <div class="small text-secondary mb-2">
                    {{ $product->category?->category_name ?? 'Chưa phân loại' }}
                </div>

                <h2 class="h6 product-title mb-2">
                    {{ $product->product_name }}
                </h2>

                <div class="product-card__rating d-flex align-items-center gap-2 mb-2">
                    <span
                        class="product-card__stars"
                        role="img"
                        aria-label="Đánh giá {{ number_format($cardRating, 1) }} trên 5"
                    >
                        @for ($star = 1; $star <= 5; $star++)
                            @php
                                $starValue = $displayRating - ($star - 1);
                            @endphp

                            @if ($starValue >= 1)
                                <i class="bi bi-star-fill" aria-hidden="true"></i>
                            @elseif ($starValue >= 0.5)
                                <i class="bi bi-star-half" aria-hidden="true"></i>
                            @else
                                <i class="bi bi-star" aria-hidden="true"></i>
                            @endif
                        @endfor
                    </span>

                    @if ($cardReviewCount > 0)
                        <span class="small text-secondary">
                            ({{ number_format($cardReviewCount) }})
                        </span>
                    @else
                        <span class="small text-secondary">Chưa có đánh giá</span>
                    @endif
                </div>

                <div class="small text-secondary text-truncate mb-3">
                    <i class="bi bi-shop me-1" aria-hidden="true"></i>
                    {{ $product->seller?->shop_name ?? 'Cửa hàng' }}
                </div>

                <div class="mt-auto d-flex justify-content-between align-items-end gap-2">
                    <span class="fw-bold text-danger text-nowrap">
                        {{ number_format($product->price, 0, ',', '.') }}₫
                    </span>

                    @if ($product->sold_count > 0)
                        <span class="small text-secondary text-end text-nowrap">
                            Đã bán {{ number_format($product->sold_count) }}
                        </span>
                    @endif
                </div>
            </div>
        </article>
    </a>
</div>

@once
    <style>
        .product-card__stars {
            color: #FFC107;
            white-space: nowrap;
        }

        .product-card__stars .bi-star {
            color: #D6A000;
        }
    </style>
@endonce
