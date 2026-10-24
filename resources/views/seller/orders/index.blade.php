@extends('seller.layouts.app')

@section('title', 'Quản lý đơn hàng - Seller Center')

@section('content')
    @php
        $status = $filters['status'] ?? 'all';
        $search = $filters['q'] ?? '';
        $allOrdersCount = $statusCounts->sum();

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

        $paymentLabels = [
            'cod' => 'COD',
            'vnpay' => 'VNPAY',
            'bank_transfer' => 'Chuyển khoản',
        ];
    @endphp

    <style>
        .seller-orders { color: #0f172a; }
        .seller-orders__panel,
        .seller-orders__card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, .035);
        }
        .seller-orders__panel { overflow: hidden; }
        .seller-orders__tabs {
            display: flex;
            gap: 1.25rem;
            overflow-x: auto;
            border-bottom: 1px solid #e2e8f0;
            scrollbar-width: thin;
        }
        .seller-orders__tabs a {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .65rem .1rem .8rem;
            color: #64748b;
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
        }
        .seller-orders__tabs a:hover,
        .seller-orders__tabs a.active { color: #1a94ff; }
        .seller-orders__tabs a.active {
            border-color: #1a94ff;
            font-weight: 700;
        }
        .seller-orders__filter .form-control {
            min-height: 42px;
            border-color: #e2e8f0;
            border-radius: 8px;
        }
        .seller-orders__card { overflow: hidden; margin-bottom: 14px; }
        .seller-orders__card-head {
            padding: 14px 18px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .seller-orders__code {
            color: #1a94ff;
            font-weight: 700;
            text-decoration: none;
        }
        .seller-orders__item {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
        }
        .seller-orders__item:last-child { border-bottom: 0; }
        .seller-orders__image {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }
        .seller-orders__item-name { font-weight: 600; line-height: 1.35; }
        .seller-orders__summary {
            padding: 14px 18px;
            border-top: 1px solid #e2e8f0;
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
        .order-status--pending { color: #854d0e; background: #fef3c7; }
        .order-status--processing { color: #075985; background: #e0f2fe; }
        .order-status--shipping { color: #1d4ed8; background: #dbeafe; }
        .order-status--completed { color: #166534; background: #dcfce7; }
        .order-status--cancelled { color: #991b1b; background: #fee2e2; }
        .seller-orders .modal-content { border: 0; border-radius: 14px; }
        .seller-orders .modal-header { border-bottom: 1px solid #e2e8f0; }
        .seller-orders__empty {
            padding: 56px 20px;
            color: #64748b;
            text-align: center;
        }
        @media (max-width: 575.98px) {
            .seller-orders__item {
                grid-template-columns: 44px minmax(0, 1fr);
                padding: 10px 12px;
            }
            .seller-orders__image { width: 44px; height: 44px; }
            .seller-orders__item-price { grid-column: 2; }
            .seller-orders__card-head,
            .seller-orders__summary { padding: 12px; }
        }
    </style>

    <main class="seller-orders">
        <header class="mb-3">
            <h1 class="h4 fw-bold mb-1">Quản lý đơn hàng</h1>
            <p class="small text-secondary mb-0">
                {{ number_format($orders->total()) }} đơn hàng phù hợp
            </p>
        </header>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                        {{ $label }}

                        @if ($value === 'pending')
                            <span class="badge rounded-pill text-bg-danger">{{ $count }}</span>
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
                [$statusLabel, $statusClass] = $statusMeta[$order->order_status]
                    ?? [$order->order_status, 'order-status--processing'];
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
                                    alt=""
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
                                    <div class="small text-secondary">{{ $item->variant_name }}</div>
                                @endif
                                <div class="small text-secondary">
                                    Số lượng: {{ number_format($item->quantity) }}
                                    × {{ number_format((float) $item->price, 0, ',', '.') }}₫
                                </div>
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

                            <form method="POST" action="{{ route('seller.orders.status', $order->order_id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button class="btn btn-sm btn-success" type="submit">
                                    Đã giao thành công
                                </button>
                            </form>
                        @endif
                    </div>
                </footer>
            </article>

            @include('seller.orders.partials.detail-modal', [
                'order' => $order,
                'statusLabel' => $statusLabel,
                'statusClass' => $statusClass,
                'paymentLabels' => $paymentLabels,
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
