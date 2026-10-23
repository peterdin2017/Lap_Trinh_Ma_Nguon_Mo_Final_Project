{{-- Thẻ sản phẩm. Cần biến $product; cột Bootstrap do view gọi partial quản lý. --}}
<a
    href="{{ route('store.products.show', $product->product_id) }}"
    class="store-product__link text-decoration-none text-dark d-block h-100"
>
    <article class="card store-product h-100 border-0 shadow-sm">
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
                <span class="badge text-bg-secondary position-absolute top-0 start-0 m-2">
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
