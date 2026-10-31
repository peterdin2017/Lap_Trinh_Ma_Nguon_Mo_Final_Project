@extends('seller.layouts.app')

@section('title', 'Thêm sản phẩm - Seller Center')

@section('content')
    @php
        $variantRows = old('variants', []);
        $variantRows = is_array($variantRows) ? $variantRows : [];

        $variantsEnabled = old('variants_present') == '1'
            || count($variantRows) > 0;

        if ($variantsEnabled && $variantRows === []) {
            $variantRows = [
                ['variant_name' => '', 'price' => '', 'stock_quantity' => ''],
            ];
        }

        $nextVariantIndex = $variantRows === []
            ? 0
            : max(array_map('intval', array_keys($variantRows))) + 1;
    @endphp

    <style>
        .product-create-page {
            --p: var(--seller-primary, #1a94ff);
            --p-light: var(--seller-primary-light, #eaf5ff);
            --line: var(--seller-border, #e2e8f0);
            --ink: var(--seller-text, #0f172a);
            --muted: var(--seller-text-muted, #64748b);
            --surface: var(--seller-card-bg, #fff);
            --canvas: var(--seller-page-bg, #f4f7fa);
            color: var(--ink);
            --soft-surface: var(--seller-hover-bg, #f8fafc);
        }

        .product-create-page .create-heading,
        .product-create-page .form-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, .035);
        }

        .product-create-page .create-heading {
            padding: 20px 24px;
        }

        .product-create-page .form-card {
            margin-bottom: 16px;
        }

        .product-create-page .form-card .card-body {
            padding: 22px;
        }

        .product-create-page h1,
        .product-create-page h2,
        .product-create-page label {
            color: var(--ink);
        }

        .product-create-page .text-secondary,
        .product-create-page .form-text {
            color: var(--muted) !important;
        }

        .product-create-page .form-control,
        .product-create-page .form-select {
            min-height: 42px;
            color: var(--ink);
            background-color: var(--surface);
            border-color: var(--line);
            border-radius: 8px;
        }

        .product-create-page textarea.form-control {
            min-height: 160px;
        }

        .product-create-page .form-control:focus,
        .product-create-page .form-select:focus {
            border-color: var(--p);
            box-shadow: 0 0 0 .18rem rgba(26, 148, 255, .14);
        }

        .product-create-page .section-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            color: var(--p);
            background: var(--p-light);
            font-size: 1.15rem;
        }

        .product-create-page .upload-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .product-create-page .upload-tile {
            position: relative;
            display: flex;
            width: 112px;
            height: 112px;
            flex: 0 0 112px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px dashed var(--line);
            border-radius: 10px;
            background: var(--soft-surface);
            cursor: pointer;
            transition: border-color .18s, background .18s, transform .18s;
        }

        .product-create-page .upload-tile:hover,
        .product-create-page .upload-tile.is-dragging {
            border-color: var(--p);
            background: var(--p-light);
            transform: translateY(-2px);
        }

        .product-create-page .upload-tile--cover {
            width: 148px;
            height: 148px;
            flex-basis: 148px;
            border-style: solid;
        }

        .product-create-page .upload-tile img,
        .product-create-page .preview-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-create-page .upload-tile__prompt {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            color: var(--muted);
            font-size: .8rem;
            text-align: center;
        }

        .product-create-page .upload-tile__prompt i {
            color: var(--p);
            font-size: 1.5rem;
        }

        .product-create-page .upload-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            z-index: 2;
            padding: 4px 7px;
            color: #fff;
            background: var(--p);
            border-radius: 999px;
            font-size: .65rem;
            font-weight: 700;
        }

        .product-create-page .preview-tile {
            position: relative;
            width: 112px;
            height: 112px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--surface);
        }

        .product-create-page .preview-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            display: grid;
            width: 28px;
            height: 28px;
            place-items: center;
            padding: 0;
            color: #fff;
            background: #dc3545;
            border: 0;
            border-radius: 50%;
            opacity: .92;
        }

        .product-create-page .preview-remove:hover {
            background: #b02a37;
        }

        .product-create-page .variant-panel {
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--soft-surface);
        }

        .product-create-page .variant-row {
            margin: 0;
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--surface);
        }

        .product-create-page .variant-image-preview {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }

        .product-create-page .variant-image-preview img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border: 1px solid var(--line);
            border-radius: 6px;
        }

        .product-create-page .variant-image-preview.d-none {
            display: none !important;
        }

        .product-create-page [hidden] {
            display: none !important;
        }

        .product-create-page .form-actions {
            position: sticky;
            bottom: 0;
            z-index: 100;
            margin: 16px -1px 0;
            padding: 12px 16px;
            background: color-mix(in srgb, var(--surface) 96%, transparent);
            border: 1px solid var(--line);
            border-bottom: 0;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 -6px 20px rgba(15, 23, 42, .07);
            backdrop-filter: blur(10px);
        }

        .product-create-page .form-actions .btn {
            min-height: 42px;
            border-radius: 8px;
        }

        .product-create-page .char-counter {
            color: var(--muted);
            font-size: .78rem;
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .product-create-page .form-card .card-body,
            .product-create-page .create-heading {
                padding: 16px;
            }

            .product-create-page .form-actions {
                flex-wrap: wrap;
            }

            .product-create-page .form-actions .action-spacer {
                display: none;
            }

            .product-create-page .form-actions .btn {
                flex: 1 1 calc(50% - 8px);
            }
        }
    </style>

    <div class="product-create-page">
        <header class="create-heading d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="section-icon">
                        <i class="bi bi-box-seam" aria-hidden="true"></i>
                    </span>
                    <span class="badge rounded-pill text-primary" style="background:var(--p-light)">
                        Seller Center
                    </span>
                </div>

                <h1 class="h3 fw-bold mb-1">Thêm sản phẩm</h1>
                <p class="text-secondary mb-0">
                    Thêm thông tin, hình ảnh và giá bán cho sản phẩm.
                </p>
            </div>

            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Danh sách sản phẩm
            </a>
        </header>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Vui lòng kiểm tra lại thông tin.</strong>
                <ul class="mb-0 mt-1">
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
            id="createProductForm"
        >
            @csrf

            <input
                type="hidden"
                name="variants_present"
                id="variantsPresent"
                value="1"
                @disabled(!$variantsEnabled)
            >

            <section class="card form-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-icon">
                            <i class="bi bi-card-text" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin cơ bản</h2>
                            <p class="small text-secondary mb-0">
                                Tên và ngành hàng giúp khách dễ tìm sản phẩm.
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <label for="product_name" class="form-label fw-semibold">
                                Tên sản phẩm <span class="text-danger">*</span>
                            </label>
                            <span class="char-counter">
                                <span id="productNameCount">
                                    {{ mb_strlen(old('product_name', '')) }}
                                </span>/120
                            </span>
                        </div>

                        <input
                            id="product_name"
                            name="product_name"
                            type="text"
                            maxlength="120"
                            required
                            autofocus
                            value="{{ old('product_name') }}"
                            class="form-control @error('product_name') is-invalid @enderror"
                            placeholder="Ví dụ: Điện thoại ..."
                        >

                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label fw-semibold">
                                Danh mục <span class="text-danger">*</span>
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                required
                                class="form-select @error('category_id') is-invalid @enderror"
                            >
                                <option value="">-- Chọn danh mục --</option>

                                @foreach ($categories as $parent)
                                    @if ($parent->children->isNotEmpty())
                                        @foreach ($parent->children as $child)
                                            <option
                                                value="{{ $child->category_id }}"
                                                @selected((string) old('category_id') === (string) $child->category_id)
                                            >
                                                {{ $parent->category_name }} › {{ $child->category_name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option
                                            value="{{ $parent->category_id }}"
                                            @selected((string) old('category_id') === (string) $parent->category_id)
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

                        <div class="col-12 col-md-6">
                            <label for="brand" class="form-label fw-semibold">
                                Thương hiệu
                            </label>

                            <input
                                id="brand"
                                name="brand"
                                maxlength="100"
                                value="{{ old('brand') }}"
                                class="form-control @error('brand') is-invalid @enderror"
                                placeholder="Tên thương hiệu (nếu có)"
                            >

                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="status" class="form-label fw-semibold">
                            Trạng thái đăng bán <span class="text-danger">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Lưu bản nháp</option>
                            <option value="active" @selected(old('status') === 'active')>Đăng bán ngay</option>
                        </select>
                        <div class="form-text">Bản nháp chỉ hiển thị trong Seller Center, chưa xuất hiện ở cửa hàng.</div>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="card form-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="section-icon">
                            <i class="bi bi-images" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Hình ảnh sản phẩm</h2>
                            <p class="small text-secondary mb-0">
                                Kéo thả ảnh vào ô hoặc nhấn để chọn. Tệp ảnh tối đa 5MB.
                            </p>
                        </div>
                    </div>

                    <div class="fw-semibold mt-4 mb-2">
                        Ảnh đại diện <span class="text-danger">*</span>
                    </div>

                    <div class="upload-grid mb-4">
                        <div
                            class="upload-tile upload-tile--cover"
                            id="coverDropZone"
                            role="button"
                            tabindex="0"
                            aria-label="Chọn ảnh đại diện"
                        >
                            <span class="upload-badge">Ảnh đại diện</span>
                            <span class="upload-tile__prompt" id="coverPrompt">
                                <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                                <span>Chọn hoặc kéo ảnh vào đây</span>
                            </span>
                            <img id="coverPreview" class="d-none" alt="Xem trước ảnh đại diện">
                            <button
                                type="button"
                                class="preview-remove d-none"
                                id="removeCover"
                                aria-label="Xóa ảnh đại diện"
                            >
                                <i class="bi bi-trash3" aria-hidden="true"></i>
                            </button>
                            <input
                                id="image"
                                name="image"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                                class="visually-hidden"
                                aria-label="Tệp ảnh đại diện"
                            >
                        </div>
                    </div>

                    @error('image')
                        <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror

                    <div class="fw-semibold mb-2">
                        Ảnh phụ <span class="small text-secondary fw-normal">(tối đa 8 ảnh)</span>
                    </div>

                    <div class="upload-grid" id="galleryGrid">
                        <label for="galleryInput" class="upload-tile" id="galleryDropZone">
                            <span class="upload-tile__prompt">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                <span>Thêm ảnh</span>
                            </span>
                        </label>
                        <input
                            id="galleryInput"
                            name="images[]"
                            type="file"
                            accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                            multiple
                            class="visually-hidden"
                            aria-label="Chọn ảnh phụ"
                        >
                    </div>

                    <div class="form-text mt-2">
                        JPG, PNG, WebP, GIF hoặc BMP. Mỗi ảnh tối đa 5MB.
                    </div>

                    @error('images')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    @error('images.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </section>

            <section class="card form-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-icon">
                            <i class="bi bi-card-paragraph" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Mô tả sản phẩm</h2>
                            <p class="small text-secondary mb-0">
                                Trình bày đặc điểm và thông tin cần thiết.
                            </p>
                        </div>
                    </div>

                    <label for="description" class="visually-hidden">
                        Mô tả sản phẩm
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="7"
                        maxlength="5000"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Nhập mô tả chi tiết..."
                    >{{ old('description') }}</textarea>

                    <div class="form-text">
                        Tối đa 5.000 ký tự. Nhập văn bản thường, không cần dán mã HTML.
                    </div>

                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </section>

            <section class="card form-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="section-icon">
                                <i class="bi bi-tags" aria-hidden="true"></i>
                            </span>
                            <h2 class="h5 fw-bold mb-0" id="pricingTitle">
                                {{ $variantsEnabled ? 'Danh sách mẫu mã sản phẩm' : 'Giá bán & Tồn kho' }}
                            </h2>
                        </div>

                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            id="enableVariantsButton"
                            @if ($variantsEnabled) hidden @endif
                        >
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                            Thêm mẫu mã sản phẩm
                        </button>

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="disableVariantsButton"
                            @unless ($variantsEnabled) hidden @endunless
                        >
                            Chuyển về sản phẩm đơn lẻ
                        </button>
                    </div>

                    <div id="singleProductFields" @if ($variantsEnabled) hidden @endif>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="price" class="form-label fw-semibold">
                                    Giá bán (đ) <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="price"
                                    name="price"
                                    type="number"
                                    min="0"
                                    step="1"
                                    value="{{ old('price', 0) }}"
                                    required
                                    @disabled($variantsEnabled)
                                    class="form-control @error('price') is-invalid @enderror"
                                    placeholder="0"
                                >
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="stock_quantity" class="form-label fw-semibold">
                                    Số lượng tồn kho <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="stock_quantity"
                                    name="stock_quantity"
                                    type="number"
                                    min="0"
                                    value="{{ old('stock_quantity', 0) }}"
                                    required
                                    @disabled($variantsEnabled)
                                    class="form-control @error('stock_quantity') is-invalid @enderror"
                                    placeholder="0"
                                >
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        id="variantEditor"
                        class="variant-panel"
                        @unless ($variantsEnabled) hidden @endunless
                    >
                        <div id="variantRows" class="d-flex flex-column gap-2">
                            @foreach ($variantRows as $index => $variant)
                                @if (is_array($variant))
                                    <div class="variant-row row g-2 align-items-end">
                                        <div class="col-12 col-lg-4">
                                            <label
                                                for="variant-name-{{ $index }}"
                                                class="form-label small fw-semibold"
                                            >
                                                Tên mẫu mã
                                            </label>
                                            <input
                                                id="variant-name-{{ $index }}"
                                                name="variants[{{ $index }}][variant_name]"
                                                maxlength="100"
                                                required
                                                @disabled(!$variantsEnabled)
                                                class="form-control"
                                                value="{{ $variant['variant_name'] ?? '' }}"
                                                placeholder="Ví dụ: Màu Đen - 128GB"
                                            >
                                        </div>

                                        <div class="col-6 col-lg-2">
                                            <label
                                                for="variant-price-{{ $index }}"
                                                class="form-label small fw-semibold"
                                            >
                                                Giá (đ)
                                            </label>
                                            <input
                                                id="variant-price-{{ $index }}"
                                                name="variants[{{ $index }}][price]"
                                                type="number"
                                                min="0"
                                                step="1"
                                                required
                                                @disabled(!$variantsEnabled)
                                                class="form-control"
                                                value="{{ $variant['price'] ?? '' }}"
                                                placeholder="0"
                                            >
                                        </div>

                                        <div class="col-6 col-lg-2">
                                            <label
                                                for="variant-stock-{{ $index }}"
                                                class="form-label small fw-semibold"
                                            >
                                                Tồn kho
                                            </label>
                                            <input
                                                id="variant-stock-{{ $index }}"
                                                name="variants[{{ $index }}][stock_quantity]"
                                                type="number"
                                                min="0"
                                                required
                                                @disabled(!$variantsEnabled)
                                                class="form-control"
                                                value="{{ $variant['stock_quantity'] ?? '' }}"
                                                placeholder="0"
                                            >
                                        </div>

                                        <div class="col-10 col-lg-3">
                                            <label
                                                for="variant-image-{{ $index }}"
                                                class="form-label small fw-semibold"
                                            >
                                                Ảnh mẫu (nếu có)
                                            </label>
                                            <input
                                                id="variant-image-{{ $index }}"
                                                name="variants[{{ $index }}][image]"
                                                type="file"
                                                accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                                                @disabled(!$variantsEnabled)
                                                class="form-control variant-image-input"
                                            >
                                            <div class="variant-image-preview d-none"></div>
                                        </div>

                                        <div class="col-2 col-lg-1 d-grid">
                                            <button
                                                type="button"
                                                class="btn btn-outline-danger remove-variant"
                                                aria-label="Xóa mẫu mã"
                                                title="Xóa mẫu mã"
                                            >
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm mt-3"
                            id="addVariant"
                        >
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                            Thêm dòng mẫu mã
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
                        @error('variants.*.image')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="card form-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-icon">
                            <i class="bi bi-truck" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin vận chuyển</h2>
                            <p class="small text-secondary mb-0">
                                Cân nặng giúp xử lý giao hàng chính xác hơn.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 px-0">
                        <label for="weight_gram" class="form-label fw-semibold">
                            Cân nặng (gram)
                        </label>
                        <input
                            id="weight_gram"
                            name="weight_gram"
                            type="number"
                            min="1"
                            value="{{ old('weight_gram') }}"
                            class="form-control @error('weight_gram') is-invalid @enderror"
                            placeholder="Ví dụ: 500"
                        >
                        @error('weight_gram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="form-actions d-flex align-items-center gap-2">
                <a
                    href="{{ route('seller.products.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                    Quay lại
                </a>

                <span class="action-spacer flex-grow-1"></span>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-cloud-arrow-up me-1" aria-hidden="true"></i>
                    Đăng bán sản phẩm
                </button>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const form = document.getElementById('createProductForm');
            const name = document.getElementById('product_name');
            const nameCount = document.getElementById('productNameCount');

            const coverInput = document.getElementById('image');
            const coverZone = document.getElementById('coverDropZone');
            const coverPreview = document.getElementById('coverPreview');
            const coverPrompt = document.getElementById('coverPrompt');
            const removeCover = document.getElementById('removeCover');

            const galleryInput = document.getElementById('galleryInput');
            const galleryGrid = document.getElementById('galleryGrid');
            const galleryZone = document.getElementById('galleryDropZone');

            const variantRows = document.getElementById('variantRows');
            const variantEditor = document.getElementById('variantEditor');
            const variantsFlag = document.getElementById('variantsPresent');
            const singleFields = document.getElementById('singleProductFields');
            const enableVariantsButton = document.getElementById('enableVariantsButton');
            const disableVariantsButton = document.getElementById('disableVariantsButton');
            const pricingTitle = document.getElementById('pricingTitle');
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock_quantity');

            let nextVariantIndex = {{ $nextVariantIndex }};
            let variantsEnabled = {{ $variantsEnabled ? 'true' : 'false' }};
            let galleryFiles = new DataTransfer();

            name.addEventListener('input', () => {
                nameCount.textContent = name.value.length;
            });

            function showCover(file) {
                if (!file) {
                    coverPreview.src = '';
                    coverPreview.classList.add('d-none');
                    coverPrompt.classList.remove('d-none');
                    removeCover.classList.add('d-none');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    return;
                }

                coverPreview.src = URL.createObjectURL(file);
                coverPreview.classList.remove('d-none');
                coverPrompt.classList.add('d-none');
                removeCover.classList.remove('d-none');
            }

            coverZone.addEventListener('click', event => {
                if (event.target.closest('#removeCover')) {
                    return;
                }

                if (event.target !== coverInput) {
                    coverInput.click();
                }
            });

            coverZone.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    coverInput.click();
                }
            });

            coverInput.addEventListener('change', () => {
                showCover(coverInput.files[0]);
            });

            removeCover.addEventListener('click', event => {
                event.stopPropagation();
                coverInput.value = '';
                showCover(null);
            });

            function bindDrop(zone, onFiles) {
                ['dragenter', 'dragover'].forEach(type => {
                    zone.addEventListener(type, event => {
                        event.preventDefault();
                        zone.classList.add('is-dragging');
                    });
                });

                ['dragleave', 'drop'].forEach(type => {
                    zone.addEventListener(type, event => {
                        event.preventDefault();
                        zone.classList.remove('is-dragging');
                    });
                });

                zone.addEventListener('drop', event => {
                    onFiles([...event.dataTransfer.files]);
                });
            }

            bindDrop(coverZone, files => {
                if (!files[0]) {
                    return;
                }

                const transfer = new DataTransfer();
                transfer.items.add(files[0]);
                coverInput.files = transfer.files;
                showCover(files[0]);
            });

            function renderGallery() {
                galleryGrid.querySelectorAll('.gallery-preview').forEach(node => {
                    node.remove();
                });

                [...galleryFiles.files].forEach((file, index) => {
                    const tile = document.createElement('div');
                    tile.className = 'preview-tile gallery-preview';

                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    image.alt = `Ảnh phụ ${index + 1}`;

                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'preview-remove';
                    remove.setAttribute(
                        'aria-label',
                        `Xóa ảnh phụ ${index + 1}`
                    );
                    remove.innerHTML =
                        '<i class="bi bi-trash3" aria-hidden="true"></i>';

                    remove.addEventListener('click', () => {
                        const next = new DataTransfer();

                        [...galleryFiles.files].forEach((item, itemIndex) => {
                            if (itemIndex !== index) {
                                next.items.add(item);
                            }
                        });

                        galleryFiles = next;
                        galleryInput.files = galleryFiles.files;
                        renderGallery();
                    });

                    tile.append(image, remove);
                    galleryGrid.insertBefore(tile, galleryZone);
                });
            }

            function addGallery(files) {
                for (const file of files) {
                    if (!file.type.startsWith('image/')) {
                        continue;
                    }

                    if (galleryFiles.files.length >= 8) {
                        alert('Chỉ được chọn tối đa 8 ảnh phụ.');
                        break;
                    }

                    galleryFiles.items.add(file);
                }

                galleryInput.files = galleryFiles.files;
                renderGallery();
            }

            galleryInput.addEventListener('change', () => {
                addGallery([...galleryInput.files]);
            });

            bindDrop(galleryZone, addGallery);

            function setPanelDisabled(panel, disabled) {
                panel.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = disabled;
                });
            }

            function addVariantRow() {
                const index = nextVariantIndex++;
                const row = document.createElement('div');

                row.className = 'variant-row row g-2 align-items-end';
                row.innerHTML = `
                    <div class="col-12 col-lg-4">
                        <label for="variant-name-${index}" class="form-label small fw-semibold">
                            Tên mẫu mã
                        </label>
                        <input
                            id="variant-name-${index}"
                            name="variants[${index}][variant_name]"
                            maxlength="100"
                            required
                            class="form-control"
                            placeholder="Ví dụ: Màu Đen - 128GB"
                        >
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="variant-price-${index}" class="form-label small fw-semibold">
                            Giá (đ)
                        </label>
                        <input
                            id="variant-price-${index}"
                            name="variants[${index}][price]"
                            type="number"
                            min="0"
                            step="1"
                            required
                            class="form-control"
                            placeholder="0"
                        >
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="variant-stock-${index}" class="form-label small fw-semibold">
                            Tồn kho
                        </label>
                        <input
                            id="variant-stock-${index}"
                            name="variants[${index}][stock_quantity]"
                            type="number"
                            min="0"
                            required
                            class="form-control"
                            placeholder="0"
                        >
                    </div>

                    <div class="col-10 col-lg-3">
                        <label for="variant-image-${index}" class="form-label small fw-semibold">
                            Ảnh mẫu (nếu có)
                        </label>
                        <input
                            id="variant-image-${index}"
                            name="variants[${index}][image]"
                            type="file"
                            accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                            class="form-control variant-image-input"
                        >
                        <div class="variant-image-preview d-none"></div>
                    </div>

                    <div class="col-2 col-lg-1 d-grid">
                        <button
                            type="button"
                            class="btn btn-outline-danger remove-variant"
                            aria-label="Xóa mẫu mã"
                            title="Xóa mẫu mã"
                        >
                            <i class="bi bi-trash3" aria-hidden="true"></i>
                        </button>
                    </div>
                `;

                setPanelDisabled(row, !variantsEnabled);
                variantRows.appendChild(row);
            }

            function setVariantMode(enabled) {
                variantsEnabled = enabled;

                singleFields.hidden = enabled;
                variantEditor.hidden = !enabled;

                setPanelDisabled(singleFields, enabled);
                setPanelDisabled(variantEditor, !enabled);

                enableVariantsButton.hidden = enabled;
                disableVariantsButton.hidden = !enabled;

                pricingTitle.textContent = enabled
                    ? 'Danh sách mẫu mã sản phẩm'
                    : 'Giá bán & Tồn kho';

                variantsFlag.disabled = !enabled;

                if (enabled && !variantRows.querySelector('.variant-row')) {
                    addVariantRow();
                }
            }

            enableVariantsButton.addEventListener('click', () => {
                setVariantMode(true);
            });

            disableVariantsButton.addEventListener('click', () => {
                // Chuyển chế độ nhưng giữ các dòng mẫu mã đã nhập.
                setVariantMode(false);
            });

            document.getElementById('addVariant').addEventListener('click', () => {
                addVariantRow();
            });

            variantRows.addEventListener('click', event => {
                const removeButton = event.target.closest('.remove-variant');

                if (removeButton) {
                    removeButton.closest('.variant-row')?.remove();
                }
            });

            variantRows.addEventListener('change', event => {
                if (!event.target.matches('.variant-image-input')) {
                    return;
                }

                const preview = event.target
                    .closest('.variant-row')
                    ?.querySelector('.variant-image-preview');

                const file = event.target.files?.[0];

                if (!preview) {
                    return;
                }

                preview.replaceChildren();

                if (!file) {
                    preview.classList.add('d-none');
                    return;
                }

                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = 'Xem trước ảnh mẫu mã';

                const fileName = document.createElement('span');
                fileName.className = 'small text-secondary';
                fileName.textContent = file.name;

                preview.append(image, fileName);
                preview.classList.remove('d-none');
            });

            form.addEventListener('submit', event => {
                if (!coverInput.files.length) {
                    event.preventDefault();
                    alert('Vui lòng chọn ảnh đại diện sản phẩm.');
                    coverZone.focus();
                    return;
                }

                if (!variantsEnabled) {
                    return;
                }

                const rows = [...variantRows.querySelectorAll('.variant-row')];
                const incomplete = rows.length === 0 || rows.some(row => {
                    return [...row.querySelectorAll('input[required]')]
                        .some(input => !input.value.trim());
                });

                if (incomplete) {
                    event.preventDefault();
                    alert('Vui lòng nhập đủ tên, giá và tồn kho cho từng mẫu mã.');
                }
            });

            setVariantMode(variantsEnabled);
        })();
    </script>
@endsection
