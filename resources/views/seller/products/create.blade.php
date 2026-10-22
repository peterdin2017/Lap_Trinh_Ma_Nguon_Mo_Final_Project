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

    <style>
        .product-form-page {
            --form-primary: var(--seller-primary);
            --form-primary-hover: var(--seller-primary-hover);
            --form-primary-light: var(--seller-primary-light);
            --form-page-bg: var(--seller-page-bg);
            --form-card: var(--seller-card-bg);
            --form-text: var(--seller-text);
            --form-muted: var(--seller-text-muted);
            --form-border: var(--seller-border);

            width: 100%;
            max-width: none;
            margin: 0;
            color: var(--form-text);
        }

        .product-form-page .page-heading,
        .product-form-page .section-card {
            margin-bottom: 16px !important;
            color: var(--form-text);
            background-color: var(--form-card);
            background-image: none;
            border: 1px solid var(--form-border);
            border-radius: 10px;
            box-shadow: none;
        }

        .product-form-page .page-heading,
        .product-form-page .section-card .card-body {
            padding: 20px !important;
        }

        .product-form-page .section-title-icon {
            display: inline-flex;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: var(--form-primary);
            background-color: var(--form-primary-light);
        }

        .product-form-page h1,
        .product-form-page h2,
        .product-form-page .form-label {
            color: var(--form-text);
        }

        .product-form-page .text-secondary,
        .product-form-page .form-text {
            color: var(--form-muted) !important;
        }

        .product-form-page .form-control,
        .product-form-page .form-select {
            min-height: 42px;
            color: var(--form-text);
            background-color: var(--form-card);
            border: 1px solid var(--form-border);
            border-radius: 8px;
        }

        .product-form-page textarea.form-control {
            min-height: 150px;
        }

        .product-form-page .form-control::placeholder {
            color: var(--form-muted);
            opacity: .85;
        }

        .product-form-page .form-control:focus,
        .product-form-page .form-select:focus {
            color: var(--form-text);
            background-color: var(--form-card);
            border-color: var(--form-primary);
            box-shadow: 0 0 0 .15rem color-mix(in srgb, var(--form-primary) 20%, transparent);
        }

        .product-form-page .form-control[readonly] {
            background-color: var(--form-primary-light);
        }

        .product-form-page input[type="file"] {
            color: var(--form-text);
        }

        .product-form-page input[type="file"]::file-selector-button {
            color: var(--form-text);
            background-color: var(--form-primary-light);
            border: 0;
        }

        .product-form-page .variant-row {
            margin-bottom: 12px !important;
            padding: 12px;
            color: var(--form-text);
            background-color: var(--form-primary-light);
            border: 1px solid var(--form-border);
            border-radius: 8px;
            box-shadow: none;
        }

        .product-form-page .btn {
            min-height: 40px;
            border-radius: 8px;
            box-shadow: none;
        }

        .product-form-page .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--form-primary);
            --bs-btn-border-color: var(--form-primary);
            --bs-btn-hover-bg: var(--form-primary-hover);
            --bs-btn-hover-border-color: var(--form-primary-hover);
            --bs-btn-active-bg: var(--form-primary-hover);
            --bs-btn-active-border-color: var(--form-primary-hover);
        }

        .product-form-page .btn-outline-primary {
            --bs-btn-color: var(--form-primary);
            --bs-btn-border-color: var(--form-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--form-primary);
            --bs-btn-hover-border-color: var(--form-primary);
        }

        .product-form-page .btn-outline-secondary {
            --bs-btn-color: var(--form-muted);
            --bs-btn-border-color: var(--form-border);
            --bs-btn-hover-color: var(--form-text);
            --bs-btn-hover-bg: var(--form-primary-light);
            --bs-btn-hover-border-color: var(--form-border);
        }

        .product-form-page .alert {
            color: var(--form-text);
            background-color: var(--form-primary-light);
            border: 1px solid var(--form-border);
            box-shadow: none;
        }

        .product-form-page .seller-badge {
            color: var(--form-primary);
            background-color: var(--form-primary-light);
        }

        .product-form-page .section-divider {
            border-color: var(--form-border);
            opacity: 1;
        }

        @media (max-width: 575.98px) {
            .product-form-page .page-heading,
            .product-form-page .section-card .card-body {
                padding: 16px !important;
            }

            .product-form-page .form-actions .btn {
                flex: 1 1 0;
            }
        }
    </style>

    <div class="product-form-page">
        {{-- Tiêu đề trang --}}
        <div class="page-heading d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="section-title-icon">
                        <i class="bi bi-box-seam fs-5" aria-hidden="true"></i>
                    </span>

                    <span class="badge rounded-pill seller-badge">
                        Seller Center
                    </span>
                </div>

                <h1 class="h3 fw-bold mb-1">Thêm sản phẩm</h1>
                <p class="text-secondary mb-0">
                    Hoàn thiện các thông tin bên dưới để đăng sản phẩm lên cửa hàng.
                </p>
            </div>

            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Danh sách sản phẩm
            </a>
        </div>

        {{-- Thông báo lỗi validation --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3" role="alert">
                <div class="fw-semibold mb-1">
                    <i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>
                    Vui lòng kiểm tra lại thông tin
                </div>

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
        >
            @csrf

            {{-- Thông tin cơ bản --}}
            <section class="card section-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-title-icon">
                            <i class="bi bi-card-text fs-5" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin cơ bản</h2>
                            <p class="small text-secondary mb-0">
                                Ảnh và thông tin nhận diện giúp khách tìm thấy sản phẩm.
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="product_name" class="form-label fw-semibold">
                            Tên sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input
                            id="product_name"
                            name="product_name"
                            type="text"
                            class="form-control @error('product_name') is-invalid @enderror"
                            value="{{ old('product_name') }}"
                            maxlength="200"
                            placeholder="Nhập tên sản phẩm"
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
                                Danh mục <span class="text-danger">*</span>
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
                            <label for="brand" class="form-label fw-semibold">Thương hiệu</label>
                            <input
                                id="brand"
                                name="brand"
                                type="text"
                                class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand') }}"
                                maxlength="100"
                                placeholder="Nhập thương hiệu nếu có"
                            >
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="image" class="form-label fw-semibold">
                                Ảnh chính <span class="text-danger">*</span>
                            </label>
                            <input
                                id="image"
                                name="image"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                                class="form-control @error('image') is-invalid @enderror"
                                required
                            >
                            <div class="form-text">JPG, PNG, WebP, GIF hoặc BMP; tối đa 5MB.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="images" class="form-label fw-semibold">Ảnh phụ</label>
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
                    </div>
                </div>
            </section>

            {{-- Chi tiết sản phẩm --}}
            <section class="card section-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-title-icon">
                            <i class="bi bi-card-paragraph fs-5" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Chi tiết sản phẩm</h2>
                            <p class="small text-secondary mb-0">
                                Cung cấp mô tả rõ ràng để khách hiểu đặc điểm sản phẩm.
                            </p>
                        </div>
                    </div>

                    <label for="description" class="form-label fw-semibold">Mô tả sản phẩm</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        class="form-control @error('description') is-invalid @enderror"
                        maxlength="5000"
                        placeholder="Nhập mô tả, đặc điểm và thông tin cần thiết..."
                    >{{ old('description') }}</textarea>
                    <div class="form-text">Tối đa 5.000 ký tự.</div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </section>

            {{-- Thông tin bán hàng --}}
            <section class="card section-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="section-title-icon">
                                <i class="bi bi-tags fs-5" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h2 class="h5 fw-bold mb-1">Thông tin bán hàng</h2>
                                <p class="small text-secondary mb-0">
                                    Thiết lập mẫu mã, giá bán và số lượng tồn.
                                </p>
                            </div>
                        </div>

                        <button type="button" id="addVariant" class="btn btn-outline-primary">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                            Thêm mẫu mã
                        </button>
                    </div>

                    <div class="alert small mb-3" role="note">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        Ví dụ mẫu mã: Đen – 256GB hoặc Tím – 512GB. Khi các mẫu được điền đầy đủ,
                        giá thấp nhất và tổng tồn kho sẽ được tính tự động.
                    </div>

                    <div id="variantRows">
                        @foreach ($variantRows as $index => $variant)
                            @if (is_array($variant))
                                <div class="row g-2 align-items-end variant-row">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Tên mẫu mã</label>
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
                                        <label class="form-label fw-semibold">Giá (₫)</label>
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
                                        <label class="form-label fw-semibold">Tồn kho</label>
                                        <input
                                            name="variants[{{ $index }}][stock_quantity]"
                                            type="number"
                                            min="0"
                                            class="form-control"
                                            value="{{ $variant['stock_quantity'] ?? '' }}"
                                            placeholder="Số lượng"
                                        >
                                    </div>

                                    <div class="col-md-1 d-grid">
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger remove-variant"
                                            aria-label="Xóa mẫu mã"
                                            title="Xóa mẫu mã"
                                        >
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

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

                    <hr class="section-divider my-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label fw-semibold">
                                Giá bán thấp nhất (₫) <span class="text-danger">*</span>
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
                                Khi có mẫu mã đầy đủ, hệ thống tự lấy giá thấp nhất.
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock_quantity" class="form-label fw-semibold">
                                Tổng tồn kho <span class="text-danger">*</span>
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
                                Khi có mẫu mã đầy đủ, hệ thống tự cộng tồn kho các mẫu.
                            </div>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Vận chuyển --}}
            <section class="card section-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-title-icon">
                            <i class="bi bi-truck fs-5" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Vận chuyển</h2>
                            <p class="small text-secondary mb-0">
                                Nhập cân nặng để hỗ trợ xử lý thông tin giao hàng.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
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
                            placeholder="Ví dụ: 500"
                        >
                        <div class="form-text">Nhập cân nặng sản phẩm theo gram.</div>
                        @error('weight_gram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Nút thao tác --}}
            <div class="form-actions d-flex flex-column flex-sm-row justify-content-end gap-2 pb-4">
                <a
                    href="{{ route('seller.products.index') }}"
                    class="btn btn-outline-secondary px-4 order-2 order-sm-1"
                >
                    Hủy
                </a>

                <button type="submit" class="btn btn-primary px-4 order-1 order-sm-2">
                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                    Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>

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
                    <div class="row g-2 align-items-end variant-row">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Tên mẫu mã</label>
                            <input
                                name="variants[${index}][variant_name]"
                                type="text"
                                maxlength="100"
                                class="form-control"
                                placeholder="Ví dụ: Đen - 256GB"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Giá (₫)</label>
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
                            <label class="form-label fw-semibold">Tồn kho</label>
                            <input
                                name="variants[${index}][stock_quantity]"
                                type="number"
                                min="0"
                                class="form-control"
                                placeholder="Số lượng"
                            >
                        </div>

                        <div class="col-md-1 d-grid">
                            <button
                                type="button"
                                class="btn btn-outline-danger remove-variant"
                                aria-label="Xóa mẫu mã"
                                title="Xóa mẫu mã"
                            >
                                <i class="bi bi-trash" aria-hidden="true"></i>
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
