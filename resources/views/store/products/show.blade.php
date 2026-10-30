@extends('store.layouts.app')

@section('title', $product->product_name)

@section('content')
    @php
        $variants = $product->variants ?? collect();
        $hasVariants = $variants->isNotEmpty();

        $selectedVariant = $hasVariants
            ? $variants->firstWhere('variant_id', (int) old('variant_id'))
            : null;

        $galleryImage = $images->first();
        $galleryImageUrl = $galleryImage
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($galleryImage)
            : null;

        $selectedVariantImageUrl = $selectedVariant?->image_url
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($selectedVariant->image_url)
            : null;

        $galleryImageUrls = $images->map(
            fn ($image) => \Illuminate\Support\Facades\Storage::disk('public')->url($image)
        )->all();

        $variantGalleryImages = $variants
            ->filter(fn ($variant) => filled($variant->image_url))
            ->map(fn ($variant) => [
                'variant_id' => (string) $variant->variant_id,
                'variant_name' => $variant->variant_name,
                'url' => \Illuminate\Support\Facades\Storage::disk('public')->url($variant->image_url),
            ])
            ->reject(fn ($image) => in_array($image['url'], $galleryImageUrls, true))
            ->values();

        $initialImageUrl = $selectedVariantImageUrl ?? $galleryImageUrl;
        $initialStock = $selectedVariant?->stock_quantity ?? $product->stock_quantity;

        $minPrice = $hasVariants ? $variants->min('price') : $product->price;
        $maxPrice = $hasVariants ? $variants->max('price') : $product->price;
        $fmt = fn ($n) => number_format($n, 0, ',', '.') . '₫';

        $initialPriceText = $selectedVariant
            ? $fmt($selectedVariant->price)
            : ((float) $minPrice === (float) $maxPrice
                ? $fmt($minPrice)
                : $fmt($minPrice) . ' - ' . $fmt($maxPrice));

        $reviewCollection = method_exists($reviews, 'getCollection')
            ? $reviews->getCollection()
            : collect($reviews);

        $ratingCounts = [];
        foreach ([5, 4, 3, 2, 1] as $star) {
            $ratingCounts[$star] = $reviewCollection->where('rating', $star)->count();
        }

        $averageRating = (float) ($product->rating_avg ?? 0);
        $totalReviewCount = (int) ($product->rating_count ?? 0);
    @endphp

    <main class="container py-4">
        @include('store.products.partials._breadcrumb')

        <section class="card border-0 shadow-sm">
            <div class="card-body p-3 p-lg-4">
                <div class="row g-4">
                    <div class="col-12 col-lg-5">
                        @include('store.products.partials._gallery')
                    </div>

                    <div class="col-12 col-lg-7">
                        @include('store.products.partials._details')
                        @include('store.products.partials._purchase-form')
                    </div>
                </div>
            </div>
        </section>

        @include('store.products.partials._seller')
        @include('store.products.partials._description')

        {{-- Tạo file _reviews.blade.php để hiển thị phần đánh giá --}}
        @includeIf('store.products.partials._reviews')
    </main>

    {{-- Tạo file này để bật chức năng xem ảnh đánh giá phóng to --}}
    @includeIf('store.products.partials._review-image-lightbox')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const variantButtons = document.querySelectorAll('.variant-option');
            const mainImage = document.getElementById('productMainImage');
            const imagePlaceholder = document.getElementById('productImagePlaceholder');
            const galleryThumbnails = document.querySelectorAll('.product-gallery-thumbnail');
            const defaultGalleryImage = mainImage?.dataset.galleryImage || '';
            const selectedVariantInput = document.getElementById('selectedVariantId');
            const priceElement = document.getElementById('productPrice');
            const statusElement = document.getElementById('productStatus');
            const stockElement = document.getElementById('productStock');
            const quantityInput = document.getElementById('quantity');
            const clientHint = document.getElementById('clientHint');
            const purchaseForm = document.getElementById('purchaseForm');

            const purchaseButtons = [
                document.getElementById('addToCartButton'),
                document.getElementById('buyNowButton'),
            ].filter(Boolean);

            const setMainImage = (imageUrl) => {
                if (!mainImage || !imageUrl) {
                    return;
                }

                mainImage.src = imageUrl;
                mainImage.classList.remove('d-none');
                imagePlaceholder?.classList.add('d-none');

                galleryThumbnails.forEach((thumbnail) => {
                    const isActive = thumbnail.dataset.image === imageUrl;
                    thumbnail.classList.toggle('active', isActive);
                    thumbnail.setAttribute('aria-pressed', String(isActive));
                });
            };

            galleryThumbnails.forEach((thumbnail) => {
                thumbnail.addEventListener('click', () => {
                    setMainImage(thumbnail.dataset.image);
                });
            });

            setMainImage(mainImage?.getAttribute('src'));

            if (!variantButtons.length) {
                return;
            }

            const formatPrice = (price) =>
                new Intl.NumberFormat('vi-VN').format(Number(price)) + '₫';

            const selectVariant = (selectedButton) => {
                if (selectedButton.disabled) {
                    return;
                }

                variantButtons.forEach((button) => {
                    const isSelected = button === selectedButton;

                    button.classList.toggle('active', isSelected);
                    button.setAttribute(
                        'aria-pressed',
                        isSelected ? 'true' : 'false'
                    );
                });

                const variantId = selectedButton.dataset.variantId;
                const price = Number(selectedButton.dataset.price || 0);
                const stock = Number(selectedButton.dataset.stock || 0);

                // Mẫu có ảnh riêng thì chuyển sang ảnh đó; nếu không, quay về ảnh sản phẩm.
                setMainImage(selectedButton.dataset.image || defaultGalleryImage);

                if (selectedVariantInput) {
                    selectedVariantInput.value = variantId;
                }

                if (priceElement) {
                    priceElement.textContent = formatPrice(price);
                }

                if (stockElement) {
                    stockElement.textContent = new Intl.NumberFormat('vi-VN')
                        .format(stock);
                }

                if (statusElement) {
                    statusElement.innerHTML = stock > 0
                        ? '<span class="text-success">Còn hàng</span>'
                        : '<span class="text-danger">Hết hàng</span>';
                }

                if (quantityInput) {
                    quantityInput.max = String(Math.max(stock, 1));
                    quantityInput.disabled = stock < 1;

                    const currentQuantity = Number(quantityInput.value || 1);
                    quantityInput.value = String(
                        Math.min(
                            Math.max(currentQuantity, 1),
                            Math.max(stock, 1)
                        )
                    );
                }

                purchaseButtons.forEach((button) => {
                    button.disabled = stock < 1;
                });

                if (clientHint) {
                    clientHint.textContent = '';
                    clientHint.hidden = true;
                }
            };

            variantButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    selectVariant(button);
                });
            });

            // Khôi phục mẫu mã cũ nếu form được trả về do lỗi validation.
            const oldVariantId = selectedVariantInput?.value;

            if (oldVariantId) {
                const oldButton = [...variantButtons].find(
                    (button) => button.dataset.variantId === oldVariantId
                );

                if (oldButton) {
                    selectVariant(oldButton);
                }
            }

            purchaseForm?.addEventListener('submit', (event) => {
                if (!selectedVariantInput?.value) {
                    event.preventDefault();

                    if (clientHint) {
                        clientHint.textContent =
                            'Vui lòng chọn mẫu mã sản phẩm.';
                        clientHint.hidden = false;
                    }

                    document.querySelector('.variant-list')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });
                }
            });
        });
    </script>
@endsection

