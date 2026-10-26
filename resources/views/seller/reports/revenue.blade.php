@extends('seller.layouts.app')

@section('title', 'Báo cáo doanh thu - Seller Center')

@push('styles')
    <style>
        .revenue-page {
            color: var(--seller-text, #0f172a);
        }

        .revenue-card,
        .revenue-panel {
            color: var(--seller-text, #0f172a);
            background: var(--seller-card-bg, #fff);
            border: 1px solid var(--seller-border, #e2e8f0);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgb(15 23 42 / 4%);
        }

        .revenue-card {
            height: 100%;
            padding: 1.15rem;
        }

        .revenue-card__label,
        .revenue-muted {
            color: var(--seller-text-muted, #64748b);
        }

        .revenue-card__value {
            margin-top: .45rem;
            font-size: clamp(1.25rem, 2.4vw, 1.8rem);
            font-weight: 700;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .revenue-card__icon {
            display: inline-grid;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 12px;
            color: var(--seller-primary, #1a94ff);
            background: var(--seller-primary-light, #ebf5ff);
            font-size: 1.2rem;
        }

        .revenue-panel__header {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--seller-border, #e2e8f0);
        }

        .revenue-chart-scroll {
            overflow-x: auto;
            padding: 1.25rem;
        }

        .revenue-chart {
            display: flex;
            align-items: end;
            gap: .55rem;
            min-width: max-content;
            min-height: 230px;
            padding: 1rem .25rem 0;
            border-bottom: 1px solid var(--seller-border, #e2e8f0);
        }

        .revenue-chart__column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: end;
            width: 42px;
            height: 210px;
            gap: .45rem;
        }

        .revenue-chart__bar-wrap {
            display: flex;
            align-items: end;
            width: 100%;
            height: 170px;
        }

        .revenue-chart__bar {
            width: 100%;
            min-height: 3px;
            border-radius: 6px 6px 0 0;
            background: linear-gradient(180deg, #39a8ff, #1677e8);
        }

        .revenue-chart__date {
            color: var(--seller-text-muted, #64748b);
            font-size: .72rem;
            white-space: nowrap;
        }

        .revenue-table {
            --bs-table-color: var(--seller-text, #0f172a);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--seller-border, #e2e8f0);
            margin-bottom: 0;
        }

        .revenue-filter .form-control {
            min-height: 42px;
            color: var(--seller-text, #0f172a);
            background-color: var(--seller-input-bg, #fff);
            border-color: var(--seller-border, #e2e8f0);
        }

        [data-theme="dark"] .revenue-filter .form-control {
            color-scheme: dark;
        }
    </style>
@endpush

@section('content')
    @php
        $formatMoney = fn ($amount) => number_format((float) $amount, 0, ',', '.') . '₫';
    @endphp

    <main class="revenue-page container-fluid py-4">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Báo cáo doanh thu</h1>
                <p class="revenue-muted mb-0">Thống kê các đơn hàng đã hoàn thành của cửa hàng.</p>
            </div>
        </div>

        <section class="revenue-panel p-3 p-lg-4 mb-4">
            <form method="GET" action="{{ route('seller.reports.revenue') }}"
                class="revenue-filter row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="date_from" class="form-label fw-semibold">Từ ngày</label>
                    <input id="date_from" name="date_from" type="date" class="form-control"
                        value="{{ request('date_from', $dateFrom->toDateString()) }}">
                </div>
                <div class="col-12 col-md-4">
                    <label for="date_to" class="form-label fw-semibold">Đến ngày</label>
                    <input id="date_to" name="date_to" type="date" class="form-control"
                        value="{{ request('date_to', $dateTo->toDateString()) }}">
                </div>
                <div class="col-12 col-md-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1" aria-hidden="true"></i>Lọc
                    </button>
                    <button type="submit" formaction="{{ route('seller.reports.revenue.export') }}"
                        class="btn btn-outline-success flex-grow-1">
                        <i class="bi bi-file-earmark-spreadsheet me-1" aria-hidden="true"></i>Xuất Excel (CSV)
                    </button>
                    <a href="{{ route('seller.reports.revenue') }}" class="btn btn-outline-secondary">Đặt lại</a>
                </div>
            </form>
            @error('date_to')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('date_from')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </section>

        <section class="row g-3 mb-4" aria-label="Tổng quan doanh thu">
            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <span class="revenue-card__label">Doanh thu sản phẩm sau giảm giá</span>
                        <span class="revenue-card__icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
                    </div>
                    <div class="revenue-card__value text-primary">{{ $formatMoney($revenue) }}</div>
                    <small class="revenue-muted">Không bao gồm phí vận chuyển</small>
                </article>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <span class="revenue-card__label">Đơn đã hoàn thành</span>
                        <span class="revenue-card__icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
                    </div>
                    <div class="revenue-card__value">{{ number_format((int) $summary->completed_order_count) }}</div>
                    <small class="revenue-muted">Trong khoảng thời gian đã chọn</small>
                </article>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <span class="revenue-card__label">Sản phẩm đã bán</span>
                        <span class="revenue-card__icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
                    </div>
                    <div class="revenue-card__value">{{ number_format($soldQuantity) }}</div>
                    <small class="revenue-muted">Tính từ các đơn đã hoàn thành</small>
                </article>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <span class="revenue-card__label">Doanh thu trung bình / đơn</span>
                        <span class="revenue-card__icon"><i class="bi bi-graph-up" aria-hidden="true"></i></span>
                    </div>
                    <div class="revenue-card__value">{{ $formatMoney($averageOrderValue) }}</div>
                    <small class="revenue-muted">Doanh thu sản phẩm sau giảm giá</small>
                </article>
            </div>
        </section>

        <section class="revenue-panel mb-4">
            <header class="revenue-panel__header">
                <h2 class="h5 fw-bold mb-1">Doanh thu theo ngày</h2>
                <p class="revenue-muted small mb-0">
                    Chỉ tính đơn đã hoàn thành; biểu đồ có thể kéo ngang để xem hết ngày.
                </p>
            </header>

            @if ($chartRows->isEmpty() || (int) $summary->completed_order_count === 0)
                <div class="text-center p-5">
                    <i class="bi bi-bar-chart-line fs-1 revenue-muted" aria-hidden="true"></i>
                    <p class="fw-semibold mt-3 mb-1">Chưa có doanh thu trong khoảng thời gian này</p>
                    <p class="revenue-muted mb-0">Doanh thu sẽ xuất hiện khi đơn hàng được hoàn thành.</p>
                </div>
            @else
                <div class="revenue-chart-scroll">
                    <div class="revenue-chart" role="img" aria-label="Biểu đồ doanh thu theo ngày">
                        @foreach ($chartRows as $row)
                            @php
                                $height = $row['revenue'] > 0
                                    ? max(4, ($row['revenue'] / $maxDailyRevenue) * 170)
                                    : 3;
                            @endphp
                            <div class="revenue-chart__column"
                                title="{{ $row['date']->format('d/m/Y') }}: {{ $formatMoney($row['revenue']) }} · {{ $row['order_count'] }} đơn">
                                <div class="revenue-chart__bar-wrap">
                                    <div class="revenue-chart__bar" style="height: {{ $height }}px"></div>
                                </div>
                                <span class="revenue-chart__date">{{ $row['date']->format('d/m') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <section class="revenue-panel">
            <header class="revenue-panel__header">
                <h2 class="h5 fw-bold mb-1">Sản phẩm bán chạy</h2>
                <p class="revenue-muted small mb-0">Xếp theo số lượng bán trong các đơn đã hoàn thành.</p>
            </header>

            @if ($topProducts->isEmpty())
                <div class="text-center p-5">
                    <p class="revenue-muted mb-0">Chưa có dữ liệu sản phẩm trong khoảng thời gian này.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table revenue-table align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4" scope="col">#</th>
                                <th scope="col">Sản phẩm</th>
                                <th class="text-end" scope="col">Đã bán</th>
                                <th class="text-end" scope="col">Số đơn</th>
                                <th class="text-end pe-4" scope="col">Doanh số sản phẩm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $index => $product)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $product->product_name }}</td>
                                    <td class="text-end">{{ number_format((int) $product->units_sold) }}</td>
                                    <td class="text-end">{{ number_format((int) $product->order_count) }}</td>
                                    <td class="text-end pe-4">{{ $formatMoney($product->product_sales) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
@endsection
