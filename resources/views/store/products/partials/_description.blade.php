<section class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-3">Chi tiết sản phẩm</h2>

        @if ($product->description)
            <p class="mb-0" style="white-space: pre-line;">{{ $product->description }}</p>
        @else
            <p class="text-secondary mb-0">Sản phẩm chưa có mô tả.</p>
        @endif
    </div>
</section>
