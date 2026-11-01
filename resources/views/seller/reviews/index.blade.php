@extends('seller.layouts.app')

@section('title', 'Đánh giá sản phẩm - Seller Center')

@section('content')
    <main class="container-fluid py-4 seller-reviews">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Đánh giá sản phẩm</h1>
            <p class="text-secondary mb-0">
                Theo dõi nhận xét và phản hồi khách hàng về sản phẩm của Shop.
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="status">
                {{ session('success') }}
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Đóng"
                ></button>
            </div>
        @endif

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('seller.reviews.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label for="review-keyword" class="form-label fw-semibold">
                                Tìm theo sản phẩm
                            </label>
                            <input
                                id="review-keyword"
                                type="search"
                                name="keyword"
                                class="form-control"
                                value="{{ $keyword }}"
                                placeholder="Nhập tên sản phẩm"
                            >
                        </div>

                        <div class="col-12 col-md-5 col-lg-3">
                            <label for="review-status" class="form-label fw-semibold">
                                Phản hồi
                            </label>
                            <select id="review-status" name="status" class="form-select">
                                <option value="all" @selected($status === 'all')>Tất cả</option>
                                <option value="unanswered" @selected($status === 'unanswered')>
                                    Chưa phản hồi
                                </option>
                                <option value="answered" @selected($status === 'answered')>
                                    Đã phản hồi
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="review-rating" class="form-label fw-semibold">Số sao</label>
                            <select id="review-rating" name="rating" class="form-select">
                                <option value="">Tất cả số sao</option>
                                @foreach ([5, 4, 3, 2, 1] as $star)
                                    <option
                                        value="{{ $star }}"
                                        @selected((string) $rating === (string) $star)
                                    >
                                        {{ $star }} sao
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3 col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-1" aria-hidden="true"></i>
                                Lọc
                            </button>
                            <a
                                href="{{ route('seller.reviews.index') }}"
                                class="btn btn-outline-secondary"
                                aria-label="Xóa bộ lọc"
                                title="Xóa bộ lọc"
                            >
                                <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <h2 class="h5 fw-bold mb-0">Danh sách đánh giá</h2>
            <span class="badge rounded-pill text-bg-light border">
                {{ number_format($reviews->total()) }} đánh giá
            </span>
        </div>

        @forelse ($reviews as $review)
            @php
                $replyHasError = $errors->has('seller_reply')
                    && (string) old('review_id') === (string) $review->review_id;

                $replyValue = $replyHasError
                    ? old('seller_reply')
                    : ($review->seller_reply ?? '');
            @endphp

            <article class="card border-0 shadow-sm mb-3 seller-review-card">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <div class="small text-secondary mb-1">Sản phẩm</div>
                            <h3 class="h6 fw-bold mb-1">
                                {{ $review->product?->product_name ?? 'Sản phẩm không còn tồn tại' }}
                            </h3>
                            <div class="small text-secondary">
                                Khách hàng · {{ $review->created_at?->format('d/m/Y H:i') }}
                                @if ($review->orderItem?->variant_name)
                                    · Phân loại: {{ $review->orderItem->variant_name }}
                                @endif
                            </div>
                        </div>

                        <div class="seller-review-rating" aria-label="{{ $review->rating }} trên 5 sao">
                            @for ($star = 1; $star <= 5; $star++)
                                <i
                                    class="bi {{ $star <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"
                                    aria-hidden="true"
                                ></i>
                            @endfor
                        </div>
                    </div>

                    <div class="seller-review-customer mb-3">
                        <div class="small fw-semibold text-secondary mb-1">
                            Nhận xét của khách
                        </div>

                        @if (filled($review->comment))
                            <p class="mb-0 seller-review-comment">{{ $review->comment }}</p>
                        @else
                            <p class="mb-0 text-secondary fst-italic">
                                Khách hàng không để lại nội dung.
                            </p>
                        @endif

                        @if ($review->images->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                @foreach ($review->images as $image)
                                    @php
                                        $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')
                                            ->url($image->image_path);
                                    @endphp
                                    <a
                                        href="{{ $imageUrl }}"
                                        target="_blank"
                                        rel="noopener"
                                        aria-label="Mở ảnh đánh giá"
                                    >
                                        <img
                                            class="seller-review-image"
                                            src="{{ $imageUrl }}"
                                            alt="Ảnh do khách gửi trong đánh giá"
                                            loading="lazy"
                                        >
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if (filled($review->seller_reply))
                        <div class="seller-review-existing-reply mb-3">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                <strong>
                                    <i class="bi bi-reply-fill me-1" aria-hidden="true"></i>
                                    Phản hồi của Shop
                                </strong>
                                <span class="small text-secondary">
                                    {{ $review->seller_replied_at?->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="mb-0 seller-review-comment">{{ $review->seller_reply }}</p>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('seller.reviews.update', $review->review_id) }}"
                        class="seller-review-reply-form"
                    >
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="review_id" value="{{ $review->review_id }}">

                        <label
                            for="seller-reply-{{ $review->review_id }}"
                            class="form-label fw-semibold"
                        >
                            {{ filled($review->seller_reply) ? 'Chỉnh sửa phản hồi' : 'Trả lời đánh giá' }}
                        </label>

                        <textarea
                            id="seller-reply-{{ $review->review_id }}"
                            name="seller_reply"
                            rows="3"
                            maxlength="2000"
                            required
                            class="form-control {{ $replyHasError ? 'is-invalid' : '' }}"
                            placeholder="Viết lời cảm ơn hoặc giải đáp cho khách hàng..."
                        >{{ $replyValue }}</textarea>

                        @if ($replyHasError)
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('seller_reply') }}
                            </div>
                        @endif

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2">
                            <small class="text-secondary">
                                Phản hồi sẽ hiển thị công khai bên dưới đánh giá.
                            </small>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-send me-1" aria-hidden="true"></i>
                                {{ filled($review->seller_reply) ? 'Cập nhật phản hồi' : 'Gửi phản hồi' }}
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-chat-square-text display-6 text-secondary" aria-hidden="true"></i>
                    <h3 class="h5 mt-3">Chưa có đánh giá phù hợp</h3>
                    <p class="text-secondary mb-0">
                        Đánh giá của khách về sản phẩm trong Shop sẽ hiển thị tại đây.
                    </p>
                </div>
            </div>
        @endforelse

        <div class="mt-4">{{ $reviews->links() }}</div>
    </main>

    <style>
        .seller-review-card { border-radius: 12px; }
        .seller-review-rating { color: #F5A623; white-space: nowrap; }
        .seller-review-customer {
            padding: 14px;
            border: 1px solid var(--seller-border, #E2E8F0);
            border-radius: 10px;
            background: var(--seller-hover-bg, #F8FAFC);
        }
        .seller-review-comment { white-space: pre-line; overflow-wrap: anywhere; }
        .seller-review-image {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border: 1px solid var(--seller-border, #E2E8F0);
            border-radius: 8px;
        }
        .seller-review-existing-reply {
            padding: 14px;
            border: 1px solid #BFDBFE;
            border-radius: 10px;
            background: #EFF6FF;
            color: #1E3A8A;
        }
        .seller-review-reply-form {
            padding-top: 14px;
            border-top: 1px solid var(--seller-border, #E2E8F0);
        }
        [data-theme="dark"] .seller-review-customer { background: #172338; }
        [data-theme="dark"] .seller-review-existing-reply {
            border-color: #334155;
            background: #172A46;
            color: #DBEAFE;
        }
    </style>
@endsection
