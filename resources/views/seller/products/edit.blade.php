@extends('seller.layouts.app')

@section('title', 'Sửa sản phẩm - Seller Center')

@section('content')
    @php
        $savedVariants = $product->variants
            ->map(fn ($variant) => [
                'variant_id' => $variant->variant_id,
                'variant_name' => $variant->variant_name,
                'price' => $variant->price,
                'stock_quantity' => $variant->stock_quantity,
            ])
            ->toArray();

        $variantRows = old('variants', $savedVariants);

        if (! is_array($variantRows)) {
            $variantRows = [];
        }

        $nextVariantIndex = $variantRows === []
            ? 0
            : max(array_map('intval', array_keys($variantRows))) + 1;
    @endphp

    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">Sửa sản phẩm</h1>
        <p class="text-secondary mb-0">
            Cập nhật thông tin, hình ảnh và mẫu mã sản phẩm.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('seller.products.update', $product->product_id) }}"
        enctype="multipart/form-data"
        class="card border-0 shadow-sm"
    >
        @csrf
        @method('PUT')

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="product_name" class="form-label fw-semibold">
                    Tên sản phẩm
                </label>
                <input
                    id="product_name"
                    name="product_name"
                    type="text"
                    maxlength="200"
                    class="form-control @error('product_name') is-invalid @enderror"
                    value="{{ old('product_name', $product->product_name) }}"
                    required
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
                                        @selected(
                                            (string) old('category_id', $product->category_id)
                                            === (string) $child->category_id
                                        )
                                    >
                                        {{ $parent->category_name }} › {{ $child->category_name }}
                                    </option>
                                @endforeach
                            @else
                                <option
                                    value="{{ $parent->category_id }}"
                                    @selected(
                                        (string) old('category_id', $product->category_id)
                                        === (string) $parent->category_id
                                    )
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
                        maxlength="100"
                        class="form-control @error('brand') is-invalid @enderror"
                        value="{{ old('brand', $product->brand) }}"
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
                    maxlength="5000"
                    class="form-control @error('description') is-invalid @enderror"
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <div class="fw-semibold">Mẫu mã sản phẩm</div>
                    <div class="small text-secondary">
                        Ví dụ: Đen – 256GB hoặc Tím – 512GB.
                        Có thể chọn ảnh riêng cho từng mẫu mã.
                    </div>
                </div>

                <div class="card-body">
                    <input type="hidden" name="variants_present" value="1">

                    <div id="variantRows">
                        @foreach ($variantRows as $index => $variant)
                            @if (is_array($variant))
                                @php
                                    $savedVariant = !empty($variant['variant_id'])
                                        ? $product->variants->firstWhere(
                                            'variant_id',
                                            (int) $variant['variant_id']
                                        )
                                        : null;
                                @endphp

                                <div class="row g-2 align-items-end mb-4 variant-row">
                                    @if (!empty($variant['variant_id']))
                                        <input
                                            type="hidden"
                                            name="variants[{{ $index }}][variant_id]"
                                            value="{{ $variant['variant_id'] }}"
                                        >
                                    @endif

                                    <div class="col-md-4">
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

                                    <div class="col-md-2">
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

                                    <div class="col-md-2">
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

                                    <div class="col-md-3">
                                        <label class="form-label">Ảnh mẫu mã</label>

                                        @if ($savedVariant?->image_url)
                                            <div class="mb-2">
                                                <img
                                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($savedVariant->image_url) }}"
                                                    alt="Ảnh mẫu {{ $variant['variant_name'] ?? '' }}"
                                                    width="64"
                                                    height="64"
                                                    class="rounded border"
                                                    style="object-fit: cover;"
                                                >
                                                <span class="small text-secondary ms-1">
                                                    Ảnh hiện tại
                                                </span>
                                            </div>
                                        @endif

                                        <input
                                            name="variants[{{ $index }}][image]"
                                            type="file"
                                            accept="image/*"
                                            class="form-control"
                                        >
                                        <div class="form-text">
                                            Để trống nếu muốn giữ ảnh hiện tại.
                                        </div>
                                        @error("variants.$index.image")
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-1 d-grid">
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

                    <button
                        type="button"
                        id="addVariant"
                        class="btn btn-outline-primary btn-sm"
                    >
                        <i class="bi bi-plus-lg me-1"></i>Thêm mẫu mã
                    </button>

                    @error('variants')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('variants.*.variant_id')
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
                        value="{{ old('price', $product->price) }}"
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
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
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
                        value="{{ old('weight_gram', $product->weight_gram) }}"
                    >
                    @error('weight_gram')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Ảnh chính hiện tại</label>

                <div class="mb-3">
                    @if ($product->image_url)
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                            alt="{{ $product->product_name }}"
                            width="120"
                            height="120"
                            class="rounded border"
                            style="object-fit: cover;"
                        >
                    @else
                        <span class="text-secondary">Chưa có ảnh chính.</span>
                    @endif
                </div>

                <label for="image" class="form-label">
                    Chọn ảnh chính mới (không bắt buộc)
                </label>
                <input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="form-control @error('image') is-invalid @enderror"
                >
                <div class="form-text">
                    Tối đa 10 MB. Hệ thống chuẩn hóa ảnh thành JPG hoặc PNG.
                </div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ảnh phụ hiện tại</label>

                @if ($product->images->isEmpty())
                    <p class="text-secondary">Sản phẩm chưa có ảnh phụ.</p>
                @else
                    <div class="row g-3">
                        @foreach ($product->images as $image)
                            <div class="col-6 col-sm-4 col-md-3">
                                <label class="card h-100 p-2">
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_url) }}"
                                        alt="Ảnh phụ sản phẩm"
                                        class="rounded mb-2"
                                        style="width: 100%; height: 130px; object-fit: cover;"
                                    >
                                    <span class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="remove_image_ids[]"
                                            value="{{ $image->image_id }}"
                                        >
                                        <span class="form-check-label">Xóa ảnh này</span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="images" class="form-label fw-semibold">
                    Thêm ảnh phụ
                </label>
                <input
                    id="images"
                    name="images[]"
                    type="file"
                    accept="image/*"
                    multiple
                    class="form-control @error('images') is-invalid @enderror"
                >
                <div class="form-text">
                    Tổng số ảnh phụ sau khi cập nhật không vượt quá 8 ảnh.
                </div>
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Lưu thay đổi
                </button>

                <a
                    href="{{ route('seller.products.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Quay lại
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
                    <div class="row g-2 align-items-end mb-4 variant-row">
                        <div class="col-md-4">
                            <label class="form-label">Tên mẫu mã</label>
                            <input
                                name="variants[${index}][variant_name]"
                                type="text"
                                maxlength="100"
                                class="form-control"
                                placeholder="Ví dụ: Đen - 256GB"
                            >
                        </div>

                        <div class="col-md-2">
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

                        <div class="col-md-2">
                            <label class="form-label">Tồn kho</label>
                            <input
                                name="variants[${index}][stock_quantity]"
                                type="number"
                                min="0"
                                class="form-control"
                                placeholder="Số lượng"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ảnh mẫu mã</label>
                            <input
                                name="variants[${index}][image]"
                                type="file"
                                accept="image/*"
                                class="form-control"
                            >
                            <div class="form-text">
                                Không bắt buộc, tối đa 10 MB.
                            </div>
                        </div>

                        <div class="col-md-1 d-grid">
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
