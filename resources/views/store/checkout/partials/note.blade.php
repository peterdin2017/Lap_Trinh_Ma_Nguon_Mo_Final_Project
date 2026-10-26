<section class="card checkout-card" aria-labelledby="note-title">
    <div class="card-body p-3 p-md-4">
        <label id="note-title" for="note" class="form-label fw-bold">
            Ghi chú cho người bán
        </label>

        <textarea
            id="note"
            name="note"
            rows="3"
            maxlength="1000"
            class="form-control"
            placeholder="Ví dụ: Giao hàng giờ hành chính"
        >{{ old('note') }}</textarea>
    </div>
</section>
