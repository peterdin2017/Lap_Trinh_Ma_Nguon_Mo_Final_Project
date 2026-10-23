<div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-4">
    <div>
        <div class="small text-primary fw-semibold text-uppercase mb-1">
            Mua sắm
        </div>

        <h1 id="cart-page-title" class="h3 fw-bold mb-1">
            Giỏ hàng của bạn
        </h1>

        <p id="cart-page-description" class="text-secondary mb-0">
            Kiểm tra sản phẩm và số lượng trước khi tiếp tục.
        </p>
    </div>

    <a
        href="{{ route('store.home') }}"
        class="btn btn-outline-primary d-none d-sm-inline-flex align-items-center gap-2"
        aria-label="Tiếp tục mua sắm"
    >
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        Tiếp tục mua sắm
    </a>
</div>
