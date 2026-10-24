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
@endsection
