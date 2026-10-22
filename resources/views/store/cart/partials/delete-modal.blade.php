<div class="modal fade"
     id="cart-confirm-modal"
     tabindex="-1"
     aria-labelledby="cart-confirm-title"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body text-center p-4">
                <span class="cart-confirm-icon mb-3" aria-hidden="true">
                    <i class="bi bi-trash3"></i>
                </span>
                <h2 id="cart-confirm-title" class="h5 fw-bold">Xóa sản phẩm?</h2>
                <p id="cart-confirm-message" class="text-secondary small mb-4">
                    Sản phẩm đã chọn sẽ được xóa khỏi giỏ hàng.
                </p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">
                        Giữ lại
                    </button>
                    <button type="button" class="btn btn-danger flex-fill" id="confirm-cart-delete">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
