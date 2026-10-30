<section class="product-reviews card border-0 shadow-sm mt-4" id="reviews">
    <div class="card-body p-3 p-md-4">
        <h2 class="h5 fw-bold mb-4">Đánh giá sản phẩm</h2>

        @if (session('review_success'))
            <div class="alert alert-success" role="status">
                {{ session('review_success') }}
            </div>
        @endif

        @error('review')
            <div class="alert alert-danger" role="alert">{{ $message }}</div>
        @enderror

        <div class="product-reviews__summary mb-4">
            <div class="product-reviews__score">
                <div class="product-reviews__score-number">
                    {{ number_format($averageRating, 1) }}
                </div>

                @php
                    $displayRating = max(0, min(5, (float) $averageRating));
                @endphp

                <div
                    class="product-reviews__stars"
                    role="img"
                    aria-label="Điểm trung bình {{ number_format($averageRating, 1) }} trên 5"
                >
                    @for ($star = 1; $star <= 5; $star++)
                        @php
                            $starValue = $displayRating - ($star - 1);
                        @endphp

                        @if ($starValue >= 0.75)
                            <i class="bi bi-star-fill" aria-hidden="true"></i>
                        @elseif ($starValue >= 0.25)
                            <i class="bi bi-star-half" aria-hidden="true"></i>
                        @else
                            <i class="bi bi-star" aria-hidden="true"></i>
                        @endif
                    @endfor
                </div>

                <div class="small text-secondary mt-1">
                    {{ number_format($totalReviewCount) }} lượt đánh giá
                </div>
            </div>

            <div>
                <div class="small fw-semibold mb-2">Lọc nhận xét</div>

                <div class="product-reviews__filters" role="group" aria-label="Lọc đánh giá">
                    <button type="button" class="review-filter active" data-review-filter="all">
                        Tất cả
                    </button>

                    @foreach ([5, 4, 3, 2, 1] as $star)
                        <button type="button" class="review-filter" data-review-filter="{{ $star }}">
                            {{ $star }} Sao ({{ $ratingCounts[$star] ?? 0 }})
                        </button>
                    @endforeach

                    <button type="button" class="review-filter" data-review-filter="media">
                        Có hình ảnh
                    </button>
                </div>
            </div>
        </div>

        <div data-review-list>
            @forelse ($reviewCollection as $review)
                @php
                    $reviewerName = ($reviewerNames ?? [])[$review->account_id] ?? 'Người mua';
                    $reviewerInitial = \Illuminate\Support\Str::upper(
                        \Illuminate\Support\Str::substr($reviewerName, 0, 1)
                    );
                    $reviewImages = $review->images ?? collect();
                    $hasMedia = $reviewImages->isNotEmpty();
                    $reviewRating = max(0, min(5, (int) $review->rating));
                @endphp

                <article
                    class="product-review"
                    data-review-item
                    data-rating="{{ $reviewRating }}"
                    data-has-media="{{ $hasMedia ? '1' : '0' }}"
                >
                    <div class="d-flex align-items-start gap-3">
                        <div class="product-review__avatar" aria-hidden="true">
                            {{ $reviewerInitial }}
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex flex-wrap align-items-center">
                                <strong>{{ $reviewerName }}</strong>
                                <span class="badge bg-success-subtle text-success ms-2">
                                    <i class="bi bi-patch-check-fill me-1" aria-hidden="true"></i>
                                    Đã mua hàng
                                </span>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                <span
                                    class="product-reviews__stars"
                                    role="img"
                                    aria-label="{{ $reviewRating }} trên 5 sao"
                                >
                                    @for ($star = 1; $star <= 5; $star++)
                                        <i
                                            class="bi {{ $star <= $reviewRating ? 'bi-star-fill' : 'bi-star' }}"
                                            aria-hidden="true"
                                        ></i>
                                    @endfor
                                </span>

                                <span class="small text-secondary">
                                    {{ $review->created_at?->format('d/m/Y') }}
                                </span>

                                @if ($review->orderItem?->variant_name)
                                    <span class="small text-secondary">
                                        Phân loại: {{ $review->orderItem->variant_name }}
                                    </span>
                                @endif
                            </div>

                            @if (filled($review->comment))
                                <p class="product-review__comment">{{ $review->comment }}</p>
                            @endif

                            @if (filled($review->seller_reply))
                                <div class="product-review__seller-reply">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                        <strong>
                                            <i class="bi bi-reply-fill me-1" aria-hidden="true"></i>
                                            Phản hồi từ Shop
                                        </strong>

                                        @if ($review->seller_replied_at)
                                            <span class="small text-secondary">
                                                {{ $review->seller_replied_at->format('d/m/Y H:i') }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mb-0">{{ $review->seller_reply }}</p>
                                </div>
                            @endif

                            @if ($hasMedia)
                                <div class="product-review__media">
                                    @foreach ($reviewImages as $reviewImage)
                                        @php
                                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')
                                                ->url($reviewImage->image_path);
                                        @endphp

                                        <button
                                            type="button"
                                            class="product-review__image-button"
                                            data-review-image="{{ $imageUrl }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reviewImageLightbox"
                                            aria-label="Phóng to ảnh đánh giá"
                                        >
                                            <img
                                                src="{{ $imageUrl }}"
                                                alt="Ảnh thực tế trong đánh giá"
                                                loading="lazy"
                                            >
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center text-secondary py-4">
                    Chưa có đánh giá nào cho sản phẩm này.
                </div>
            @endforelse

            <div class="text-center text-secondary py-4 d-none" data-filter-empty>
                Không có đánh giá phù hợp với bộ lọc.
            </div>
        </div>

        @if (method_exists($reviews, 'hasPages') && $reviews->hasPages())
            <div class="mt-3">
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
        @endif

        <hr class="my-4">

        @if ($canReview)
            <form
                method="POST"
                action="{{ route('store.products.reviews.store', $product->product_id) }}"
                enctype="multipart/form-data"
                class="product-reviews__form"
            >
                @csrf

                <h3 class="h6 fw-bold mb-3">Viết đánh giá của bạn</h3>

                <input type="hidden" name="rating" id="reviewRating" value="{{ old('rating') }}">

                <div class="mb-3">
                    <div class="small fw-semibold mb-2">Chất lượng sản phẩm</div>

                    <div class="review-star-picker" role="radiogroup" aria-label="Chọn số sao">
                        @for ($star = 1; $star <= 5; $star++)
                            <button
                                type="button"
                                class="review-star"
                                data-rating-star="{{ $star }}"
                                aria-label="{{ $star }} sao"
                                aria-pressed="false"
                            >★</button>
                        @endfor
                    </div>

                    @error('rating')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reviewComment" class="form-label fw-semibold">
                        Nội dung đánh giá
                    </label>
                    <textarea
                        id="reviewComment"
                        name="comment"
                        rows="4"
                        maxlength="1000"
                        class="form-control @error('comment') is-invalid @enderror"
                        placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm"
                    >{{ old('comment') }}</textarea>

                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reviewImagesInput" class="form-label fw-semibold">
                        Ảnh thực tế
                    </label>
                    <input
                        id="reviewImagesInput"
                        type="file"
                        name="images[]"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        multiple
                        class="form-control @error('images') is-invalid @enderror"
                    >
                    <div class="form-text">Tối đa 5 ảnh, mỗi ảnh không quá 5 MB.</div>

                    @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div id="reviewImagesPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1" aria-hidden="true"></i>
                    Gửi đánh giá
                </button>
            </form>
        @elseif ($isGuest)
            <div class="alert alert-light border text-secondary mb-0" role="status">
                <i class="bi bi-info-circle-fill me-2" aria-hidden="true"></i>
                <a href="{{ route('login.form') }}">Đăng nhập</a>
                để đánh giá sau khi mua và nhận hàng.
            </div>
        @else
            <div class="alert alert-light border text-secondary mb-0" role="status">
                <i class="bi bi-info-circle-fill me-2" aria-hidden="true"></i>
                Chỉ khách hàng đã mua và nhận sản phẩm này mới có thể viết đánh giá.
            </div>
        @endif
    </div>
</section>

<style>
    .product-reviews__summary {
        display: grid;
        grid-template-columns: minmax(180px, 240px) minmax(0, 1fr);
        gap: 24px;
        align-items: center;
        padding: 24px;
        border: 1px solid #FDBA74;
        border-radius: 12px;
        background: #FFF9F5;
    }

    .product-reviews__score {
        text-align: center;
    }

    .product-reviews__score-number {
        color: #EE4D2D;
        font-size: 40px;
        font-weight: 800;
        line-height: 1;
    }

    .product-reviews__stars {
        color: #FFC107;
        letter-spacing: 2px;
        white-space: nowrap;
    }

    .product-reviews__stars .bi-star {
        color: #E2A600;
    }

    .product-reviews__filters,
    .product-review__media {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .review-filter {
        padding: 7px 13px;
        border: 1px solid #CBD5E1;
        border-radius: 999px;
        background: #fff;
        color: #475569;
    }

    .review-filter:hover,
    .review-filter.active {
        border-color: #EE4D2D;
        color: #EE4D2D;
    }

    .product-review {
        padding: 20px 0;
        border-bottom: 1px solid #E2E8F0;
    }

    .product-review__avatar {
        display: inline-flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #EAF4FF;
        color: #1A94FF;
        font-weight: 700;
    }

    .product-review__comment {
        margin: 10px 0 0;
        color: #334155;
        font-size: .95rem;
        line-height: 1.7;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .product-review__seller-reply {
        margin-top: 14px;
        padding: 12px 14px;
        border-left: 3px solid #1A94FF;
        border-radius: 8px;
        background: #F0F7FF;
    }

    .product-review__seller-reply strong {
        color: #1673D1;
    }

    .product-review__seller-reply p {
        color: #334155;
        line-height: 1.6;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .product-review__media {
        margin-top: 12px;
    }

    .product-review__image-button {
        width: 72px;
        height: 72px;
        padding: 0;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: #fff;
    }

    .product-review__image-button img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .18s ease;
    }

    .product-review__image-button:hover img {
        transform: scale(1.06);
    }

    .product-reviews__form {
        padding: 20px;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        background: #fff;
    }

    .review-star {
        padding: 0 2px;
        border: 0;
        background: transparent;
        color: #CBD5E1;
        font-size: 1.8rem;
        line-height: 1;
    }

    .review-star.active,
    .review-star:hover {
        color: #FFC107;
    }

    @media (max-width: 767.98px) {
        .product-reviews__summary {
            grid-template-columns: 1fr;
            gap: 18px;
            padding: 18px;
        }

        .product-reviews__score {
            text-align: left;
        }
    }
</style>

<script>
(() => {
    const root = document.querySelector('#reviews');
    if (!root) return;

    const filterButtons = root.querySelectorAll('[data-review-filter]');
    const reviewItems = [...root.querySelectorAll('[data-review-item]')];
    const filterEmpty = root.querySelector('[data-filter-empty]');

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.dataset.reviewFilter;
            let visibleCount = 0;

            filterButtons.forEach((item) => item.classList.remove('active'));
            button.classList.add('active');

            reviewItems.forEach((review) => {
                const matches = filter === 'all'
                    || (filter === 'media' && review.dataset.hasMedia === '1')
                    || review.dataset.rating === filter;

                review.classList.toggle('d-none', !matches);
                if (matches) visibleCount++;
            });

            filterEmpty?.classList.toggle(
                'd-none',
                visibleCount > 0 || reviewItems.length === 0
            );
        });
    });

    const ratingInput = root.querySelector('#reviewRating');
    const starButtons = [...root.querySelectorAll('[data-rating-star]')];

    const paintStars = (rating) => {
        starButtons.forEach((button) => {
            const active = Number(button.dataset.ratingStar) <= rating;
            button.classList.toggle('active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    };

    starButtons.forEach((button) => {
        button.addEventListener('mouseenter', () => {
            paintStars(Number(button.dataset.ratingStar));
        });

        button.addEventListener('focus', () => {
            paintStars(Number(button.dataset.ratingStar));
        });

        button.addEventListener('click', () => {
            ratingInput.value = button.dataset.ratingStar;
            paintStars(Number(ratingInput.value));
        });
    });

    root.querySelector('.review-star-picker')?.addEventListener('mouseleave', () => {
        paintStars(Number(ratingInput?.value || 0));
    });

    const input = root.querySelector('#reviewImagesInput');
    const preview = root.querySelector('#reviewImagesPreview');

    input?.addEventListener('change', () => {
        if (!preview) return;

        preview.replaceChildren();

        const files = [...input.files];
        if (files.length > 5) {
            alert('Chỉ được chọn tối đa 5 ảnh.');
            input.value = '';
            return;
        }

        const invalidFile = files.some((file) =>
            !['image/jpeg', 'image/png', 'image/jpg', 'image/webp'].includes(file.type)
            || file.size > 5 * 1024 * 1024
        );

        if (invalidFile) {
            alert('Ảnh phải là JPG, PNG hoặc WEBP và không quá 5 MB.');
            input.value = '';
            return;
        }

        files.forEach((file) => {
            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = 'Ảnh đánh giá xem trước';
            image.width = 64;
            image.height = 64;
            image.style.objectFit = 'cover';
            image.style.borderRadius = '8px';
            preview.appendChild(image);
        });
    });
})();
</script>
