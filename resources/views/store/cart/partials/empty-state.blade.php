<section id="cart-empty-state" class="card cart-card {{ $cartCount > 0 ? 'd-none' : '' }}">
    <div class="card-body text-center py-5 px-3">
        <span class="cart-empty-icon mb-3" aria-hidden="true">
            <i class="bi bi-cart3"></i>
        </span>
        <h2 class="h5 fw-bold mb-2">Giỏ hàng đang trống</h2>
        <p class="text-secondary mb-4">Khám phá cửa hàng và thêm sản phẩm bạn yêu thích.</p>
        <a href="{{ route('store.home') }}" class="btn btn-primary px-4">Khám phá sản phẩm</a>
    </div>
</section>
