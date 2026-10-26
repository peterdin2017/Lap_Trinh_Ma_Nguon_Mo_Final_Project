@extends('seller.layouts.app')

@section('title', 'Tổng quan - Seller Center')

@section('content')
    @php
        $productCount = $productCount ?? 0;
        $newOrderCount = $newOrderCount ?? ($pendingOrderCount ?? 0);
        $processingOrderCount = $processingOrderCount ?? 0;
        $revenue = $revenue ?? 0;
        $recentOrders = $recentOrders ?? collect();

        $stats = [
            [
                'label' => 'Sản phẩm',
                'value' => number_format((int) $productCount),
                'icon' => 'box-seam',
            ],
            [
                'label' => 'Đơn hàng mới',
                'value' => number_format((int) $newOrderCount),
                'icon' => 'bag',
            ],
            [
                'label' => 'Đơn cần xử lý',
                'value' => number_format((int) $processingOrderCount),
                'icon' => 'clock-history',
            ],
            [
                'label' => 'Doanh thu',
                'value' => number_format((float) $revenue, 0, ',', '.') . '₫',
                'icon' => 'currency-exchange',
            ],
        ];

        $orderStatusLabels = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang vận chuyển',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $orderStatusClasses = [
            'pending' => 'warning',
            'processing' => 'primary',
            'shipping' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
    @endphp

    <style>
        .seller-dashboard {
            color: var(--seller-text, #0f172a);
        }

        .seller-dashboard__hero {
            position: relative;
            display: flex;
            min-height: 190px;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            overflow: hidden;
            margin-bottom: 24px;
            padding: 32px 40px;
            border-radius: 14px;
            background: linear-gradient(115deg, #163b63 0%, #087ca8 58%, #1597bc 100%);
            color: #fff;
        }

        .seller-dashboard__hero-content {
            position: relative;
            z-index: 1;
        }

        .seller-dashboard__eyebrow {
            display: inline-block;
            margin-bottom: 12px;
            color: #fdba74;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .12em;
        }

        .seller-dashboard__hero h2 {
            margin-bottom: 8px;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 800;
        }

        .seller-dashboard__hero p {
            margin: 0;
            color: rgb(255 255 255 / 82%);
        }

        .seller-dashboard__hero-icon {
            flex: 0 0 auto;
            margin-right: 20px;
            color: rgb(255 255 255 / 22%);
            font-size: 5rem;
        }

        .seller-dashboard__kpi {
            position: relative;
            min-height: 148px;
            overflow: hidden;
            padding: 24px;
            border: 1px solid var(--seller-border, #334155);
            border-radius: 12px;
            background: var(--seller-card-bg, #fff);
            box-shadow: var(--seller-shadow, 0 3px 12px rgb(15 23 42 / 6%));
            color: var(--seller-text, #0f172a);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .seller-dashboard__kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 18px rgb(15 23 42 / 12%);
        }

        .seller-dashboard__kpi-label {
            margin-bottom: 12px;
            color: var(--seller-text-muted, #64748b);
        }

        .seller-dashboard__kpi-value {
            overflow-wrap: anywhere;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
        }

        .seller-dashboard__kpi-icon {
            position: absolute;
            top: 25px;
            right: 24px;
            color: #ff9d3d;
            font-size: 1.9rem;
        }

        .seller-dashboard__panel {
            height: 100%;
            overflow: hidden;
            border: 1px solid var(--seller-border, #dee2e6);
            border-radius: 12px;
            background: var(--seller-card-bg, #fff);
            box-shadow: var(--seller-shadow, 0 3px 12px rgb(15 23 42 / 6%));
            color: var(--seller-text, #0f172a);
        }

        .seller-dashboard__panel-heading {
            display: flex;
            min-height: 68px;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--seller-border, #dee2e6);
        }

        .seller-dashboard__panel-heading h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 750;
        }

        .seller-dashboard__panel-link {
            color: var(--seller-primary, #1688ff);
            font-size: .9rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .seller-dashboard__panel-link:hover {
            text-decoration: underline;
        }

        .seller-dashboard__quick-links {
            display: flex;
            flex-direction: column;
        }

        .seller-dashboard__quick-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 17px 22px;
            border-bottom: 1px solid var(--seller-border, #dee2e6);
            color: var(--seller-text, #0f172a);
            text-decoration: none;
            transition: background-color .15s ease;
        }

        .seller-dashboard__quick-link:last-child {
            border-bottom: 0;
        }

        .seller-dashboard__quick-link:hover {
            background: var(--seller-primary-light, rgb(22 136 255 / 8%));
            color: var(--seller-primary, #1688ff);
        }

        .seller-dashboard__quick-link > i:first-child {
            width: 22px;
            color: #ff9d3d;
            font-size: 1.1rem;
            text-align: center;
        }

        .seller-dashboard__quick-link .seller-dashboard__arrow {
            margin-left: auto;
            color: var(--seller-text-muted, #64748b);
        }

        .seller-dashboard .seller-dashboard__table {
            --bs-table-color: var(--seller-text, #0f172a);
            --bs-table-bg: var(--seller-card-bg, #fff);
            --bs-table-border-color: var(--seller-border, #dee2e6);
            --bs-table-striped-color: var(--seller-text, #0f172a);
            --bs-table-striped-bg: var(--seller-hover-bg, rgb(15 23 42 / 4%));
            --bs-table-hover-color: var(--seller-text, #0f172a);
            --bs-table-hover-bg: var(--seller-primary-light, rgb(22 136 255 / 8%));

            width: 100%;
            margin: 0;
            color: var(--seller-text, #0f172a);
            vertical-align: middle;
            background-color: var(--seller-card-bg, #fff);
        }

        .seller-dashboard .seller-dashboard__table > :not(caption) > * > * {
            color: inherit;
            background-color: var(--seller-card-bg, #fff) !important;
            border-color: var(--seller-border, #dee2e6);
            box-shadow: none;
        }

        .seller-dashboard .seller-dashboard__table thead th {
            padding: 14px 18px;
            color: var(--seller-text-muted, #64748b);
            font-size: .82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .seller-dashboard .seller-dashboard__table tbody td {
            padding: 15px 18px;
        }

        .seller-dashboard .seller-dashboard__table tbody tr:last-child td {
            border-bottom: 0;
        }

        .seller-dashboard__order-code {
            color: var(--seller-primary, #1688ff);
            font-weight: 750;
            text-decoration: none;
        }

        .seller-dashboard__order-code:hover {
            text-decoration: underline;
        }

        .seller-dashboard__empty {
            padding: 42px 20px;
            color: var(--seller-text-muted, #64748b);
            text-align: center;
        }

        .seller-dashboard__empty i {
            display: block;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        @media (max-width: 767.98px) {
            .seller-dashboard__hero {
                min-height: 160px;
                padding: 24px;
            }

            .seller-dashboard__hero-icon {
                margin-right: 0;
                font-size: 3.5rem;
            }

            .seller-dashboard__kpi {
                min-height: 130px;
                padding: 18px;
            }

            .seller-dashboard__kpi-icon {
                top: 18px;
                right: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .seller-dashboard__kpi,
            .seller-dashboard__quick-link {
                transition: none;
            }

            .seller-dashboard__kpi:hover {
                transform: none;
            }
        }
    </style>

    <div class="seller-dashboard">
        <section class="seller-dashboard__hero">
            <div class="seller-dashboard__hero-content">
                <span class="seller-dashboard__eyebrow">
                    SELLER CENTER / DASHBOARD
                </span>
                <h2>Tổng quan cửa hàng</h2>
                <p>
                    Theo dõi sản phẩm, đơn hàng và doanh thu của cửa hàng.
                </p>
            </div>

            <i
                class="bi bi-shop-window seller-dashboard__hero-icon"
                aria-hidden="true"
            ></i>
        </section>

        <div class="row g-3 mb-4">
            @foreach ($stats as $stat)
                <div class="col-6 col-xl-3">
                    <article class="seller-dashboard__kpi h-100">
                        <div class="seller-dashboard__kpi-label">
                            {{ $stat['label'] }}
                        </div>

                        <div class="seller-dashboard__kpi-value">
                            {{ $stat['value'] }}
                        </div>

                        <i
                            class="bi bi-{{ $stat['icon'] }} seller-dashboard__kpi-icon"
                            aria-hidden="true"
                        ></i>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="row g-3">
            <div class="col-lg-5">
                <section class="seller-dashboard__panel">
                    <header class="seller-dashboard__panel-heading">
                        <h3>Thao tác nhanh</h3>
                    </header>

                    <nav class="seller-dashboard__quick-links" aria-label="Thao tác nhanh">
                        <a
                            href="{{ route('seller.products.create') }}"
                            class="seller-dashboard__quick-link"
                        >
                            <i class="bi bi-plus-square" aria-hidden="true"></i>
                            <span>Thêm sản phẩm</span>
                            <i class="bi bi-arrow-right seller-dashboard__arrow" aria-hidden="true"></i>
                        </a>

                        <a
                            href="{{ route('seller.products.index') }}"
                            class="seller-dashboard__quick-link"
                        >
                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                            <span>Quản lý sản phẩm</span>
                            <i class="bi bi-arrow-right seller-dashboard__arrow" aria-hidden="true"></i>
                        </a>

                        <a
                            href="{{ route('seller.orders.index') }}"
                            class="seller-dashboard__quick-link"
                        >
                            <i class="bi bi-receipt" aria-hidden="true"></i>
                            <span>Quản lý đơn hàng</span>
                            <i class="bi bi-arrow-right seller-dashboard__arrow" aria-hidden="true"></i>
                        </a>

                        <a
                            href="{{ route('seller.vouchers.index') }}"
                            class="seller-dashboard__quick-link"
                        >
                            <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
                            <span>Quản lý voucher Shop</span>
                            <i class="bi bi-arrow-right seller-dashboard__arrow" aria-hidden="true"></i>
                        </a>

                        <a
                            href="{{ route('seller.shop-information.edit') }}"
                            class="seller-dashboard__quick-link"
                        >
                            <i class="bi bi-shop" aria-hidden="true"></i>
                            <span>Cập nhật thông tin Shop</span>
                            <i class="bi bi-arrow-right seller-dashboard__arrow" aria-hidden="true"></i>
                        </a>
                    </nav>
                </section>
            </div>

            <div class="col-lg-7">
                <section class="seller-dashboard__panel">
                    <header class="seller-dashboard__panel-heading">
                        <h3>Đơn hàng gần đây</h3>

                        <a
                            href="{{ route('seller.orders.index') }}"
                            class="seller-dashboard__panel-link"
                        >
                            Xem tất cả
                            <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                        </a>
                    </header>

                    <div class="table-responsive">
                        <table class="table seller-dashboard__table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($recentOrders as $order)
                                    @php
                                        $status = $order->order_status ?? '';
                                        $statusLabel = $orderStatusLabels[$status] ?? 'Không xác định';
                                        $statusClass = $orderStatusClasses[$status] ?? 'secondary';
                                    @endphp

                                    <tr>
                                        <td>
                                            <a
                                                href="{{ route('seller.orders.show', $order->order_id) }}"
                                                class="seller-dashboard__order-code"
                                            >
                                                {{ $order->order_code ?? ('#' . $order->order_id) }}
                                            </a>
                                        </td>
                                        <td class="text-nowrap">
                                            {{ $order->created_at?->format('d/m/Y H:i') ?? '—' }}
                                        </td>
                                        <td class="text-nowrap fw-semibold">
                                            {{ number_format((float) ($order->total_amount ?? 0), 0, ',', '.') }}₫
                                        </td>
                                        <td>
                                            <span class="badge text-bg-{{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="seller-dashboard__empty">
                                                <i class="bi bi-receipt" aria-hidden="true"></i>
                                                <div class="fw-semibold">Chưa có đơn hàng</div>
                                                <div class="small mt-1">
                                                    Đơn hàng mới sẽ hiển thị tại đây.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
