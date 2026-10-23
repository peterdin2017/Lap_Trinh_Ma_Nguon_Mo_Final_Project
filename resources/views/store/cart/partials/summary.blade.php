<div class="card cart-card cart-summary">
    <div class="card-body p-3 p-md-4">
        <h2 id="summary-title" class="h5 fw-bold mb-4">
            Tóm tắt đơn hàng
        </h2>

        <div class="summary-line">
            <span>
                Tạm tính (<span id="summary-selected-count">0</span> sản phẩm)
            </span>
            <strong id="summary-subtotal">0₫</strong>
        </div>

        <div class="summary-line">
            <span>Giảm giá</span>
            <strong id="summary-discount" class="text-success">0₫</strong>
        </div>

        <div class="summary-line">
            <span>Phí vận chuyển</span>
            <span class="text-secondary small">Tính ở bước tiếp theo</span>
        </div>

        <hr class="my-3">

        <div class="summary-line summary-line--total">
            <span>Tổng tiền</span>
            <strong id="summary-total">0₫</strong>
        </div>

        <p class="small text-secondary mt-2 mb-3">
            Phí giao hàng và ưu đãi sẽ được xác nhận ở bước thanh toán.
        </p>

        <form
            id="checkout-form"
            method="GET"
            action="{{ route('store.checkout.index') }}"
        >
            <button
                type="submit"
                id="checkout-button"
                class="btn btn-primary btn-lg w-100 fw-bold"
            >
                <i class="bi bi-shield-check me-2" aria-hidden="true"></i>
                Tiến hành thanh toán
            </button>
        </form>

        <a
            href="{{ route('store.home') }}"
            class="summary-continue d-flex justify-content-center align-items-center gap-2 mt-3"
        >
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Tiếp tục mua sắm
        </a>
    </div>
</div>
