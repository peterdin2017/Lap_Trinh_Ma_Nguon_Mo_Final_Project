@extends('seller.layouts.app')

@section('title', 'Quản lý đơn hàng - Seller Center')

@section('content')
    @php
        $status = $filters['status'] ?? 'all';
        $search = $filters['q'] ?? '';

        $allOrdersCount = $allOrdersCount ?? ($statusCounts?->sum() ?? 0);
        $returnRequests = $returnRequests ?? collect();

        $tabs = [
            'all' => ['Tất cả', $allOrdersCount],
            'pending' => ['Chờ xác nhận', $statusCounts['pending'] ?? 0],
            'processing' => ['Đang xử lý', $statusCounts['processing'] ?? 0],
            'shipping' => ['Đang vận chuyển', $statusCounts['shipping'] ?? 0],
            'completed' => ['Đã hoàn thành', $statusCounts['completed'] ?? 0],
            'cancelled' => ['Đơn hủy / Trả hàng', $statusCounts['cancelled'] ?? 0],
        ];

        $statusMeta = [
            'pending' => ['Chờ xác nhận', 'order-status--pending'],
            'processing' => ['Đang xử lý', 'order-status--processing'],
            'shipping' => ['Đang vận chuyển', 'order-status--shipping'],
            'completed' => ['Đã hoàn thành', 'order-status--completed'],
            'cancelled' => ['Đã hủy', 'order-status--cancelled'],
        ];

        $returnStatusMeta = [
            'pending' => ['Chờ xử lý yêu cầu trả hàng', 'order-status--return'],
            'approved' => ['Đã chấp nhận trả hàng', 'order-status--return-approved'],
            'rejected' => ['Đã từ chối yêu cầu', 'order-status--cancelled'],
            'completed' => ['Đã xử lý yêu cầu', 'order-status--completed'],
        ];

        $returnReasonLabels = [
            'damaged' => 'Sản phẩm bị hư hỏng',
            'wrong_item' => 'Nhận sai sản phẩm',
            'missing_parts' => 'Sản phẩm thiếu phụ kiện',
            'not_as_described' => 'Sản phẩm không đúng mô tả',
            'changed_mind' => 'Khách đổi ý, không còn nhu cầu',
            'other' => 'Lý do khác',
        ];

        $paymentLabels = [
            'cod' => 'COD',
            'vnpay' => 'VNPAY',
            'bank_transfer' => 'Chuyển khoản',
        ];
    @endphp

    <style>
        .seller-orders {
            --orders-primary: var(--seller-primary, #1a94ff);
            --orders-text: var(--seller-text, #0f172a);
            --orders-muted: var(--seller-text-muted, #64748b);
            --orders-surface: var(--seller-card-bg, #fff);
            --orders-soft: var(--seller-hover-bg, #f8fafc);
            --orders-border: var(--seller-border, #e2e8f0);
            color: var(--orders-text);
        }

        .seller-orders__panel,
        .seller-orders__card {
            color: var(--orders-text);
            background: var(--orders-surface);
            border: 1px solid var(--orders-border);
            border-radius: 12px;
            box-shadow: var(--seller-shadow, 0 4px 16px rgba(15, 23, 42, .035));
        }

        .seller-orders__panel {
            overflow: hidden;
        }

        .seller-orders__tabs {
            display: flex;
            gap: 1.25rem;
            overflow-x: auto;
            border-bottom: 1px solid var(--orders-border);
            scrollbar-width: thin;
        }

        .seller-orders__tabs a {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .65rem .1rem .8rem;
            color: var(--orders-muted);
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
        }

        .seller-orders__tabs a:hover,
        .seller-orders__tabs a.active {
            color: var(--orders-primary);
        }

        .seller-orders__tabs a.active {
            border-color: var(--orders-primary);
            font-weight: 700;
        }

        .seller-orders__filter .form-control,
        .seller-orders__filter .form-select {
            min-height: 42px;
            color: var(--orders-text);
            background-color: var(--orders-surface);
            border-color: var(--orders-border);
            border-radius: 8px;
        }

        .seller-orders__filter .form-control::placeholder {
            color: var(--orders-muted);
            opacity: .8;
        }

        .seller-orders__filter .form-control:focus,
        .seller-orders__filter .form-select:focus {
            color: var(--orders-text);
            background-color: var(--orders-surface);
            border-color: var(--orders-primary);
            box-shadow: 0 0 0 .18rem rgba(26, 148, 255, .14);
        }

        .seller-orders__card {
            overflow: hidden;
            margin-bottom: 14px;
        }

        .seller-orders__card-head {
            padding: 14px 18px;
            background: var(--orders-soft);
            border-bottom: 1px solid var(--orders-border);
        }

        .seller-orders__code {
            color: var(--orders-primary);
            font-weight: 700;
            text-decoration: none;
        }

        .seller-orders__code:hover {
            color: var(--orders-primary);
            text-decoration: underline;
        }

        .seller-orders__return-notice {
            margin: 14px 18px 0;
            padding: 12px 14px;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            background: #fff7ed;
            color: #7c2d12;
        }

        .seller-orders__return-notice-title {
            font-weight: 700;
        }

        .seller-orders__item {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 12px 18px;
            border-bottom: 1px solid var(--orders-border);
        }

        .seller-orders__item:last-child {
            border-bottom: 0;
        }

        .seller-orders__image {
            width: 52px;
            height: 52px;
            object-fit: cover;
            color: var(--orders-muted);
            border: 1px solid var(--orders-border);
            border-radius: 8px;
            background: var(--orders-soft);
        }

        .seller-orders__item-name {
            color: var(--orders-text);
            font-weight: 600;
            line-height: 1.35;
        }

        .seller-orders__item-price {
            color: var(--orders-text);
        }

        .seller-orders__summary {
            padding: 14px 18px;
            background: var(--orders-surface);
            border-top: 1px solid var(--orders-border);
        }

        .seller-orders .text-secondary,
        .seller-orders__empty {
            color: var(--orders-muted) !important;
        }

        .seller-orders .text-dark {
            color: var(--orders-text) !important;
        }

        .seller-orders .badge.text-bg-light {
            color: var(--orders-text) !important;
            background: var(--orders-soft) !important;
            border-color: var(--orders-border) !important;
        }

        .order-status {
            display: inline-flex;
            align-items: center;
            padding: .34rem .65rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-status--pending {
            color: #854d0e;
            background: #fef3c7;
        }

        .order-status--processing {
            color: #075985;
            background: #e0f2fe;
        }

        .order-status--shipping {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .order-status--completed {
            color: #166534;
            background: #dcfce7;
        }

        .order-status--cancelled {
            color: #991b1b;
            background: #fee2e2;
        }

        .order-status--return {
            color: #9a3412;
            background: #ffedd5;
        }

        .order-status--return-approved {
            color: #075985;
            background: #e0f2fe;
        }

        .seller-orders__empty {
            padding: 56px 20px;
            text-align: center;
        }

        .seller-orders__count {
            color: var(--orders-text);
            background: var(--orders-soft);
            border: 1px solid var(--orders-border);
        }

        @media (max-width: 575.98px) {
            .seller-orders__item {
                grid-template-columns: 44px minmax(0, 1fr);
                padding: 10px 12px;
            }

            .seller-orders__image {
                width: 44px;
                height: 44px;
            }

            .seller-orders__item-price {
                grid-column: 2;
            }

            .seller-orders__card-head,
            .seller-orders__summary {
                padding: 12px;
            }

            .seller-orders__return-notice {
                margin-right: 12px;
                margin-left: 12px;
            }
        }
    </style>

    <main class="seller-orders">
        <header class="mb-3">
            <h1 class="h4 fw-bold mb-1">Quản lý đơn hàng</h1>
            <p class="small text-secondary mb-0">
                {{ number_format($orders->total()) }} đơn hàng phù hợp
            </p>
        </header>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="seller-orders__panel p-3 p-lg-4 mb-3">
            <nav class="seller-orders__tabs mb-3" aria-label="Lọc trạng thái đơn hàng">
                @foreach ($tabs as $value => [$label, $count])
                    <a
                        href="{{ route('seller.orders.index', array_merge(request()->except(['status', 'page']), ['status' => $value])) }}"
                        class="{{ $status === $value ? 'active' : '' }}"
                        @if ($status === $value) aria-current="page" @endif
                    >
                        <span>{{ $label }}</span>

                        @if ((int) $count > 0)
                            <span class="badge rounded-pill {{ $value === 'pending' ? 'text-bg-danger' : 'seller-orders__count' }}">
                                {{ number_format((int) $count) }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <form method="GET" action="{{ route('seller.orders.index') }}" class="seller-orders__filter">
                <input type="hidden" name="status" value="{{ $status }}">

                <div class="row g-2 align-items-end">
                    <div class="col-12 col-lg-5">
                        <label for="order-search" class="form-label small fw-semibold">
                            Mã đơn, tên hoặc số điện thoại khách
                        </label>
                        <input
                            id="order-search"
                            type="search"
                            name="q"
                            value="{{ $search }}"
                            class="form-control"
                            maxlength="100"
                            placeholder="Nhập thông tin cần tìm"
                        >
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="date-from" class="form-label small fw-semibold">Từ ngày</label>
                        <input
                            id="date-from"
                            type="date"
                            name="date_from"
                            value="{{ $filters['date_from'] ?? '' }}"
                            class="form-control"
                        >
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="date-to" class="form-label small fw-semibold">Đến ngày</label>
                        <input
                            id="date-to"
                            type="date"
                            name="date_to"
                            value="{{ $filters['date_to'] ?? '' }}"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-lg-3 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" type="submit">
                            <i class="bi bi-search me-1" aria-hidden="true"></i>Lọc
                        </button>

                        <a
                            href="{{ route('seller.orders.index', $status === 'all' ? [] : ['status' => $status]) }}"
                            class="btn btn-outline-secondary"
                        >
                            Xóa lọc
                        </a>
                    </div>
                </div>
            </form>
        </section>

        @forelse ($orders as $order)
            @php
                $returnRequest = $returnRequests->get($order->order_id);

                if ($returnRequest) {
                    [$statusLabel, $statusClass] = $returnStatusMeta[$returnRequest->status]
                        ?? ['Có yêu cầu trả hàng', 'order-status--return'];
                } else {
                    [$statusLabel, $statusClass] = $statusMeta[$order->order_status] ?? [
                        $order->order_status,
                        'order-status--processing',
                    ];
                }

                $returnTypeLabel = match ($returnRequest?->request_type) {
                    'return' => 'Trả hàng và hoàn tiền',
                    'refund' => 'Chỉ yêu cầu hoàn tiền',
                    default => null,
                };

                $returnReasonLabel = $returnReasonLabels[$returnRequest?->reason]
                    ?? $returnRequest?->reason;
            @endphp

            <article class="seller-orders__card">
                <header class="seller-orders__card-head d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a class="seller-orders__code" href="{{ route('seller.orders.show', $order->order_id) }}">
                            {{ $order->order_code }}
                        </a>

                        <span class="small text-secondary">
                            <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                            {{ $order->created_at?->format('d/m/Y H:i') }}
                        </span>

                        <span class="small">
                            <i class="bi bi-person me-1" aria-hidden="true"></i>
                            {{ $order->shipping_name }}
                        </span>
                    </div>

                    <span class="order-status {{ $statusClass }}">{{ $statusLabel }}</span>
                </header>

                @if ($returnRequest)
                    <section class="seller-orders__return-notice" aria-label="Thông tin yêu cầu trả hàng">
                        <div class="seller-orders__return-notice-title">
                            <i class="bi bi-arrow-return-left me-1" aria-hidden="true"></i>
                            Khách yêu cầu {{ $returnTypeLabel }}
                        </div>

                        <div class="small mt-1">
                            <strong>Lý do:</strong> {{ $returnReasonLabel }}
                        </div>

                        @if ($returnRequest->description)
                            <div class="small mt-1">
                                <strong>Mô tả:</strong> {{ $returnRequest->description }}
                            </div>
                        @endif

                        <div class="small mt-1">
                            Sản phẩm thuộc yêu cầu được hiển thị bên dưới.
                        </div>

                        @if ($returnRequest->status === 'pending')
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <form
                                    method="POST"
                                    action="{{ route('seller.orders.return-requests.update', $returnRequest->return_request_id) }}"
                                    onsubmit="return confirm('Chấp nhận yêu cầu trả hàng/hoàn tiền này?')"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="decision" value="approved">

                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                                        Chấp nhận
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('seller.orders.return-requests.update', $returnRequest->return_request_id) }}"
                                    onsubmit="return confirm('Từ chối yêu cầu trả hàng/hoàn tiền này?')"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="decision" value="rejected">

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                                        Từ chối
                                    </button>
                                </form>
                            </div>
                        @endif
                    </section>
                @endif

                <div>
                    @foreach ($order->items as $item)
                        @php
                            $itemImage = $item->variant?->image_url ?: $item->product?->image_url;
                        @endphp

                        <div class="seller-orders__item">
                            @if ($itemImage)
                                <img
                                    class="seller-orders__image"
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($itemImage) }}"
                                    alt="{{ $item->product_name }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="seller-orders__image d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-secondary" aria-hidden="true"></i>
                                </div>
                            @endif

                            <div>
                                <div class="seller-orders__item-name">{{ $item->product_name }}</div>

                                @if ($item->variant_name)
                                    <div class="small text-secondary">
                                        Phân loại: {{ $item->variant_name }}
                                    </div>
                                @endif

                                <div class="small text-secondary">
                                    Số lượng: {{ number_format($item->quantity) }}
                                    × {{ number_format((float) $item->price, 0, ',', '.') }}₫
                                </div>

                                @if ($returnRequest)
                                    <span class="badge text-bg-warning mt-1">
                                        Sản phẩm trong yêu cầu trả
                                    </span>
                                @endif
                            </div>

                            <div class="seller-orders__item-price text-end fw-semibold">
                                {{ number_format((float) $item->total_price, 0, ',', '.') }}₫
                            </div>
                        </div>
                    @endforeach
                </div>

                <footer class="seller-orders__summary d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="small text-secondary">
                            Khách:
                            <strong class="text-dark">{{ $order->shipping_name }}</strong>
                            · {{ $order->shipping_phone }}
                        </span>

                        <span class="badge text-bg-light border">
                            {{ $paymentLabels[$order->payment_method] ?? strtoupper($order->payment_method) }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-md-end gap-2">
                        <div class="me-md-2 text-end">
                            <div class="small text-secondary">Tổng thanh toán</div>
                            <strong class="text-danger fs-5">
                                {{ number_format((float) $order->total_amount, 0, ',', '.') }}₫
                            </strong>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#orderDetail{{ $order->order_id }}"
                        >
                            <i class="bi bi-eye me-1" aria-hidden="true"></i>Chi tiết
                        </button>

                        @if ($order->order_status === 'pending')
                            <form method="POST" action="{{ route('seller.orders.status', $order->order_id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="processing">

                                <button class="btn btn-sm btn-primary" type="submit">
                                    <i class="bi bi-check2 me-1" aria-hidden="true"></i>Xác nhận đơn
                                </button>
                            </form>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#cancelOrder{{ $order->order_id }}"
                            >
                                Hủy đơn
                            </button>
                        @elseif ($order->order_status === 'processing')
                            <a
                                class="btn btn-sm btn-outline-secondary"
                                href="{{ route('seller.orders.shipping-label', $order->order_id) }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="bi bi-printer me-1" aria-hidden="true"></i>In phiếu
                            </a>

                            <form method="POST" action="{{ route('seller.orders.status', $order->order_id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="shipping">

                                <button class="btn btn-sm btn-primary" type="submit">
                                    Bàn giao vận chuyển
                                </button>
                            </form>
                        @elseif ($order->order_status === 'shipping')
                            <a
                                class="btn btn-sm btn-outline-secondary"
                                href="{{ route('seller.orders.shipping-label', $order->order_id) }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="bi bi-printer me-1" aria-hidden="true"></i>In phiếu
                            </a>

                            <span class="small text-secondary"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Chờ khách xác nhận đã nhận hàng</span>
                        @endif
                    </div>
                </footer>
            </article>

            @include('seller.orders.partials.detail-modal', [
                'order' => $order,
                'statusLabel' => $statusLabel,
                'statusClass' => $statusClass,
                'paymentLabels' => $paymentLabels,
                'returnRequest' => $returnRequest,
            ])
        @empty
            <div class="seller-orders__panel seller-orders__empty">
                <i class="bi bi-receipt fs-1 d-block mb-3" aria-hidden="true"></i>
                <h2 class="h5 text-dark">Chưa có đơn hàng phù hợp</h2>
                <p class="mb-0">Thử đổi trạng thái hoặc điều kiện tìm kiếm.</p>
            </div>
        @endforelse

        @if ($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </main>
@endsection

