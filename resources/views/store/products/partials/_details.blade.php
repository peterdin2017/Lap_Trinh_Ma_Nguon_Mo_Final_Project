<h1 class="h3 fw-semibold mb-3">{{ $product->product_name }}</h1>

<div class="d-flex flex-wrap align-items-center gap-3 small mb-4">
    <span>
        Đánh giá:
        <strong>{{ number_format((float) $product->rating_avg, 1) }}</strong>
        ({{ number_format((int) $product->rating_count) }})
    </span>

    <span class="text-secondary">
        Đã bán {{ number_format((int) $product->sold_count) }}
    </span>

    @if (session('account_role') === 'user')
        <button
            type="button"
            class="btn btn-link btn-sm text-secondary text-decoration-none p-0 ms-lg-auto"
            data-bs-toggle="modal"
            data-bs-target="#productReportModal"
        >
            <i class="bi bi-flag me-1" aria-hidden="true"></i>
            Tố cáo
        </button>
    @elseif (!session('account_id'))
        <a
            href="{{ route('login.form') }}"
            class="text-secondary text-decoration-none ms-lg-auto"
        >
            <i class="bi bi-flag me-1" aria-hidden="true"></i>
            Tố cáo
        </a>
    @endif
</div>

@if (session('report_success'))
    <div class="alert alert-success py-2" role="status">
        {{ session('report_success') }}
    </div>
@endif

@if (session('report_error'))
    <div class="alert alert-warning py-2" role="status">
        {{ session('report_error') }}
    </div>
@endif

<div class="bg-light rounded p-3 mb-4">
    <span id="productPrice" class="text-danger fs-2 fw-semibold">
        {{ $initialPriceText }}
    </span>
</div>

@if ($variants->isNotEmpty())
    <div class="row mb-4 align-items-start">
        <div class="col-4 col-sm-3 text-secondary pt-2">Mẫu mã</div>

        <div class="col-8 col-sm-9">
            <div
                class="d-flex flex-wrap gap-2 variant-list"
                role="group"
                aria-label="Chọn mẫu mã sản phẩm"
            >
                @foreach ($variants as $variant)
                    @php
                        $isSelected = $selectedVariant?->variant_id === $variant->variant_id;
                        $variantImage = $variant->image_url
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($variant->image_url)
                            : null;
                    @endphp

                    <button
                        type="button"
                        class="btn btn-outline-secondary variant-option {{ $isSelected ? 'active' : '' }}"
                        data-variant-id="{{ $variant->variant_id }}"
                        data-price="{{ $variant->price }}"
                        data-stock="{{ $variant->stock_quantity }}"
                        @if ($variantImage) data-image="{{ $variantImage }}" @endif
                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                        @disabled($variant->stock_quantity < 1)
                    >
                        {{ $variant->variant_name }}

                        @if ($variant->stock_quantity < 1)
                            <small>(Hết hàng)</small>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>
@endif

<dl class="row mb-4">
    @if ($product->brand)
        <dt class="col-4 col-sm-3 fw-normal text-secondary">Thương hiệu</dt>
        <dd class="col-8 col-sm-9">{{ $product->brand }}</dd>
    @endif

    <dt class="col-4 col-sm-3 fw-normal text-secondary">Danh mục</dt>
    <dd class="col-8 col-sm-9">{{ $product->category?->category_name ?? '—' }}</dd>

    <dt class="col-4 col-sm-3 fw-normal text-secondary">Tình trạng</dt>
    <dd class="col-8 col-sm-9" id="productStatus">
        @if ($hasVariants && !$selectedVariant)
            <span class="text-secondary">Chọn mẫu mã để xem tình trạng</span>
        @elseif ($initialStock > 0)
            <span class="text-success">Còn hàng</span>
        @else
            <span class="text-danger">Hết hàng</span>
        @endif
    </dd>

    <dt class="col-4 col-sm-3 fw-normal text-secondary">Số lượng</dt>
    <dd class="col-8 col-sm-9" id="productStock">
        {{ $hasVariants && !$selectedVariant ? '—' : number_format($initialStock) }}
    </dd>
</dl>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Đóng"
        ></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Đóng"
        ></button>
    </div>
@endif

@if (session('account_role') === 'user')
    <div
        class="modal fade"
        id="productReportModal"
        tabindex="-1"
        aria-labelledby="productReportModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form
                    method="POST"
                    action="{{ route('store.products.reports.store', $product->product_id) }}"
                >
                    @csrf

                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="productReportModalLabel">
                            Tố cáo sản phẩm
                        </h2>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Đóng"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-secondary small">
                            Chọn lý do phù hợp. Báo cáo sẽ được quản trị viên xem xét.
                        </p>

                        <div class="mb-3">
                            <label for="reportReason" class="form-label">
                                Lý do báo cáo <span class="text-danger">*</span>
                            </label>

                            <select
                                id="reportReason"
                                name="reason"
                                class="form-select @error('reason') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Chọn lý do --</option>
                                <option value="fake" @selected(old('reason') === 'fake')>
                                    Hàng giả, hàng nhái
                                </option>
                                <option value="prohibited" @selected(old('reason') === 'prohibited')>
                                    Sản phẩm bị cấm
                                </option>
                                <option value="wrong_info" @selected(old('reason') === 'wrong_info')>
                                    Thông tin sản phẩm sai lệch
                                </option>
                                <option value="copyright" @selected(old('reason') === 'copyright')>
                                    Vi phạm bản quyền
                                </option>
                                <option value="other" @selected(old('reason') === 'other')>
                                    Lý do khác
                                </option>
                            </select>

                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="reportDescription" class="form-label">
                                Mô tả chi tiết
                            </label>

                            <textarea
                                id="reportDescription"
                                name="description"
                                rows="4"
                                maxlength="2000"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Cung cấp thêm thông tin để quản trị viên xem xét"
                            >{{ old('description') }}</textarea>

                            <div class="form-text" id="reportDescriptionHelp">
                                Bắt buộc khi chọn “Lý do khác”.
                            </div>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                        >
                            Đóng
                        </button>

                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-flag me-1" aria-hidden="true"></i>
                            Gửi báo cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reason = document.getElementById('reportReason');
            const description = document.getElementById('reportDescription');
            const modalElement = document.getElementById('productReportModal');

            const updateDescriptionRequirement = () => {
                if (reason && description) {
                    description.required = reason.value === 'other';
                }
            };

            reason?.addEventListener('change', updateDescriptionRequirement);
            updateDescriptionRequirement();

            @if ($errors->has('reason') || $errors->has('description'))
                if (modalElement && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            @endif
        });
    </script>
@endif
