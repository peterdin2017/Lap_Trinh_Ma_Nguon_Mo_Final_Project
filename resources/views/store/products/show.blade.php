@extends('store.layouts.app')

@section('title', $product->product_name)

@section('content')
    @php
        $variants = $product->variants ?? collect();
        $hasVariants = $variants->isNotEmpty();

        // Có mẫu mã thì khách PHẢI chọn; chỉ giữ lại lựa chọn cũ khi form báo lỗi và quay lại.
        $selectedVariant = $hasVariants
            ? $variants->firstWhere('variant_id', (int) old('variant_id'))
            : null;

        $initialStock = $selectedVariant?->stock_quantity ?? $product->stock_quantity;

        $minPrice = $hasVariants ? $variants->min('price') : $product->price;
        $maxPrice = $hasVariants ? $variants->max('price') : $product->price;
        $fmt = fn ($n) => number_format($n, 0, ',', '.') . '₫';
        $initialPriceText = $selectedVariant
            ? $fmt($selectedVariant->price)
            : ((float) $minPrice === (float) $maxPrice ? $fmt($minPrice) : $fmt($minPrice) . ' - ' . $fmt($maxPrice));
    @endphp

    <main class="container py-4">
        <nav class="small mb-3" aria-label="breadcrumb">
<<<<<<< HEAD
            <a href="{{ route('store.home') }}" class="text-decoration-none">Trang chủ</a>
=======
            <a href="{{ route('store.home') }}" class="text-decoration-none">
                Trang chủ
            </a>
            @foreach ($breadcrumb as $crumb)
                <span class="text-secondary mx-2">/</span>
                <a href="{{ route('store.products.index', ['category' => $crumb->category_id]) }}"
                    class="text-decoration-none">{{ $crumb->category_name }}</a>
            @endforeach
