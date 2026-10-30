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
