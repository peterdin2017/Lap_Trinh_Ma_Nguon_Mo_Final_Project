@extends('seller.layouts.app')

@section('title', 'Thêm sản phẩm - Seller Center')

@section('content')
    @php
        $variantRows = old('variants', []);

        if (! is_array($variantRows)) {
            $variantRows = [];
        }

        $nextVariantIndex = $variantRows === []
            ? 0
            : max(array_map('intval', array_keys($variantRows))) + 1;
    @endphp

    <div class="mb-4">
        <h2 class="h4 fw-bold mb-1">Thêm sản phẩm</h2>
        <p class="text-secondary mb-0">Nhập thông tin sản phẩm để đăng bán.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('seller.products.store') }}"
        enctype="multipart/form-data"
        class="card border-0 shadow-sm"
    >
        @csrf

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="product_name" class="form-label fw-semibold">
                    Tên sản phẩm
                </label>
                <input
                    id="product_name"
                    name="product_name"
                    type="text"
                    class="form-control @error('product_name') is-invalid @enderror"
                    value="{{ old('product_name') }}"
                    maxlength="200"
                    required
                    autofocus
                >
                @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label fw-semibold">
                        Danh mục
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Chọn danh mục --</option>

                        @foreach ($categories as $parent)
                            @if ($parent->children->isNotEmpty())
                                @foreach ($parent->children as $child)
                                    <option
                                        value="{{ $child->category_id }}"
                                        @selected((string) old('category_id', '') === (string) $child->category_id)
                                    >
                                        {{ $parent->category_name }} › {{ $child->category_name }}
                                    </option>
                                @endforeach
                            @else
                                <option
                                    value="{{ $parent->category_id }}"
                                    @selected((string) old('category_id', '') === (string) $parent->category_id)
                                >
                                    {{ $parent->category_name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label fw-semibold">
                        Thương hiệu
                    </label>
                    <input
                        id="brand"
                        name="brand"
                        type="text"
                        class="form-control @error('brand') is-invalid @enderror"
                        value="{{ old('brand') }}"
                        maxlength="100"
                    >
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-semibold">
                    Mô tả sản phẩm
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="form-control @error('description') is-invalid @enderror"
                    maxlength="5000"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <div class="fw-semibold">Mẫu mã sản phẩm</div>
                    <div class="small text-secondary">
                        Ví dụ: Đen – 256GB hoặc Tím – 512GB. Nếu có mẫu mã,
                        giá và tồn kho tổng sẽ được tính từ các mẫu bên dưới.
                    </div>
                </div>

                <div class="card-body">
                    <div id="variantRows">
                        @foreach ($variantRows as $index => $variant)
                            @if (is_array($variant))
                                <div class="row g-2 align-items-end mb-3 variant-row">
                                    <div class="col-md-5">
                                        <label class="form-label">Tên mẫu mã</label>
                                        <input
                                            name="variants[{{ $index }}][variant_name]"
                                            type="text"
                                            maxlength="100"
                                            class="form-control"
                                            value="{{ $variant['variant_name'] ?? '' }}"
                                            placeholder="Ví dụ: Đen - 256GB"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Giá (₫)</label>
                                        <input
                                            name="variants[{{ $index }}][price]"
                                            type="number"
                                            min="0"
                                            step="1"
                                            class="form-control"
                                            value="{{ $variant['price'] ?? '' }}"
                                            placeholder="Giá mẫu này"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Tồn kho</label>
                                        <input
                                            name="variants[{{ $index }}][stock_quantity]"
                                            type="number"
                                            min="0"
                                            class="form-control"
                                            value="{{ $variant['stock_quantity'] ?? '' }}"
                                            placeholder="Số lượng"
                                        >
                                    </div>

                                    <div class="col-md-1">
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger remove-variant"
                                            aria-label="Xóa mẫu mã"
                                        >
                                            Xóa
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <button type="button" id="addVariant" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Thêm mẫu mã
                    </button>

                    @error('variants')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('variants.*.variant_name')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('variants.*.price')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('variants.*.stock_quantity')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label fw-semibold">
                        Giá bán thấp nhất (₫)
                    </label>
                    <input
                        id="price"
                        name="price"
                        type="number"
                        min="0"
                        step="1"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}"
                        required
                    >
                    <div class="form-text">
                        Khi có mẫu mã, hệ thống tự lấy giá thấp nhất.
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="stock_quantity" class="form-label fw-semibold">
                        Tổng tồn kho
                    </label>
                    <input
                        id="stock_quantity"
                        name="stock_quantity"
                        type="number"
                        min="0"
                        class="form-control @error('stock_quantity') is-invalid @enderror"
                        value="{{ old('stock_quantity', 0) }}"
                        required
                    >
                    <div class="form-text">
                        Khi có mẫu mã, hệ thống tự cộng tồn kho các mẫu.
                    </div>
                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="weight_gram" class="form-label fw-semibold">
                        Cân nặng (gram)
                    </label>
                    <input
                        id="weight_gram"
                        name="weight_gram"
                        type="number"
                        min="1"
                        class="form-control @error('weight_gram') is-invalid @enderror"
                        value="{{ old('weight_gram') }}"
                    >
                    @error('weight_gram')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label fw-semibold">
                    Ảnh chính
                </label>
                <input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                    class="form-control @error('image') is-invalid @enderror"
                    required
                >
                <div class="form-text">
                    Chọn một ảnh làm ảnh bìa. Hỗ trợ JPG, PNG, WebP, GIF hoặc BMP;
                    tối đa 5MB. Hệ thống sẽ chuẩn hóa ảnh thành JPG hoặc PNG.
                </div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="images" class="form-label fw-semibold">
                    Ảnh phụ
                </label>
                <input
                    id="images"
                    name="images[]"
                    type="file"
                    accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                    class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                    multiple
                >
                <div class="form-text">
                    Có thể chọn tối đa 8 ảnh phụ, mỗi ảnh tối đa 5MB.
                </div>
                @error('images')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    Lưu sản phẩm
                </button>

                <a
                    href="{{ route('seller.products.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Hủy
                </a>
            </div>
        </div>
    </form>

    <script>
        (() => {
            const rows = document.getElementById('variantRows');
            const addButton = document.getElementById('addVariant');
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock_quantity');

            if (!rows || !addButton || !priceInput || !stockInput) {
                return;
            }

            let nextIndex = {{ $nextVariantIndex }};

            const updateProductTotals = () => {
                const variantRows = [...rows.querySelectorAll('.variant-row')];

                const values = variantRows.map((row) => ({
                    name: row.querySelector('[name$="[variant_name]"]').value.trim(),
                    price: row.querySelector('[name$="[price]"]').value,
                    stock: row.querySelector('[name$="[stock_quantity]"]').value,
                }));

                const allRowsComplete = values.length > 0 && values.every((variant) =>
                    variant.name !== '' &&
                    variant.price !== '' &&
                    variant.stock !== '' &&
                    Number.isFinite(Number(variant.price)) &&
                    Number.isFinite(Number(variant.stock))
                );

                if (allRowsComplete) {
                    priceInput.value = Math.min(
                        ...values.map((variant) => Number(variant.price))
                    );

                    stockInput.value = values.reduce(
                        (total, variant) => total + Number(variant.stock),
                        0
                    );

                    priceInput.readOnly = true;
                    stockInput.readOnly = true;
                } else {
                    priceInput.readOnly = false;
                    stockInput.readOnly = false;
                }
            };

            addButton.addEventListener('click', () => {
                const index = nextIndex++;

                rows.insertAdjacentHTML('beforeend', `
                    <div class="row g-2 align-items-end mb-3 variant-row">
                        <div class="col-md-5">
                            <label class="form-label">Tên mẫu mã</label>
                            <input
                                name="variants[${index}][variant_name]"
                                type="text"
                                maxlength="100"
                                class="form-control"
                                placeholder="Ví dụ: Đen - 256GB"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Giá (₫)</label>
                            <input
                                name="variants[${index}][price]"
                                type="number"
                                min="0"
                                step="1"
                                class="form-control"
                                placeholder="Giá mẫu này"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tồn kho</label>
                            <input
                                name="variants[${index}][stock_quantity]"
                                type="number"
                                min="0"
                                class="form-control"
                                placeholder="Số lượng"
                            >
                        </div>

                        <div class="col-md-1">
                            <button
                                type="button"
                                class="btn btn-outline-danger remove-variant"
                                aria-label="Xóa mẫu mã"
                            >
                                Xóa
                            </button>
                        </div>
                    </div>
                `);

                updateProductTotals();
            });

            rows.addEventListener('input', updateProductTotals);

            rows.addEventListener('click', (event) => {
                const removeButton = event.target.closest('.remove-variant');

                if (removeButton) {
                    removeButton.closest('.variant-row').remove();
                    updateProductTotals();
                }
            });

            updateProductTotals();
        })();
    </script>
@endsection