>>>>>>> origin/hoai-phuong
            <span class="text-secondary mx-2">/</span>
            <span class="text-secondary">{{ $product->product_name }}</span>
        </nav>

        <section class="card border-0 shadow-sm">
            <div class="card-body p-3 p-lg-4">
                <div class="row g-4">
                    <div class="col-12 col-lg-5">
                        @if ($images->isNotEmpty())
                            <img id="productMainImage"
                                 src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($images->first()) }}"
                                 alt="{{ $product->product_name }}"
                                 class="img-fluid rounded w-100"
                                 style="height: 420px; object-fit: contain; background: #f8f9fa;">

                            @if ($images->count() > 1)
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @foreach ($images as $image)
                                        <button type="button"
                                                class="btn border p-1"
                                                data-image="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                                onclick="document.getElementById('productMainImage').src = this.dataset.image"
                                                aria-label="Xem ảnh sản phẩm {{ $loop->iteration }}">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                                 alt=""
                                                 width="64"
                                                 height="64"
                                                 style="object-fit: cover;">
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

                    <div class="col-12 col-lg-7">
                        <h1 class="h3 fw-semibold mb-3">{{ $product->product_name }}</h1>

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

                        <div class="bg-light rounded p-3 mb-4">
                            <span id="productPrice" class="text-danger fs-2 fw-semibold">
                                {{ $initialPriceText }}
                            </span>
                        </div>

                        @if ($variants->isNotEmpty())
                            <div class="row mb-4 align-items-start">
                                <div class="col-4 col-sm-3 text-secondary pt-2">Mẫu mã</div>
                                <div class="col-8 col-sm-9">
                                    <div class="d-flex flex-wrap gap-2 variant-list"
                                         role="group"
                                         aria-label="Chọn mẫu mã sản phẩm">
                                        @foreach ($variants as $variant)
                                            @php $isSelected = $selectedVariant?->variant_id === $variant->variant_id; @endphp
                                            <button type="button"
                                                class="btn btn-outline-secondary variant-option {{ $isSelected ? 'active' : '' }}"
                                                data-variant-id="{{ $variant->variant_id }}"
                                                data-price="{{ $variant->price }}"
                                                data-stock="{{ $variant->stock_quantity }}"
                                                @if ($variant->image_url)
                                                    data-image="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($variant->image_url) }}"
                                                @endif
                                                aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                                @disabled($variant->stock_quantity < 1)>
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
                            <dd class="col-8 col-sm-9">
                                {{ $product->category?->category_name ?? '—' }}
                            </dd>

                            <dt class="col-4 col-sm-3 fw-normal text-secondary">Tình trạng</dt>
                            <dd class="col-8 col-sm-9" id="productStatus">
                                @if ($hasVariants && ! $selectedVariant)
                                    <span class="text-secondary">Chọn mẫu mã để xem tình trạng</span>
                                @elseif ($initialStock > 0)
                                    <span class="text-success">Còn hàng</span>
                                @else
                                    <span class="text-danger">Hết hàng</span>
                                @endif
                            </dd>

                            <dt class="col-4 col-sm-3 fw-normal text-secondary">Số lượng</dt>
                            <dd class="col-8 col-sm-9" id="productStock">
                                {{ $hasVariants && ! $selectedVariant ? '—' : number_format($initialStock) }}
                            </dd>
                        </dl>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert"
                                        aria-label="Đóng"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert"
                                        aria-label="Đóng"></button>
                            </div>
                        @endif

                        {{-- Cả "Thêm vào giỏ" và "Mua ngay" gửi tới cùng route giỏ hàng của Người 2.
                             Khách chưa đăng nhập sẽ được CartController chuyển sang trang đăng nhập.
                             intent=buy_now để Người 2 chuyển tiếp sang bước xác nhận mua. --}}
                        <form method="POST" action="{{ route('store.cart.items.store') }}" id="purchaseForm" novalidate>
                            @csrf

                            <input type="hidden"
                                   name="product_id"
                                   value="{{ $product->product_id }}">

                            <input type="hidden"
                                   name="variant_id"
                                   id="selectedVariantId"
                                   value="{{ $selectedVariant?->variant_id }}">

                            <div class="mb-3" style="max-width: 160px;">
                                <label for="quantity" class="form-label">Số lượng</label>
                                <input id="quantity" name="quantity" type="number" min="1"
                                    max="{{ max($initialStock, 1) }}" value="{{ old('quantity', 1) }}"
                                    class="form-control @error('quantity') is-invalid @enderror" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="clientHint" class="text-danger small mt-1" hidden></div>
                            </div>

                            @error('variant_id')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" name="intent" value="cart" class="btn btn-outline-primary"
                                    id="addToCartButton" @disabled(! $hasVariants && $initialStock < 1)>
                                    <i class="bi bi-cart-plus me-1" aria-hidden="true"></i>
                                    Thêm vào giỏ hàng
                                </button>

                                <button type="submit" name="intent" value="buy_now" class="btn btn-primary"
                                    id="buyNowButton" @disabled(! $hasVariants && $initialStock < 1)>
                                    <i class="bi bi-lightning-charge me-1" aria-hidden="true"></i>
                                    Mua ngay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- Thông tin Shop: nhấn avatar hoặc tên để mở trang cửa hàng --}}
        @if ($product->seller)
            <section class="card border-0 shadow-sm my-4">
                <div class="card-body p-4">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-lg-4">
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{ route('store.shops.show', $product->seller->seller_id) }}"
                                   class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0 text-decoration-none"
                                   style="width: 72px; height: 72px;"
                                   aria-label="Xem cửa hàng {{ $product->seller->shop_name }}">
                                    <span class="fs-3 fw-bold text-primary">
                                        {{ mb_strtoupper(mb_substr($product->seller->shop_name, 0, 1)) }}
                                    </span>
                                </a>

                                <div>
                                    <h2 class="h5 mb-2">
                                        <a href="{{ route('store.shops.show', $product->seller->seller_id) }}"
                                           class="text-dark text-decoration-none">
                                            {{ $product->seller->shop_name }}
                                        </a>
                                    </h2>

                                    <span class="badge text-bg-success">Shop đang hoạt động</span>

                                    @if (session('account_role') === 'user')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary d-block mt-2"
                                                data-chat-start
                                                data-seller-id="{{ $product->seller->seller_id }}"
                                                data-shop-name="{{ $product->seller->shop_name }}"
                                                data-product-id="{{ $product->product_id }}">
                                            <i class="bi bi-chat-dots me-1" aria-hidden="true"></i>
                                            Chat với Shop
                                        </button>
                                    @else
                                        <a href="{{ route('login.form') }}"
                                           class="btn btn-sm btn-outline-primary d-block mt-2">
                                            <i class="bi bi-chat-dots me-1" aria-hidden="true"></i>
                                            Chat với Shop
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-8">
                            <div class="row g-3">
                                <div class="col-6 col-md-4">
                                    <div class="small text-secondary">Sản phẩm đang bán</div>
                                    <div class="fw-semibold">
                                        {{ $product->seller->products()->where('status', 'active')->count() }}
                                    </div>
                                </div>

                                <div class="col-6 col-md-4">
                                    <div class="small text-secondary">Tham gia</div>
                                    <div class="fw-semibold">
                                        {{ $product->seller->created_at?->format('m/Y') ?? '—' }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="small text-secondary">Địa chỉ Shop</div>
                                    <div>{{ $product->seller->shop_address ?: 'Chưa cập nhật' }}</div>

                                    @php
                                        $shopLocation = collect([
                                            $product->seller->shop_ward,
                                            $product->seller->shop_district,
                                            $product->seller->shop_province,
                                        ])->filter()->implode(', ');
                                    @endphp

                                    @if ($shopLocation)
                                        <div class="small text-secondary mt-1">{{ $shopLocation }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

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

        {{-- Đánh giá sản phẩm --}}
        <section class="card border-0 shadow-sm mt-4" id="reviews">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-4">Đánh giá sản phẩm</h2>

                @if (session('review_success'))
                    <div class="alert alert-success">{{ session('review_success') }}</div>
                @endif
                @error('review')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="row align-items-center g-4 p-3 border rounded" style="background-color: #fffaf8;">
                    <div class="col-12 col-md-3 text-center">
                        <div class="text-danger">
                            <span class="fs-2 fw-semibold">{{ number_format($product->rating_avg, 1) }}</span>
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
                        @if ($canReview)
                            <form method="POST" action="{{ route('store.products.reviews.store', $product->product_id) }}">
                                @csrf
                                <div class="fw-semibold mb-2">Viết đánh giá của bạn</div>
                                <select name="rating" class="form-select w-auto mb-2 @error('rating') is-invalid @enderror" required>
                                    <option value="">Chọn số sao</option>
                                    @foreach ([5, 4, 3, 2, 1] as $star)
                                        <option value="{{ $star }}" @selected((int) old('rating') === $star)>{{ $star }} sao</option>
                                    @endforeach
                                </select>
                                @error('rating')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <textarea name="comment" rows="3" maxlength="1000" class="form-control mb-2"
                                    placeholder="Chia sẻ trải nghiệm của bạn (không bắt buộc)">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                            </form>
                        @elseif ($isGuest)
                            <p class="text-secondary mb-0">
                                <a href="{{ route('login.form') }}">Đăng nhập</a> để đánh giá sau khi mua và nhận hàng.
                            </p>
                        @else
                            <p class="text-secondary mb-0">
                                Chỉ khách đã nhận sản phẩm này mới có thể đánh giá.
                            </p>
                        @endif
                    </div>
                </div>

                @forelse ($reviews as $review)
                    <div class="border-bottom py-3">
                        <div class="small">
                            <strong>{{ $reviewerNames[$review->account_id] ?? 'Người dùng ẩn danh' }}</strong>
                            <span class="text-danger ms-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            <span class="text-secondary ms-2">{{ $review->created_at?->format('d/m/Y') }}</span>
                        </div>
                        @if (filled($review->comment))
                            <div class="mt-1" style="white-space: pre-line;">{{ $review->comment }}</div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-secondary py-4">Chưa có nội dung đánh giá chi tiết.</div>
                @endforelse

                <div class="mt-3">{{ $reviews->links('pagination::bootstrap-5') }}</div>
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
            const hasVariants = @json($hasVariants);
            const baseStock = @json((int) $product->stock_quantity);
            const options = document.querySelectorAll('.variant-option');
            const form = document.getElementById('purchaseForm');
            const variantInput = document.getElementById('selectedVariantId');
            const qty = document.getElementById('quantity');
            const hint = document.getElementById('clientHint');
            const mainImage = document.getElementById('productMainImage');
            const buttons = [document.getElementById('addToCartButton'), document.getElementById('buyNowButton')];
            const vnd = (n) => new Intl.NumberFormat('vi-VN').format(n) + '₫';

            let galleryImage = mainImage ? mainImage.src : null;

            // Tồn kho của lựa chọn hiện tại; null = chưa chọn mẫu.
            function currentStock() {
                if (!hasVariants) return baseStock;
                const active = document.querySelector('.variant-option.active');
                return active ? Number(active.dataset.stock) : null;
            }

            function refresh() {
                const stock = currentStock();

                if (stock !== null) {
                    qty.max = Math.max(stock, 1);
                    if (stock >= 1 && Number(qty.value) > stock) qty.value = stock;
                }

                // Khóa nút khi biết chắc là hết hàng.
                buttons.forEach((b) => b && (b.disabled = stock !== null && stock < 1));
                hint.hidden = true;
            }

            options.forEach((button) => {
                button.addEventListener('click', () => {
                    options.forEach((option) => {
                        option.classList.remove('active');
                        option.setAttribute('aria-pressed', 'false');
                    });

                    button.classList.add('active');
                    button.setAttribute('aria-pressed', 'true');

                    // QUAN TRỌNG: cập nhật mẫu thật sự được gửi lên server.
                    variantInput.value = button.dataset.variantId;

                    const stock = Number(button.dataset.stock);

                    document.getElementById('productPrice').textContent = vnd(Number(button.dataset.price));
                    document.getElementById('productStock').textContent =
                        new Intl.NumberFormat('vi-VN').format(stock);
                    document.getElementById('productStatus').innerHTML = stock > 0 ?
                        '<span class="text-success">Còn hàng</span>' :
                        '<span class="text-danger">Hết hàng</span>';

                    // Ảnh riêng của mẫu (nếu có), không thì quay về ảnh đang xem.
                    if (mainImage) mainImage.src = button.dataset.image || galleryImage;

                    refresh();
                });
            });

            // Bấm ảnh nhỏ thì nhớ làm ảnh nền hiện tại.
            document.querySelectorAll('[data-image]').forEach((el) => {
                if (el.classList.contains('variant-option')) return;
                el.addEventListener('click', () => (galleryImage = el.dataset.image));
            });

            form.addEventListener('submit', (event) => {
                const stock = currentStock();
                const n = Number(qty.value);
                let message = '';

                if (hasVariants && stock === null) {
                    message = 'Vui lòng chọn mẫu mã trước khi mua.';
                } else if (!Number.isInteger(n) || n < 1) {
                    message = 'Số lượng phải từ 1 trở lên.';
                } else if (stock !== null && n > stock) {
                    message = 'Chỉ còn ' + stock + ' sản phẩm trong kho.';
                }

                if (message) {
                    event.preventDefault();
                    hint.textContent = message;
                    hint.hidden = false;
                }
            });

            qty.addEventListener('input', () => (hint.hidden = true));
            refresh();
        })();
    </script>
@endsection
