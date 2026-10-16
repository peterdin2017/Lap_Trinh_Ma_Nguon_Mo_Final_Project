@extends('store.layouts.app')

@section('title', $product->product_name)

@section('content')
    @php
        $variants = $product->variants ?? collect();
        $selectedVariant = $variants->first();
        $initialPrice = $selectedVariant?->price ?? $product->price;
        $initialStock = $selectedVariant?->stock_quantity ?? $product->stock_quantity;
    @endphp

    <main class="container py-4">
        {{-- Breadcrumb --}}
        <nav class="small mb-3" aria-label="breadcrumb">
            <a href="{{ route('store.home') }}" class="text-decoration-none">
                Trang chủ
            </a>
            <span class="text-secondary mx-2">/</span>
            <span class="text-secondary">
                {{ $product->category?->category_name ?? 'Sản phẩm' }}
            </span>
        </nav>

        {{-- Khung thông tin sản phẩm --}}
        <section class="card border-0 shadow-sm">
            <div class="card-body p-3 p-lg-4">
                <div class="row g-4">
                    {{-- Ảnh chính và ảnh phụ --}}
                    <div class="col-12 col-lg-5">
                        @if ($images->isNotEmpty())
                            <img id="productMainImage"
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($images->first()) }}"
                                alt="{{ $product->product_name }}" class="img-fluid rounded w-100"
                                style="height: 420px; object-fit: contain; background: #f8f9fa;">

                            @if ($images->count() > 1)
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @foreach ($images as $image)
                                        <button type="button" class="btn border p-1"
                                            data-image="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                            onclick="document.getElementById('productMainImage').src = this.dataset.image"
                                            aria-label="Xem ảnh sản phẩm {{ $loop->iteration }}">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                                alt="" width="64" height="64" style="object-fit: cover;">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 420px;">
                                <span class="text-secondary">Chưa có ảnh sản phẩm</span>
                            </div>
                        @endif
                    </div>

                    {{-- Tên, giá và lựa chọn mẫu mã --}}
                    <div class="col-12 col-lg-7">
                        <h1 class="h3 fw-semibold mb-3">
                            {{ $product->product_name }}
                        </h1>

                        <div class="d-flex flex-wrap gap-3 small mb-4">
                            <span>
                                Đánh giá:
                                <strong>{{ number_format($product->rating_avg, 1) }}</strong>
                                ({{ $product->rating_count }})
                            </span>

                            <span class="text-secondary">
                                Đã bán {{ number_format($product->sold_count) }}
                            </span>
                        </div>

                        {{-- Giá thay đổi theo mẫu đang chọn --}}
                        <div class="bg-light rounded p-3 mb-4">
                            <span id="productPrice" class="text-danger fs-2 fw-semibold">
                                {{ number_format($initialPrice, 0, ',', '.') }}₫
                            </span>
                        </div>

                        {{-- Các lựa chọn mẫu mã --}}
                        @if ($variants->isNotEmpty())
                            <div class="row mb-4 align-items-start">
                                <div class="col-4 col-sm-3 text-secondary pt-2">
                                    Mẫu mã
                                </div>

                                <div class="col-8 col-sm-9">
                                    <div class="d-flex flex-wrap gap-2 variant-list" role="group"
                                        aria-label="Chọn mẫu mã sản phẩm">
                                        @foreach ($variants as $variant)
                                            <button type="button"
                                                class="btn btn-outline-secondary variant-option {{ $loop->first ? 'active' : '' }}"
                                                data-variant-id="{{ $variant->variant_id }}"
                                                data-price="{{ $variant->price }}"
                                                data-stock="{{ $variant->stock_quantity }}"
                                                aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                                {{ $variant->variant_name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <dl class="row mb-4">
                            @if ($product->brand)
                                <dt class="col-4 col-sm-3 fw-normal text-secondary">
                                    Thương hiệu
                                </dt>
                                <dd class="col-8 col-sm-9">{{ $product->brand }}</dd>
                            @endif

                            <dt class="col-4 col-sm-3 fw-normal text-secondary">
                                Danh mục
                            </dt>
                            <dd class="col-8 col-sm-9">
                                {{ $product->category?->category_name ?? '—' }}
                            </dd>

                            <dt class="col-4 col-sm-3 fw-normal text-secondary">
                                Tình trạng
                            </dt>
                            <dd class="col-8 col-sm-9" id="productStatus">
                                @if ($initialStock > 0)
                                    <span class="text-success">Còn hàng</span>
                                @else
                                    <span class="text-danger">Hết hàng</span>
                                @endif
                            </dd>

                            <dt class="col-4 col-sm-3 fw-normal text-secondary">
                                Số lượng
                            </dt>
                            <dd class="col-8 col-sm-9" id="productStock">
                                {{ number_format($initialStock) }}
                            </dd>
                        </dl>

                        <form method="POST" action="{{ route('store.cart.items.store') }}">
                            @csrf

                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                            <input type="hidden" name="variant_id" id="selectedVariantId"
                                value="{{ $selectedVariant?->variant_id }}">

                            <div class="mb-3" style="max-width: 160px;">
                                <label for="quantity" class="form-label">Số lượng</label>
                                <input id="quantity" name="quantity" type="number" min="1"
                                    max="{{ $initialStock }}" value="1"
                                    class="form-control @error('quantity') is-invalid @enderror" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @error('variant_id')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn btn-primary" id="addToCartButton"
                                @disabled($initialStock < 1)>
                                <i class="bi bi-cart-plus me-1"></i>
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- Thông tin shop --}}
        <section class="card border-0 shadow-sm my-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-4">
                    <div class="col-12 col-lg-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 72px; height: 72px;">
                                <span class="fs-3 fw-bold text-primary">
                                    {{ mb_strtoupper(mb_substr($product->seller?->shop_name ?? 'S', 0, 1)) }}
                                </span>
                            </div>

                            <div>
                                <h2 class="h5 mb-2">
                                    {{ $product->seller?->shop_name ?? 'Cửa hàng' }}
                                </h2>
                                <span class="badge text-bg-success">
                                    Shop đang hoạt động
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="small text-secondary">
                                    Sản phẩm đang bán
                                </div>
                                <div class="fw-semibold">
                                    {{ $product->seller?->products()->where('status', 'active')->count() ?? 0 }}
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="small text-secondary">Tham gia</div>
                                <div class="fw-semibold">
                                    {{ $product->seller?->created_at?->format('m/Y') ?? '—' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="small text-secondary">Địa chỉ shop</div>
                                <div>
                                    {{ $product->seller?->shop_address ?: 'Chưa cập nhật' }}
                                </div>

                                @php
                                    $shopLocation = collect([
                                        $product->seller?->shop_ward,
                                        $product->seller?->shop_district,
                                        $product->seller?->shop_province,
                                    ])
                                        ->filter()
                                        ->implode(', ');
                                @endphp

                                @if ($shopLocation)
                                    <div class="small text-secondary mt-1">
                                        {{ $shopLocation }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Mô tả sản phẩm --}}
        <section class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3">Chi tiết sản phẩm</h2>

                @if ($product->description)
                    <p class="mb-0" style="white-space: pre-line;">{{ $product->description }}</p>
                @else
                    <p class="text-secondary mb-0">
                        Sản phẩm chưa có mô tả.
                    </p>
                @endif
            </div>
        </section>

        {{-- Đánh giá sản phẩm --}}
        <section class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-4">Đánh giá sản phẩm</h2>

                <div class="row align-items-center g-4 p-3 border rounded" style="background-color: #fffaf8;">
                    <div class="col-12 col-md-3 text-center">
                        <div class="text-danger">
                            <span class="fs-2 fw-semibold">
                                {{ number_format($product->rating_avg, 1) }}
                            </span>
                            <span> trên 5</span>
                        </div>

                        <div class="text-danger fs-4" aria-label="Điểm đánh giá trung bình">
                            @for ($star = 1; $star <= 5; $star++)
                                {{ $star <= round($product->rating_avg) ? '★' : '☆' }}
                            @endfor
                        </div>

                        <div class="small text-secondary">
                            {{ number_format($product->rating_count) }} đánh giá
                        </div>
                    </div>

                    <div class="col-12 col-md-9">
                        <p class="text-secondary mb-0">
                            @if ($product->rating_count > 0)
                                Điểm trên là đánh giá trung bình của sản phẩm.
                                Danh sách nhận xét chi tiết sẽ hiển thị khi chức năng đánh giá được kết nối.
                            @else
                                Sản phẩm chưa có đánh giá.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="text-center text-secondary py-4">
                    Chưa có nội dung đánh giá chi tiết.
                </div>
            </div>
        </section>
    </main>

    <style>
        .variant-list {
            max-height: 250px;
            overflow-y: auto;
            padding: 2px 6px 6px 2px;
        }

        .variant-option {
            border-radius: .25rem;
            white-space: normal;
            text-align: left;
        }

        .variant-option.active {
            color: var(--bs-primary);
            background-color: #fff;
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 1px var(--bs-primary);
        }
    </style>

    <script>
        (() => {
            const options = document.querySelectorAll('.variant-option');

            options.forEach((button) => {
                button.addEventListener('click', () => {
                    options.forEach((option) => {
                        option.classList.remove('active');
                        option.setAttribute('aria-pressed', 'false');
                    });

                    button.classList.add('active');
                    button.setAttribute('aria-pressed', 'true');

                    const price = Number(button.dataset.price);
                    const stock = Number(button.dataset.stock);

                    document.getElementById('productPrice').textContent =
                        new Intl.NumberFormat('vi-VN').format(price) + '₫';

                    document.getElementById('productStock').textContent =
                        new Intl.NumberFormat('vi-VN').format(stock);

                    document.getElementById('productStatus').innerHTML = stock > 0 ?
                        '<span class="text-success">Còn hàng</span>' :
                        '<span class="text-danger">Hết hàng</span>';
                });
            });
        })();
    </script>
@endsection
