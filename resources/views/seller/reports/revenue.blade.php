@extends('seller.layouts.app')

@section('title', 'Báo cáo doanh thu - Seller Center')

@push('styles')
    <style>
        .revenue-page {
            --revenue-surface: var(--seller-card-bg, #fff);
            --revenue-border: var(--seller-border, #e2e8f0);
            --revenue-text: var(--seller-text, #0f172a);
            --revenue-muted: var(--seller-text-muted, #64748b);
            --revenue-blue: var(--seller-primary, #1a94ff);
            --revenue-blue-soft: var(--seller-primary-light, #ebf5ff);

            color: var(--revenue-text);
        }

        .revenue-page__intro {
            margin-bottom: 1.5rem;
        }

        .revenue-page__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-bottom: .55rem;
            color: var(--revenue-blue);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .revenue-muted {
            color: var(--revenue-muted);
        }

        .revenue-panel,
        .revenue-card {
            color: var(--revenue-text);
            background: var(--revenue-surface);
            border: 1px solid var(--revenue-border);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgb(15 23 42 / 5%);
        }

        .revenue-filter {
            padding: 1.15rem;
        }

        .revenue-filter .form-label {
            margin-bottom: .5rem;
            color: var(--revenue-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .revenue-filter .form-control {
            min-height: 44px;
            color: var(--revenue-text);
            background-color: var(--seller-input-bg, var(--revenue-surface));
            border-color: var(--revenue-border);
            border-radius: 10px;
        }

        .revenue-filter .form-control:focus {
            border-color: var(--revenue-blue);
            box-shadow: 0 0 0 .2rem rgb(26 148 255 / 15%);
        }

        [data-theme="dark"] .revenue-filter input[type="date"] {
            color-scheme: dark;
        }

        .revenue-filter__actions .btn {
            min-height: 44px;
            border-radius: 10px;
            white-space: nowrap;
        }

        .revenue-card {
            position: relative;
            height: 100%;
            min-height: 150px;
            padding: 1.15rem;
            overflow: hidden;
        }

        .revenue-card::before {
            position: absolute;
            inset: 0 auto 0 0;
            width: 3px;
            background: var(--revenue-blue);
            content: "";
            opacity: .75;
        }

        .revenue-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
        }

        .revenue-card__label {
            color: var(--revenue-muted);
            font-size: .9rem;
            line-height: 1.45;
        }

        .revenue-card__icon {
            display: inline-grid;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 12px;
            color: var(--revenue-blue);
            background: var(--revenue-blue-soft);
            font-size: 1.15rem;
        }

        .revenue-card__value {
            margin-top: .8rem;
            color: var(--revenue-text);
            font-size: clamp(1.35rem, 2vw, 1.8rem);
            font-weight: 750;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .revenue-card:first-child .revenue-card__value {
            color: var(--revenue-blue);
        }

        .revenue-card__note {
            display: block;
            margin-top: .4rem;
            color: var(--revenue-muted);
            font-size: .8rem;
        }

        .revenue-chart__header {
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid var(--revenue-border);
        }

        .revenue-chart__scroll {
            overflow-x: auto;
            padding: 1.25rem 1.25rem 1rem;
            scrollbar-color: var(--revenue-border) transparent;
            scrollbar-width: thin;
        }

        .revenue-chart {
            display: flex;
            align-items: flex-end;
            gap: .55rem;
            min-width: max-content;
            padding: .25rem .15rem 0;
        }

        .revenue-chart__column {
            display: flex;
            flex: 0 0 38px;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: .55rem;
        }

        .revenue-chart__plot {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            width: 100%;
            height: 190px;
            overflow: hidden;
            border-bottom: 1px solid var(--revenue-border);
            border-radius: 7px 7px 0 0;
            background:
                repeating-linear-gradient(
                    to top,
                    transparent 0,
                    transparent calc(25% - 1px),
                    var(--revenue-border) 25%
                );
        }

        .revenue-chart__bar {
            display: block;
            width: 72%;
            height: var(--bar-height);
            min-height: 3px;
            border-radius: 6px 6px 2px 2px;
            background: linear-gradient(180deg, #45b3ff 0%, #1683f5 100%);
            box-shadow: 0 3px 12px rgb(26 148 255 / 22%);
            transition: filter .15s ease, transform .15s ease;
        }

        .revenue-chart__column:hover .revenue-chart__bar {
            filter: brightness(1.12);
            transform: scaleX(1.08);
        }

        .revenue-chart__date {
            color: var(--revenue-muted);
            font-size: .72rem;
            white-space: nowrap;
        }

        .revenue-empty {
            padding: 3.5rem 1.25rem;
            text-align: center;
        }

        .revenue-empty__icon {
            display: inline-grid;
            width: 58px;
            height: 58px;
            margin-bottom: 1rem;
            place-items: center;
            border-radius: 18px;
            color: var(--revenue-blue);
            background: var(--revenue-blue-soft);
            font-size: 1.5rem;
        }

        @media (max-width: 575.98px) {
            .revenue-filter {
                padding: 1rem;
            }

            .revenue-filter__actions .btn {
                flex: 1 1 auto;
            }

            .revenue-card {
                min-height: 138px;
            }

            .revenue-chart__scroll {
                padding-right: .9rem;
                padding-left: .9rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .revenue-chart__bar {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $formatMoney = fn ($amount) => number_format((float) $amount, 0, ',', '.') . '₫';
        $chartMaxRevenue = max((float) $maxDailyRevenue, 1);
    @endphp

    <main class="revenue-page container-fluid py-4">
        <header class="revenue-page__intro">
            <div class="revenue-page__eyebrow">
                <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                Báo cáo cửa hàng
            </div>
            <h1 class="h3 fw-bold mb-1">Báo cáo doanh thu</h1>
            <p class="revenue-muted mb-0">
                Theo dõi doanh thu từ những đơn hàng đã hoàn thành.
            </p>
        </header>

        <section class="revenue-panel revenue-filter mb-4" aria-label="Lọc khoảng thời gian">
            <form method="GET" action="{{ route('seller.reports.revenue') }}"
                class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="date_from" class="form-label">Từ ngày</label>
                    <input
                        id="date_from"
                        name="date_from"
                        type="date"
                        class="form-control @error('date_from') is-invalid @enderror"
                        value="{{ request('date_from', $dateFrom->toDateString()) }}"
                    >
                    @error('date_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="date_to" class="form-label">Đến ngày</label>
                    <input
                        id="date_to"
                        name="date_to"
                        type="date"
                        class="form-control @error('date_to') is-invalid @enderror"
                        value="{{ request('date_to', $dateTo->toDateString()) }}"
                    >
                    @error('date_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <div class="revenue-filter__actions d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel me-1" aria-hidden="true"></i>
                            Lọc
                        </button>

                        <button
                            type="submit"
                            formaction="{{ route('seller.reports.revenue.export') }}"
                            class="btn btn-outline-success flex-grow-1"
                        >
                            <i class="bi bi-file-earmark-spreadsheet me-1" aria-hidden="true"></i>
                            Xuất Excel
                        </button>

                        <a href="{{ route('seller.reports.revenue') }}"
                            class="btn btn-outline-secondary">
                            Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </section>

        <section class="row g-3 mb-4" aria-label="Tổng quan doanh thu">
            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="revenue-card__top">
                        <span class="revenue-card__label">Doanh thu sản phẩm sau giảm giá</span>
                        <span class="revenue-card__icon">
                            <i class="bi bi-cash-stack" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="revenue-card__value">{{ $formatMoney($revenue) }}</div>
                    <small class="revenue-card__note">Không bao gồm phí vận chuyển</small>
                </article>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="revenue-card__top">
                        <span class="revenue-card__label">Đơn đã hoàn thành</span>
                        <span class="revenue-card__icon">
                            <i class="bi bi-receipt" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="revenue-card__value">
                        {{ number_format((int) $summary->completed_order_count) }}
                    </div>
                    <small class="revenue-card__note">Trong khoảng thời gian đã chọn</small>
                </article>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="revenue-card__top">
                        <span class="revenue-card__label">Sản phẩm đã bán</span>
                        <span class="revenue-card__icon">
                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="revenue-card__value">{{ number_format((int) $soldQuantity) }}</div>
                    <small class="revenue-card__note">Tính từ các đơn đã hoàn thành</small>
                </article>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <article class="revenue-card">
                    <div class="revenue-card__top">
                        <span class="revenue-card__label">Doanh thu trung bình / đơn</span>
                        <span class="revenue-card__icon">
                            <i class="bi bi-graph-up" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="revenue-card__value">{{ $formatMoney($averageOrderValue) }}</div>
                    <small class="revenue-card__note">Doanh thu sau giảm chia cho số đơn</small>
                </article>
            </div>
        </section>

        <section class="revenue-panel mb-4">
            <header class="revenue-chart__header">
                <h2 class="h5 fw-bold mb-1">Doanh thu theo ngày</h2>
                <p class="revenue-muted small mb-0">
                    Chỉ tính đơn đã hoàn thành. Kéo ngang biểu đồ để xem toàn bộ khoảng ngày.
                </p>
            </header>

            @if ($chartRows->isEmpty() || (int) $summary->completed_order_count === 0)
                <div class="revenue-empty">
                    <span class="revenue-empty__icon">
                        <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                    </span>
                    <h3 class="h6 fw-bold">Chưa có doanh thu trong khoảng thời gian này</h3>
                    <p class="revenue-muted mb-0">
                        Doanh thu sẽ xuất hiện khi đơn hàng được hoàn thành.
                    </p>
                </div>
            @else
                <div class="revenue-chart__scroll">
                    <div class="revenue-chart" role="img" aria-label="Biểu đồ doanh thu theo ngày">
                        @foreach ($chartRows as $row)
                            @php
                                $height = $row['revenue'] > 0
                                    ? max(5, ($row['revenue'] / $chartMaxRevenue) * 178)
                                    : 3;
                            @endphp

                            <div
                                class="revenue-chart__column"
                                title="{{ $row['date']->format('d/m/Y') }} · {{ $formatMoney($row['revenue']) }} · {{ $row['order_count'] }} đơn"
                            >
                                <div class="revenue-chart__plot">
                                    <span
                                        class="revenue-chart__bar"
                                        style="--bar-height: {{ $height }}px"
                                        aria-hidden="true"
                                    ></span>
                                </div>
                                <span class="revenue-chart__date">
                                    {{ $row['date']->format('d/m') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </main>
@endsection
