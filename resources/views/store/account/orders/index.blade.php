@extends('store.account.layouts.account')

@section('title', 'Lịch sử đơn hàng')

@section('account_content')
    @php
        $tabs = [
            'all' => 'Tất cả',
            'pending' => 'Chờ xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'return_requested' => 'Trả hàng/Hoàn tiền',
            'cancelled' => 'Đã hủy',
        ];

        $statusMeta = [
            'pending' => ['Chờ xác nhận', 'buyer-order-status--pending'],
            'processing' => ['Đang xử lý', 'buyer-order-status--processing'],
            'shipping' => ['Đang giao', 'buyer-order-status--shipping'],
            'completed' => ['Hoàn thành', 'buyer-order-status--completed'],
            'cancelled' => ['Đã hủy', 'buyer-order-status--cancelled'],
        ];

        $returnStatusMeta = [
            'pending' => ['Đang chờ cửa hàng xử lý', 'text-bg-warning'],
            'approved' => ['Đã chấp nhận', 'text-bg-success'],
            'rejected' => ['Không được chấp nhận', 'text-bg-danger'],
            'completed' => ['Đã xử lý xong', 'text-bg-success'],
        ];

        $reviewedItemIds = $reviewedItemIds ?? collect();
        $returnRequests = $returnRequests ?? collect();
    @endphp

    <style>
        .buyer-orders {
            color: #1e293b;
        }

        .buyer-orders__heading {
            margin-bottom: 1.25rem;
        }

        .buyer-orders__panel,
        .buyer-orders__card {
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .05);
        }

        .buyer-orders__panel {
            margin-bottom: 1rem;
        }

        .buyer-orders__tabs {
            display: flex;
            overflow-x: auto;
            border-bottom: 1px solid #e2e8f0;
            scrollbar-width: thin;
        }

        .buyer-orders__tab {
            display: inline-flex;
            flex: 1 0 auto;
            align-items: center;
            justify-content: center;
            padding: 13px 16px;
            border-bottom: 2px solid transparent;
            color: #64748b;
            text-align: center;
            text-decoration: none;
            white-space: nowrap;
            transition: color .18s ease, background-color .18s ease, border-color .18s ease;
        }

        .buyer-orders__tab:hover,
        .buyer-orders__tab.active {
            color: #1a94ff;
        }

        .buyer-orders__tab:hover {
            background: #f8fbff;
        }

        .buyer-orders__tab.active {
            border-bottom-color: #1a94ff;
            font-weight: 700;
        }

        .buyer-orders__card {
            margin-bottom: 1rem;
        }

        .buyer-orders__header,
        .buyer-orders__footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
        }

        .buyer-orders__header {
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .buyer-orders__shop {
            color: #334155;
            font-weight: 600;
            text-decoration: none;
        }

        .buyer-orders__shop:hover {
            color: #1a94ff;
        }

        .buyer-orders__muted {
            color: #64748b;
            font-size: .875rem;
        }

        .buyer-order-status {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .buyer-order-status--pending {
            color: #854d0e;
            background: #fef3c7;
        }

        .buyer-order-status--processing {
            color: #075985;
            background: #e0f2fe;
        }

        .buyer-order-status--shipping {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .buyer-order-status--completed {
            color: #166534;
            background: #dcfce7;
        }

        .buyer-order-status--cancelled {
            color: #991b1b;
            background: #fee2e2;
        }

        .buyer-orders__item {
            display: grid;
            grid-template-columns: 60px minmax(0, 1fr) auto;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .buyer-orders__item:last-child {
            border-bottom: 0;
        }

        .buyer-orders__image {
            display: flex;
            width: 60px;
            height: 60px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            color: #94a3b8;
        }

        .buyer-orders__image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .buyer-orders__product-name {
            margin-bottom: 4px;
            color: #1e293b;
            font-weight: 600;
            line-height: 1.4;
        }

        .buyer-orders__item-price {
            text-align: right;
            white-space: nowrap;
        }

        .buyer-orders__footer {
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }

        .buyer-orders__total {
            color: #dc2626;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .buyer-orders__actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .buyer-orders__btn-primary {
            border-color: #1a94ff;
            background: #1a94ff;
            color: #fff;
        }

        .buyer-orders__btn-primary:hover {
            border-color: #087bd8;
            background: #087bd8;
            color: #fff;
        }

        .buyer-orders__btn-return {
            border-color: #e2e8f0;
            background: #fff;
            color: #334155;
        }

        .buyer-orders__btn-return:hover {
            border-color: #1a94ff;
            color: #087bd8;
        }

        .buyer-orders__empty {
            padding: 48px 20px;
            color: #64748b;
            text-align: center;
        }

        #imagePreviewContainer .review-image-preview {
            position: relative;
            width: 60px;
            height: 60px;
        }

        #imagePreviewContainer .review-image-preview img {
            display: block;
            width: 60px;
            height: 60px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            object-fit: cover;
        }

        #imagePreviewContainer .review-image-remove {
            position: absolute;
            top: -7px;
            right: -7px;
            display: grid;
            width: 21px;
            height: 21px;
            place-items: center;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: #dc3545;
            color: #fff;
            font-size: 15px;
            line-height: 1;
        }

        @media (max-width: 575.98px) {
            .buyer-orders__tab {
                padding: 12px;
            }

            .buyer-orders__header,
            .buyer-orders__footer {
                padding: 12px;
            }

            .buyer-orders__item {
                grid-template-columns: 52px minmax(0, 1fr);
                gap: 10px;
                padding: 12px;
            }

            .buyer-orders__image {
                width: 52px;
                height: 52px;
            }

            .buyer-orders__item-price {
                grid-column: 2;
                text-align: left;
            }

            .buyer-orders__footer {
                align-items: flex-start;
            }

            .buyer-orders__actions {
                width: 100%;
                justify-content: stretch;
            }

            .buyer-orders__actions > * {
                flex: 1 1 auto;
            }

            .buyer-orders__actions .btn {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .buyer-orders__tab {
                transition: none;
            }
        }
    </style>

    <main class="buyer-orders">
        <header class="buyer-orders__heading">
            <h1 class="h3 fw-bold mb-1">Đơn hàng của tôi</h1>
            <p class="text-secondary mb-0">Theo dõi trạng thái và lịch sử mua hàng.</p>
        </header>

        @if (session('review_success'))
            <div class="alert alert-success" role="status">
                {{ session('review_success') }}
            </div>
        @endif

        @if (session('return_success'))
            <div class="alert alert-success" role="status">
                {{ session('return_success') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('order') || $errors->has('review') || $errors->has('return'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('order') ?: ($errors->first('review') ?: $errors->first('return')) }}
            </div>
        @endif

        <section class="buyer-orders__panel">
            <nav class="buyer-orders__tabs" aria-label="Lọc trạng thái đơn hàng">
                @foreach ($tabs as $value => $label)
                    <a
                        href="{{ route('store.account.orders.index', $value === 'all' ? [] : ['status' => $value]) }}"
                        class="buyer-orders__tab {{ ($status ?? 'all') === $value ? 'active' : '' }}"
                        @if (($status ?? 'all') === $value) aria-current="page" @endif
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </section>

        @forelse ($orders as $order)
            @php
                [$statusLabel, $statusClass] = $statusMeta[$order->order_status] ?? [
                    $order->order_status,
                    'buyer-order-status--processing',
                ];

                $returnRequest = $returnRequests->get($order->order_id);
                [$returnLabel, $returnBadgeClass] = $returnStatusMeta[$returnRequest?->status] ?? [
                    'Yêu cầu đang được xử lý',
                    'text-bg-secondary',
                ];
            @endphp

            <article class="buyer-orders__card">
                <header class="buyer-orders__header">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        @if ($order->seller)
                            <a
                                href="{{ route('store.shops.show', $order->seller->seller_id) }}"
                                class="buyer-orders__shop text-decoration-none"
                            >
                                <i class="bi bi-shop me-1" aria-hidden="true"></i>
                                {{ $order->seller->shop_name ?? 'Cửa hàng' }}
                            </a>
                        @else
                            <span class="buyer-orders__shop">
                                <i class="bi bi-shop me-1" aria-hidden="true"></i>
                                Cửa hàng
                            </span>
                        @endif

                        <span class="buyer-orders__muted">
                            Mã đơn: {{ $order->order_code ?? $order->order_id }}
                        </span>

                        <span class="buyer-orders__muted">
                            {{ $order->created_at?->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <span class="buyer-order-status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </header>

                @if ($order->order_status === 'cancelled')
                    <div class="alert alert-danger mx-3 mt-3 mb-0" role="status">
                        <strong>Đơn hàng đã bị hủy.</strong>
                        @if (filled($order->cancellation_reason))
                            <div class="mt-1">
                                Lý do: {{ $order->cancellation_reason }}
                            </div>
                        @endif
                    </div>
                @endif
                @forelse ($order->items as $item)
                    @php
                        $imagePath = $item->variant?->image_url ?: $item->product?->image_url;

                        $imageUrl = $imagePath
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath)
                            : null;

                        $productName = $item->product_name
                            ?? ($item->product?->product_name ?? 'Sản phẩm');

                        $alreadyReviewed = $reviewedItemIds->contains(
                            (int) $item->order_item_id
                        );
                    @endphp

                    <div class="buyer-orders__item">
                        <div class="buyer-orders__image">
                            @if ($imageUrl)
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $productName }}"
                                    loading="lazy"
                                    onerror="this.hidden = true; this.nextElementSibling.hidden = false;"
                                >
                            @endif

                            <i
                                class="bi bi-image"
                                aria-hidden="true"
                                @if ($imageUrl) hidden @endif
                            ></i>
                        </div>

                        <div>
                            <div class="buyer-orders__product-name">{{ $productName }}</div>

                            @if ($item->variant_name)
                                <div class="buyer-orders__muted">
                                    Phân loại: {{ $item->variant_name }}
                                </div>
                            @endif

                            <div class="buyer-orders__muted">
                                Số lượng: {{ number_format((int) $item->quantity) }}
                            </div>

                            @if ($order->order_status === 'completed' && $item->product && $alreadyReviewed)
                                <span class="badge text-bg-success mt-2">Đã đánh giá</span>
                            @endif
                        </div>

                        <div class="buyer-orders__item-price">
                            <strong>{{ number_format((float) $item->price, 0, ',', '.') }}₫</strong>
                            <div class="buyer-orders__muted">
                                Thành tiền:
                                {{ number_format((float) $item->total_price, 0, ',', '.') }}₫
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-secondary">
                        Đơn hàng chưa có thông tin sản phẩm.
                    </div>
                @endforelse

                <footer class="buyer-orders__footer">
                    <div>
                        <div class="buyer-orders__muted">Tổng thanh toán</div>
                        <div class="buyer-orders__total">
                            {{ number_format((float) $order->total_amount, 0, ',', '.') }}₫
                        </div>
                    </div>

                    <div class="buyer-orders__actions">
                        @if (in_array($order->order_status, ['pending', 'processing'], true))
                            <form
                                method="POST"
                                action="{{ route('store.account.orders.cancel', $order->order_id) }}"
                                onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')"
                            >
                                @csrf
                                @method('PATCH')

                                <button class="btn btn-outline-danger" type="submit">
                                    Hủy đơn hàng
                                </button>
                            </form>
                        @elseif ($order->order_status === 'shipping')
                            <form
                                method="POST"
                                action="{{ route('store.account.orders.confirm-receipt', $order->order_id) }}"
                                onsubmit="return confirm('Xác nhận bạn đã nhận được hàng?')"
                            >
                                @csrf
                                @method('PATCH')

                                <button class="btn buyer-orders__btn-primary" type="submit">
                                    <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                    Xác nhận đã nhận hàng
                                </button>
                            </form>
                        @elseif ($order->order_status === 'completed')
                            @foreach ($order->items as $reviewItem)
                                @php
                                    $reviewProductName = $reviewItem->product_name
                                        ?? ($reviewItem->product?->product_name ?? 'Sản phẩm');
                                @endphp

                                @if ($reviewItem->product && ! $reviewedItemIds->contains((int) $reviewItem->order_item_id))
                                    <button
                                        type="button"
                                        class="btn btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#reviewModal"
                                        data-review-url="{{ route('store.products.reviews.store', $reviewItem->product->product_id) }}"
                                        data-order-item-id="{{ $reviewItem->order_item_id }}"
                                        data-product-name="{{ $reviewProductName }}"
                                    >
                                        <i class="bi bi-star me-1" aria-hidden="true"></i>
                                        Đánh giá
                                    </button>
                                @endif
                            @endforeach

                            @if ($returnRequest)
                                <span class="badge {{ $returnBadgeClass }} py-2 px-3">
                                    <i class="bi bi-arrow-return-left me-1" aria-hidden="true"></i>
                                    {{ $returnLabel }}
                                </span>
                            @else
                                <button
                                    type="button"
                                    class="btn buyer-orders__btn-return"
                                    data-bs-toggle="modal"
                                    data-bs-target="#returnRequestModal"
                                    data-return-url="{{ route('store.account.orders.return-request', $order->order_id) }}"
                                    data-order-code="{{ $order->order_code ?? $order->order_id }}"
                                >
                                    <i class="bi bi-arrow-return-left me-1" aria-hidden="true"></i>
                                    Yêu cầu Trả hàng/Hoàn tiền
                                </button>
                            @endif

                            <a class="btn buyer-orders__btn-primary" href="{{ route('store.products.index') }}">
                                <i class="bi bi-arrow-repeat me-1" aria-hidden="true"></i>
                                Mua lại
                            </a>
                        @endif
                    </div>
                </footer>
            </article>
        @empty
            <section class="buyer-orders__panel buyer-orders__empty">
                <i class="bi bi-receipt fs-1 d-block mb-3" aria-hidden="true"></i>

                @if (($status ?? 'all') === 'return_requested')
                    <h2 class="h5 text-dark">Bạn chưa có yêu cầu trả hàng/hoàn tiền</h2>
                    <p class="mb-0">Các yêu cầu đã gửi sẽ hiển thị tại đây.</p>
                @elseif (($status ?? 'all') === 'cancelled')
                    <h2 class="h5 text-dark">Bạn chưa có đơn hàng đã hủy</h2>
                    <p class="mb-0">Đơn hàng đã hủy sẽ hiển thị tại đây.</p>
                @else
                    <h2 class="h5 text-dark">Bạn chưa có đơn hàng</h2>
                    <p class="mb-0">Các đơn hàng của bạn sẽ hiển thị tại đây.</p>
                @endif
            </section>
        @endforelse

        @if ($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </main>

    {{-- Modal gửi yêu cầu trả hàng/hoàn tiền --}}
    <div
        class="modal fade"
        id="returnRequestModal"
        tabindex="-1"
        aria-labelledby="returnRequestModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <form id="returnRequestForm" method="POST">
                    @csrf

                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title fs-5 fw-bold" id="returnRequestModalLabel">
                                Yêu cầu Trả hàng/Hoàn tiền
                            </h2>
                            <div class="small text-secondary">
                                Mã đơn: <span id="returnRequestOrderCode"></span>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Đóng"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <label for="returnRequestType" class="form-label fw-semibold">
                            Hình thức yêu cầu
                        </label>
                        <select id="returnRequestType" name="request_type" class="form-select mb-3" required>
                            <option value="">Chọn hình thức</option>
                            <option value="return">Trả hàng và hoàn tiền</option>
                            <option value="refund">Chỉ yêu cầu hoàn tiền</option>
                        </select>

                        <label for="returnRequestReason" class="form-label fw-semibold">
                            Lý do
                        </label>
                        <select id="returnRequestReason" name="reason" class="form-select mb-3" required>
                            <option value="">Chọn lý do</option>
                            <option value="damaged">Sản phẩm bị hư hỏng</option>
                            <option value="wrong_item">Nhận sai sản phẩm</option>
                            <option value="missing_parts">Sản phẩm thiếu phụ kiện</option>
                            <option value="not_as_described">Sản phẩm không đúng mô tả</option>
                            <option value="changed_mind">Đổi ý, không còn nhu cầu</option>
                            <option value="other">Lý do khác</option>
                        </select>

                        <label for="returnRequestDescription" class="form-label fw-semibold">
                            Mô tả thêm
                            <span class="text-secondary fw-normal">(không bắt buộc)</span>
                        </label>
                        <textarea
                            id="returnRequestDescription"
                            name="description"
                            rows="4"
                            maxlength="2000"
                            class="form-control"
                            placeholder="Mô tả tình trạng sản phẩm hoặc vấn đề bạn gặp phải"
                        ></textarea>

                        <div class="form-text">
                            Cửa hàng sẽ xem xét yêu cầu và phản hồi trong phần lịch sử đơn hàng.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Để sau
                        </button>
                        <button type="submit" class="btn buyer-orders__btn-primary">
                            Gửi yêu cầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal đánh giá sản phẩm --}}
    <div
        class="modal fade"
        id="reviewModal"
        tabindex="-1"
        aria-labelledby="reviewModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <form id="reviewForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="order_item_id" id="reviewOrderItemId">

                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title fs-5 fw-bold" id="reviewModalLabel">
                                Đánh giá sản phẩm
                            </h2>
                            <div class="small text-secondary" id="reviewProductName"></div>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Đóng"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <label for="reviewRating" class="form-label fw-semibold">Số sao</label>
                        <select id="reviewRating" name="rating" class="form-select mb-3" required>
                            <option value="">Chọn số sao</option>
                            @foreach ([5, 4, 3, 2, 1] as $star)
                                <option value="{{ $star }}">{{ $star }} sao</option>
                            @endforeach
                        </select>

                        <label for="reviewComment" class="form-label fw-semibold">Nhận xét</label>
                        <textarea
                            id="reviewComment"
                            name="comment"
                            rows="3"
                            maxlength="1000"
                            class="form-control mb-3"
                            placeholder="Chia sẻ trải nghiệm của bạn"
                        ></textarea>

                        <input
                            type="file"
                            name="images[]"
                            id="reviewImagesInput"
                            multiple
                            accept="image/jpeg,image/png,image/webp"
                            class="d-none"
                        >

                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            id="chooseReviewImages"
                        >
                            <i class="bi bi-camera me-1" aria-hidden="true"></i>
                            Thêm ảnh thực tế (Tối đa 5 ảnh)
                        </button>

                        <div
                            id="imagePreviewContainer"
                            class="d-flex flex-wrap gap-2 mt-3"
                        ></div>

                        <div class="form-text">
                            JPEG, PNG hoặc WEBP; tối đa 5 ảnh, mỗi ảnh không quá 5MB.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Đóng
                        </button>
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const returnModal = document.getElementById('returnRequestModal');
            const returnForm = document.getElementById('returnRequestForm');
            const returnOrderCode = document.getElementById('returnRequestOrderCode');

            if (returnModal && returnForm) {
                document.querySelectorAll('[data-bs-target="#returnRequestModal"]').forEach((button) => {
                    button.addEventListener('click', () => {
                        returnForm.action = button.dataset.returnUrl;
                        returnOrderCode.textContent = button.dataset.orderCode || '';
                    });
                });

                returnModal.addEventListener('hidden.bs.modal', () => {
                    returnForm.reset();
                });
            }

            const reviewModal = document.getElementById('reviewModal');
            const reviewForm = document.getElementById('reviewForm');
            const orderItemInput = document.getElementById('reviewOrderItemId');
            const productName = document.getElementById('reviewProductName');
            const fileInput = document.getElementById('reviewImagesInput');
            const chooseButton = document.getElementById('chooseReviewImages');
            const preview = document.getElementById('imagePreviewContainer');

            if (!reviewModal || !reviewForm || !fileInput || !preview || !chooseButton) {
                return;
            }

            document.querySelectorAll('[data-bs-target="#reviewModal"]').forEach((button) => {
                button.addEventListener('click', () => {
                    reviewForm.action = button.dataset.reviewUrl;
                    orderItemInput.value = button.dataset.orderItemId;
                    productName.textContent = button.dataset.productName || '';
                });
            });

            chooseButton.addEventListener('click', () => fileInput.click());

            function renderPreview() {
                preview.replaceChildren();

                [...fileInput.files].forEach((file, index) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'review-image-preview';

                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    image.alt = 'Ảnh đánh giá ' + (index + 1);

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'review-image-remove';
                    removeButton.textContent = '×';
                    removeButton.setAttribute('aria-label', 'Gỡ ảnh');

                    removeButton.addEventListener('click', () => {
                        const transfer = new DataTransfer();

                        [...fileInput.files].forEach((selectedFile, selectedIndex) => {
                            if (selectedIndex !== index) {
                                transfer.items.add(selectedFile);
                            }
                        });

                        fileInput.files = transfer.files;
                        renderPreview();
                    });

                    wrapper.append(image, removeButton);
                    preview.appendChild(wrapper);
                });
            }

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 5) {
                    alert('Bạn chỉ có thể tải tối đa 5 ảnh.');
                    fileInput.value = '';
                }

                renderPreview();
            });

            reviewModal.addEventListener('hidden.bs.modal', () => {
                reviewForm.reset();
                preview.replaceChildren();
            });
        })();
    </script>
@endsection



