<img
    id="productMainImage"
    src="{{ $initialImageUrl ?? '' }}"
    data-gallery-image="{{ $galleryImageUrl ?? '' }}"
    alt="{{ $product->product_name }}"
    class="img-fluid rounded w-100 {{ $initialImageUrl ? '' : 'd-none' }}"
    style="height: 420px; object-fit: contain; background: #f8f9fa;"
>

<div
    id="productImagePlaceholder"
    class="bg-light rounded d-flex align-items-center justify-content-center {{ $initialImageUrl ? 'd-none' : '' }}"
    style="height: 420px;"
>
    <span class="text-secondary">Chưa có ảnh sản phẩm</span>
</div>

@if ($images->count() + $variantGalleryImages->count() > 1)
    <div class="d-flex flex-wrap gap-2 mt-3" aria-label="Thư viện ảnh sản phẩm">
        @foreach ($images as $image)
            @php
                $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($image);
            @endphp

            <button
                type="button"
                class="btn border p-1 product-gallery-thumbnail"
                data-image="{{ $imageUrl }}"
                aria-label="Xem ảnh sản phẩm {{ $loop->iteration }}"
                aria-pressed="false"
            >
                <img
                    src="{{ $imageUrl }}"
                    alt=""
                    width="64"
                    height="64"
                    style="object-fit: cover;"
                >
            </button>
        @endforeach

        @foreach ($variantGalleryImages as $variantImage)
            <button
                type="button"
                class="btn border p-1 product-gallery-thumbnail"
                data-image="{{ $variantImage['url'] }}"
                data-variant-id="{{ $variantImage['variant_id'] }}"
                aria-label="Xem ảnh mẫu {{ $variantImage['variant_name'] }}"
                aria-pressed="false"
            >
                <img
                    src="{{ $variantImage['url'] }}"
                    alt=""
                    width="64"
                    height="64"
                    style="object-fit: cover;"
                >
            </button>
        @endforeach
    </div>
@endif

