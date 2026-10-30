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
        $variantRows = is_array($variantRows) ? $variantRows : [];

        $pricingMode = old(
            'pricing_mode',
            count($savedVariants) > 0 ? 'variants' : 'single'
        );
        $variantsEnabled = $pricingMode === 'variants';

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
        .product-edit-page {
            --edit-primary: #1a94ff;
            --edit-primary-light: #eaf5ff;
            --edit-border: var(--seller-border, #e2e8f0);
            --edit-text: var(--seller-text, #0f172a);
            --edit-muted: var(--seller-text-muted, #64748b);
            --edit-surface: var(--seller-card-bg, #fff);
            color: var(--edit-text);
        }

        .product-edit-page .edit-heading,
        .product-edit-page .edit-card {
            background: var(--edit-surface);
            border: 1px solid var(--edit-border);
            border-radius: 12px;
            box-shadow: var(--seller-shadow, 0 4px 16px rgba(15, 23, 42, .035));
        }

        .product-edit-page .edit-heading {
            padding: 20px 24px;
        }

        .product-edit-page .edit-card {
            margin-bottom: 16px;
        }

        .product-edit-page .edit-card > .card-body {
            padding: 22px;
        }

        .product-edit-page h1,
        .product-edit-page h2,
        .product-edit-page label {
            color: var(--edit-text);
        }

        .product-edit-page .text-secondary,
        .product-edit-page .form-text {
            color: var(--edit-muted) !important;
        }

        .product-edit-page .form-control,
        .product-edit-page .form-select {
            min-height: 42px;
            color: var(--edit-text);
            background: var(--edit-surface);
            border-color: var(--edit-border);
            border-radius: 8px;
        }

        .product-edit-page textarea.form-control {
            min-height: 150px;
        }

        .product-edit-page .form-control:focus,
        .product-edit-page .form-select:focus {
            border-color: var(--edit-primary);
            box-shadow: 0 0 0 .18rem rgba(26, 148, 255, .14);
        }

        .product-edit-page .section-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            color: var(--edit-primary);
            background: var(--edit-primary-light);
            font-size: 1.15rem;
        }

        .product-edit-page .variant-panel {
            padding: 16px;
            border: 1px solid var(--edit-border);
            border-radius: 10px;
            background: var(--seller-hover-bg, #f8fafc);
        }

        .product-edit-page .variant-row {
            margin: 0;
            padding: 12px;
            border: 1px solid var(--edit-border);
            border-radius: 10px;
            background: var(--edit-surface);
        }

        .product-edit-page .variant-row .form-label {
            margin-bottom: .4rem;
        }

        .product-edit-page .variant-image-control {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 8px;
        }

        .product-edit-page .variant-image-thumb {
            display: grid;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            place-items: center;
            overflow: hidden;
            color: var(--edit-muted);
            background: var(--seller-hover-bg, #f8fafc);
            border: 1px solid var(--edit-border);
            border-radius: 7px;
        }

        .product-edit-page .variant-image-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-edit-page .variant-image-control .form-control {
            min-width: 0;
            padding: .35rem;
            font-size: .8rem;
        }

        .product-edit-page [hidden] {
            display: none !important;
        }

        .product-edit-page .current-image {
            width: 88px;
            height: 88px;
            object-fit: cover;
            border: 1px solid var(--edit-border);
            border-radius: 9px;
            background: var(--seller-hover-bg, #f8fafc);
        }

        .product-edit-page .gallery-image-card {
            height: 100%;
            padding: 8px;
            background: var(--edit-surface);
            border: 1px solid var(--edit-border);
            border-radius: 9px;
        }

        .product-edit-page .gallery-image-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-edit-page .form-actions {
            position: sticky;
            bottom: 0;
            z-index: 10;
            margin: 16px -1px 0;
            padding: 12px 16px;
            background: color-mix(in srgb, var(--edit-surface) 96%, transparent);
            border: 1px solid var(--edit-border);
            border-bottom: 0;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 -6px 20px rgba(15, 23, 42, .07);
            backdrop-filter: blur(10px);
        }

        .product-edit-page .form-actions .btn {
            min-height: 42px;
            border-radius: 8px;
        }

        @media (max-width: 991.98px) {
            .product-edit-page .variant-image-control {
                align-items: flex-start;
            }

            .product-edit-page .variant-image-control .form-control {
                min-height: 44px;
            }
        }

        @media (max-width: 767.98px) {
            .product-edit-page .edit-heading {
                padding: 16px;
            }

            .product-edit-page .edit-card > .card-body {
                padding: 16px;
            }

            .product-edit-page .form-actions {
                flex-wrap: wrap;
            }

            .product-edit-page .form-actions .action-spacer {
                display: none;
            }

            .product-edit-page .form-actions .btn {
                flex: 1 1 calc(50% - 8px);
            }
        }
    </style>

    <div class="product-edit-page">
        <header class="edit-heading d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="section-icon">
                        <i class="bi bi-box-seam" aria-hidden="true"></i>
                    </span>
                    <span class="badge rounded-pill text-primary" style="background:#eaf5ff">
                        Seller Center
                    </span>
                </div>

                <h1 class="h3 fw-bold mb-1">Sửa sản phẩm</h1>
                <p class="text-secondary mb-0">
                    Cập nhật thông tin, hình ảnh và giá bán cho sản phẩm.
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
            action="{{ route('seller.products.update', $product->product_id) }}"
            enctype="multipart/form-data"
            id="editProductForm"
        >
            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="pricing_mode"
                id="pricingMode"
                value="{{ $pricingMode }}"
            >

            {{-- Cờ này báo Controller cần đồng bộ hoặc xóa danh sách mẫu mã. --}}
            <input
                type="hidden"
                name="variants_present"
                id="variantsPresent"
                value="1"
            >

            <section class="card edit-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-icon">
                            <i class="bi bi-card-text" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin cơ bản</h2>
                            <p class="small text-secondary mb-0">
                                Cập nhật tên, ngành hàng và mô tả sản phẩm.
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
                            maxlength="200"
                            class="form-control @error('product_name') is-invalid @enderror"
                            value="{{ old('product_name', $product->product_name) }}"
                            required
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

                        <div class="col-12 col-md-6">
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

                    <div class="mt-3">
                        <label for="description" class="form-label fw-semibold">
                            Mô tả sản phẩm
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            maxlength="5000"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Nhập mô tả chi tiết..."
                        >{{ old('description', $product->description) }}</textarea>
                        <div class="form-text">
                            Nhập văn bản thường, không cần dán mã HTML.
                        </div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="card edit-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-icon">
                            <i class="bi bi-images" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Hình ảnh sản phẩm</h2>
                            <p class="small text-secondary mb-0">
                                Chọn ảnh mới nếu muốn thay ảnh hiện tại.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-lg-4">
                            <label for="image" class="form-label fw-semibold">
                                Ảnh đại diện
                            </label>

                            <div class="mb-2">
                                @if ($product->image_url)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}"
                                        alt="{{ $product->product_name }}"
                                        class="current-image"
                                    >
                                @else
                                    <div class="text-secondary small">
                                        Chưa có ảnh đại diện.
                                    </div>
                                @endif
                            </div>

                            <input
                                id="image"
                                name="image"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                                class="form-control @error('image') is-invalid @enderror"
                            >
                            <div class="form-text">
                                Để trống nếu muốn giữ ảnh hiện tại.
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-lg-8">
                            <label for="images" class="form-label fw-semibold">
                                Thêm ảnh phụ
                            </label>
                            <input
                                id="images"
                                name="images[]"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
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
                    </div>

                    <div class="mt-4">
                        <div class="fw-semibold mb-2">Ảnh phụ hiện tại</div>

                        @if ($product->images->isEmpty())
                            <p class="text-secondary mb-0">
                                Sản phẩm chưa có ảnh phụ.
                            </p>
                        @else
                            <div class="row g-3">
                                @foreach ($product->images as $image)
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <label class="gallery-image-card d-block">
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_url) }}"
                                                alt="Ảnh phụ sản phẩm"
                                                class="mb-2"
                                            >
                                            <span class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="remove_image_ids[]"
                                                    value="{{ $image->image_id }}"
                                                >
                                                <span class="form-check-label">
                                                    Xóa ảnh này
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="card edit-card">
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
                                    value="{{ old('price', $product->price) }}"
                                    required
                                    @disabled($variantsEnabled)
                                    class="form-control @error('price') is-invalid @enderror"
                                    placeholder="0"
                                >
                                <div class="form-text">
                                    Khi có mẫu mã, giá sản phẩm được lấy theo mẫu có giá thấp nhất.
                                </div>
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
                                    value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                    required
                                    @disabled($variantsEnabled)
                                    class="form-control @error('stock_quantity') is-invalid @enderror"
                                    placeholder="0"
                                >
                                <div class="form-text">
                                    Khi có mẫu mã, tồn kho sản phẩm là tổng tồn kho các mẫu.
                                </div>
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
                                    @php
                                        $savedVariant = !empty($variant['variant_id'])
                                            ? $product->variants->firstWhere(
                                                'variant_id',
                                                (int) $variant['variant_id']
                                            )
                                            : null;
                                    @endphp

                                    <div class="variant-row row g-2 align-items-center">
                                        @if (!empty($variant['variant_id']))
                                            <input
                                                type="hidden"
                                                name="variants[{{ $index }}][variant_id]"
                                                value="{{ $variant['variant_id'] }}"
                                            >
                                        @endif

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
                                                type="text"
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

                                        <div class="col-12 col-lg-3">
                                            <label
                                                for="variant-image-{{ $index }}"
                                                class="form-label small fw-semibold"
                                            >
                                                Ảnh mẫu (nếu có)
                                            </label>

                                            <div class="variant-image-control">
                                                <span class="variant-image-thumb">
                                                    @if ($savedVariant?->image_url)
                                                        <img
                                                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($savedVariant->image_url) }}"
                                                            alt="Ảnh mẫu hiện tại"
                                                        >
                                                    @else
                                                        <i class="bi bi-image" aria-hidden="true"></i>
                                                    @endif
                                                </span>

                                                <input
                                                    id="variant-image-{{ $index }}"
                                                    name="variants[{{ $index }}][image]"
                                                    type="file"
                                                    accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                                                    @disabled(!$variantsEnabled)
                                                    class="form-control variant-image-input"
                                                >
                                            </div>

                                            <div class="form-text">
                                                Để trống để giữ ảnh hiện tại.
                                            </div>

                                            @error("variants.$index.image")
                                                <div class="text-danger small mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-lg-1 d-flex justify-content-end">
                                            <button
                                                type="button"
                                                class="btn btn-outline-danger remove-variant"
                                                aria-label="Xóa mẫu mã"
                                                title="Xóa mẫu mã"
                                            >
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                                <span class="d-lg-none ms-1">Xóa mẫu mã</span>
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
            </section>

            <section class="card edit-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="section-icon">
                            <i class="bi bi-box" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin vận chuyển</h2>
                            <p class="small text-secondary mb-0">
                                Cập nhật cân nặng để tính phí vận chuyển.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="weight_gram" class="form-label fw-semibold">
                            Cân nặng (gram)
                        </label>
                        <input
                            id="weight_gram"
                            name="weight_gram"
                            type="number"
                            min="1"
                            max="100000"
                            value="{{ old('weight_gram', $product->weight_gram) }}"
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
                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const form = document.getElementById('editProductForm');
            const rows = document.getElementById('variantRows');
            const editor = document.getElementById('variantEditor');
            const singleFields = document.getElementById('singleProductFields');
            const title = document.getElementById('pricingTitle');
            const enableButton = document.getElementById('enableVariantsButton');
            const disableButton = document.getElementById('disableVariantsButton');
            const variantsPresent = document.getElementById('variantsPresent');
            const pricingMode = document.getElementById('pricingMode');
            const addButton = document.getElementById('addVariant');
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock_quantity');

            if (
                !form
                || !rows
                || !editor
                || !singleFields
                || !pricingMode
                || !priceInput
                || !stockInput
            ) {
                return;
            }

            let variantsEnabled = pricingMode.value === 'variants';
            let nextIndex = {{ $nextVariantIndex }};

            function setPanelDisabled(panel, disabled) {
                panel.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = disabled;
                });
            }

            function updateTotals() {
                if (!variantsEnabled) {
                    return;
                }

                const variantRows = [...rows.querySelectorAll('.variant-row')];
                const values = variantRows.map(row => ({
                    name: row.querySelector('[name$="[variant_name]"]')?.value.trim(),
                    price: row.querySelector('[name$="[price]"]')?.value,
                    stock: row.querySelector('[name$="[stock_quantity]"]')?.value,
                }));

                const complete = values.length > 0 && values.every(item =>
                    item.name !== ''
                    && item.price !== ''
                    && item.stock !== ''
                    && Number.isFinite(Number(item.price))
                    && Number.isFinite(Number(item.stock))
                );

                if (complete) {
                    priceInput.value = Math.min(
                        ...values.map(item => Number(item.price))
                    );

                    stockInput.value = values.reduce(
                        (total, item) => total + Number(item.stock),
                        0
                    );
                }
            }

            function addVariantRow() {
                const index = nextIndex++;
                const row = document.createElement('div');

                row.className = 'variant-row row g-2 align-items-center';
                row.innerHTML = `
                    <div class="col-12 col-lg-4">
                        <label for="variant-name-${index}" class="form-label small fw-semibold">
                            Tên mẫu mã
                        </label>
                        <input
                            id="variant-name-${index}"
                            name="variants[${index}][variant_name]"
                            type="text"
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

                    <div class="col-12 col-lg-3">
                        <label for="variant-image-${index}" class="form-label small fw-semibold">
                            Ảnh mẫu (nếu có)
                        </label>
                        <div class="variant-image-control">
                            <span class="variant-image-thumb">
                                <i class="bi bi-image" aria-hidden="true"></i>
                            </span>
                            <input
                                id="variant-image-${index}"
                                name="variants[${index}][image]"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/gif,image/bmp"
                                class="form-control variant-image-input"
                            >
                        </div>
                        <div class="form-text">Chọn ảnh cho mẫu mã.</div>
                    </div>

                    <div class="col-12 col-lg-1 d-flex justify-content-end">
                        <button
                            type="button"
                            class="btn btn-outline-danger remove-variant"
                            aria-label="Xóa mẫu mã"
                            title="Xóa mẫu mã"
                        >
                            <i class="bi bi-trash3" aria-hidden="true"></i>
                            <span class="d-lg-none ms-1">Xóa mẫu mã</span>
                        </button>
                    </div>
                `;

                rows.appendChild(row);
            }

            function setMode(enabled) {
                variantsEnabled = enabled;
                pricingMode.value = enabled ? 'variants' : 'single';

                editor.hidden = !enabled;
                singleFields.hidden = enabled;

                setPanelDisabled(editor, !enabled);
                setPanelDisabled(singleFields, enabled);

                enableButton.hidden = enabled;
                disableButton.hidden = !enabled;

                title.textContent = enabled
                    ? 'Danh sách mẫu mã sản phẩm'
                    : 'Giá bán & Tồn kho';

                // Luôn gửi cờ để Controller biết cần đồng bộ danh sách mẫu mã.
                variantsPresent.value = '1';

                if (enabled && !rows.querySelector('.variant-row')) {
                    addVariantRow();
                }

                updateTotals();
            }

            enableButton.addEventListener('click', () => setMode(true));
            disableButton.addEventListener('click', () => setMode(false));
            addButton.addEventListener('click', addVariantRow);

            rows.addEventListener('input', updateTotals);

            rows.addEventListener('click', event => {
                const removeButton = event.target.closest('.remove-variant');

                if (removeButton) {
                    removeButton.closest('.variant-row')?.remove();
                    updateTotals();
                }
            });

            rows.addEventListener('change', event => {
                if (!event.target.matches('.variant-image-input')) {
                    return;
                }

                const row = event.target.closest('.variant-row');
                const thumb = row?.querySelector('.variant-image-thumb');
                const file = event.target.files?.[0];

                // Nếu người dùng chưa chọn ảnh mới, giữ nguyên thumbnail hiện tại.
                if (!thumb || !file) {
                    return;
                }

                if (thumb.dataset.previewUrl) {
                    URL.revokeObjectURL(thumb.dataset.previewUrl);
                }

                const imageUrl = URL.createObjectURL(file);
                thumb.dataset.previewUrl = imageUrl;

                const thumbImage = document.createElement('img');
                thumbImage.src = imageUrl;
                thumbImage.alt = 'Ảnh mẫu mã mới';
                thumb.replaceChildren(thumbImage);
            });

            form.addEventListener('submit', event => {
                if (!variantsEnabled) {
                    return;
                }

                const variantRows = [...rows.querySelectorAll('.variant-row')];
                const incomplete = variantRows.length === 0
                    || variantRows.some(row =>
                        [...row.querySelectorAll(
                            'input[name$="[variant_name]"], input[name$="[price]"], input[name$="[stock_quantity]"]'
                        )].some(input => !input.value.trim())
                    );

                if (incomplete) {
                    event.preventDefault();
                    alert('Vui lòng nhập đủ tên, giá và tồn kho cho từng mẫu mã.');
                }
            });

            setMode(variantsEnabled);
        })();
    </script>
@endsection

