<a
    href="{{ route('store.products.show', $product->product_id) }}"
    class="store-product__link text-decoration-none"
>
    <article class="store-product h-100">
        <div class="store-product__image-wrap">
            @if ($product->image_url)
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                    alt="{{ $product->product_name }}"
                    class="store-product__image"
                    loading="lazy"
                >
            @else
                <div class="store-product__image-placeholder">
                    <i class="bi bi-image fs-2" aria-hidden="true"></i>
                    <span class="small">Chưa có ảnh</span>
                </div>
            @endif
        </div>

        <div class="store-product__body d-flex flex-column gap-2">
            <h2 class="store-product__title mb-0">
                {{ $product->product_name }}
            </h2>

            <div class="store-product__shop">
                <i class="bi bi-shop" aria-hidden="true"></i>
                <span>{{ $product->seller?->shop_name ?? 'Cửa hàng' }}</span>
            </div>

            <div class="store-product__meta d-flex align-items-baseline justify-content-between gap-2 mt-auto">
                <span class="store-product__price">
                    {{ number_format($product->price, 0, ',', '.') }}₫
                </span>
                <span class="store-product__sold">
                    Đã bán {{ number_format($product->sold_count) }}
                </span>
            </div>
        </div>
    </article>
</a>

<style>
    .store-product__link {
        display: block;
        height: 100%;
        color: var(--shop-text, #0F172A);
    }

    .store-product {
        height: 100%;
        overflow: hidden;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .03);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .store-product__link:hover .store-product,
    .store-product__link:focus-visible .store-product {
        transform: translateY(-3px);
        border-color: #1A94FF;
        box-shadow: 0 6px 16px rgba(26, 148, 255, .12);
    }

    .store-product__image-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        padding: 12px;
        background: #F8FAFC;
        border-radius: 12px 12px 0 0;
    }

    .store-product__image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform .25s ease;
    }

    .store-product__link:hover .store-product__image,
    .store-product__link:focus-visible .store-product__image {
        transform: scale(1.04);
    }

    .store-product__image-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        color: var(--shop-text-muted, #64748B);
    }

    .store-product__body {
        min-height: 150px;
        padding: 16px;
    }

    .store-product__title {
        display: -webkit-box;
        min-height: 2.52em;
        overflow: hidden;
        color: #0F172A;
        font-size: .9rem;
        font-weight: 500;
        line-height: 1.4;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .store-product__shop {
        display: inline-flex;
        width: fit-content;
        max-width: 100%;
        align-items: center;
        gap: .35rem;
        overflow: hidden;
        padding: 3px 8px;
        color: #1A94FF;
        font-size: .75rem;
        text-overflow: ellipsis;
        white-space: nowrap;
        background: #EBF5FF;
        border-radius: 6px;
    }

    .store-product__price {
        color: #EE4D2D;
        font-size: 1.05rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .store-product__sold {
        color: #64748B;
        font-size: .75rem;
        text-align: right;
        white-space: nowrap;
    }

    @media (prefers-reduced-motion: reduce) {
        .store-product,
        .store-product__image {
            transition: none;
        }
    }
</style>
