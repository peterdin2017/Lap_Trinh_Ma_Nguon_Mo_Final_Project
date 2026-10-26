<section class="card checkout-card" aria-labelledby="payment-title">
    <div class="card-body p-3 p-md-4">
        <h2 id="payment-title" class="h5 fw-bold mb-3">
            <i class="bi bi-credit-card text-primary me-2" aria-hidden="true"></i>
            Phương thức thanh toán
        </h2>

        @php
            $paymentMethod = old('payment_method', 'cod');
        @endphp

        <div class="d-flex flex-column gap-2">
            <label class="checkout-payment-option">
                <input
                    class="form-check-input"
                    type="radio"
                    name="payment_method"
                    value="cod"
                    @checked($paymentMethod === 'cod')
                >
                <span>
                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                    <small class="d-block text-secondary">
                        Thanh toán trực tiếp cho đơn vị giao hàng.
                    </small>
                </span>
            </label>

            <label class="checkout-payment-option">
                <input
                    class="form-check-input"
                    type="radio"
                    name="payment_method"
                    value="vnpay"
                    @checked($paymentMethod === 'vnpay')
                >
                <span>
                    <strong>VNPAY</strong>
                    <small class="d-block text-secondary">
                        Đơn hàng sẽ chờ xác nhận thanh toán qua cổng VNPAY.
                    </small>
                </span>
            </label>

            <label class="checkout-payment-option">
                <input
                    class="form-check-input"
                    type="radio"
                    name="payment_method"
                    value="bank_transfer"
                    @checked($paymentMethod === 'bank_transfer')
                >
                <span>
                    <strong>Chuyển khoản qua QR</strong>
                    <small class="d-block text-secondary">
                        Đơn hàng ở trạng thái chờ thanh toán; QR cần cấu hình tài khoản nhận tiền.
                    </small>
                </span>
            </label>
        </div>
    </div>
</section>
