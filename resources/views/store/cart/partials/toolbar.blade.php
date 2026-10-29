<div class="card cart-card cart-select-all-card mb-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="cart-select-all">
                <input
                    class="form-check-input"
                    type="checkbox"
                    id="select-all"
                    aria-controls="cart-stores"
                >
                <label class="cart-select-all__label" for="select-all">
                    Chọn tất cả
                </label>
            </div>

            <span class="text-secondary small">
                <span id="cart-item-count">{{ $cartCount }}</span> sản phẩm
                <span aria-hidden="true">·</span>
                <span id="cart-total-quantity">{{ $cartQuantity }}</span> món
            </span>
        </div>

        <button
            type="button"
            id="delete-selected"
            class="btn btn-link text-danger text-decoration-none p-0 d-none"
            aria-label="Xóa các sản phẩm đã chọn"
        >
            <i class="bi bi-trash3 me-1" aria-hidden="true"></i>
            Xóa mục đã chọn (<span id="selected-delete-count">0</span>)
        </button>
    </div>
</div>
