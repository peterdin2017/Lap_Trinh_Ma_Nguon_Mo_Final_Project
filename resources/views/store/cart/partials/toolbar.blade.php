<div class="card cart-card cart-select-all-card mb-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="select-all">
                <label class="form-check-label fw-semibold" for="select-all">Chọn tất cả</label>
            </div>

            <span class="text-secondary small">
                <span id="cart-item-count">{{ $cartCount }}</span> sản phẩm ·
                <span id="cart-total-quantity">{{ $cartQuantity }}</span> món
            </span>
        </div>

        <button type="button"
                id="delete-selected"
                class="btn btn-link text-danger text-decoration-none p-0 d-none">
            <i class="bi bi-trash3 me-1" aria-hidden="true"></i>
            Xóa mục đã chọn (<span id="selected-delete-count">0</span>)
        </button>
    </div>
</div>
