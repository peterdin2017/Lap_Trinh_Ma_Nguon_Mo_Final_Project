@extends('seller.layouts.app')

@section('title', 'Phân tích bán hàng - Seller Center')

@push('styles')
    <style>
        .sales-analytics {
            --analytics-surface: var(--seller-card-bg, #fff);
            --analytics-input: var(--seller-input-bg, var(--analytics-surface));
            --analytics-border: var(--seller-border, #e2e8f0);
            --analytics-text: var(--seller-text, #0f172a);
            --analytics-muted: var(--seller-text-muted, #64748b);
            --analytics-primary: var(--seller-primary, #1a94ff);
            --analytics-primary-soft: var(--seller-primary-light, #ebf5ff);
            color: var(--analytics-text);
        }

        .sales-analytics__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-bottom: .55rem;
            color: var(--analytics-primary);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .sales-analytics__muted {
            color: var(--analytics-muted);
        }

        .analytics-panel,
        .analytics-stat {
            color: var(--analytics-text);
            background: var(--analytics-surface);
            border: 1px solid var(--analytics-border);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgb(15 23 42 / 5%);
        }

        .analytics-filter {
            padding: 1.15rem;
        }

        .analytics-filter .form-label {
            color: var(--analytics-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .analytics-filter .form-control {
            min-height: 44px;
            color: var(--analytics-text);
            background-color: var(--analytics-input);
            border-color: var(--analytics-border);
            border-radius: 10px;
        }

        .analytics-filter .form-control:focus {
            border-color: var(--analytics-primary);
            box-shadow: 0 0 0 .2rem rgb(26 148 255 / 15%);
        }

        [data-theme="dark"] .analytics-filter input[type="date"] {
            color-scheme: dark;
        }

        .analytics-filter .btn {
            min-height: 44px;
            border-radius: 10px;
            white-space: nowrap;
        }

        .analytics-stat {
            position: relative;
            height: 100%;
            min-height: 150px;
            padding: 1.15rem;
            overflow: hidden;
        }

        .analytics-stat::before {
            position: absolute;
            inset: 0 auto 0 0;
            width: 3px;
            background: var(--analytics-primary);
            content: "";
            opacity: .8;
        }

        .analytics-stat__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .8rem;
        }

        .analytics-stat__label {
            color: var(--analytics-muted);
            font-size: .9rem;
            line-height: 1.45;
        }

        .analytics-stat__icon {
            display: inline-grid;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 12px;
            color: var(--analytics-primary);
            background: var(--analytics-primary-soft);
            font-size: 1.15rem;
        }

        .analytics-stat__value {
            margin-top: .75rem;
            color: var(--analytics-text);
            font-size: clamp(1.35rem, 2vw, 1.8rem);
            font-weight: 750;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .analytics-stat__value--name {
            font-size: clamp(1rem, 1.5vw, 1.25rem);
        }

        .analytics-stat__note {
            display: block;
            margin-top: .4rem;
            color: var(--analytics-muted);
            font-size: .8rem;
        }

        .analytics-panel__header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid var(--analytics-border);
        }

        .analytics-table {
            --bs-table-color: var(--analytics-text);
            --bs-table-bg: var(--analytics-surface);
            --bs-table-border-color: var(--analytics-border);
            --bs-table-striped-color: var(--analytics-text);
            --bs-table-striped-bg: color-mix(in srgb, var(--analytics-surface) 92%, var(--analytics-primary));
            --bs-table-hover-color: var(--analytics-text);
            --bs-table-hover-bg: color-mix(in srgb, var(--analytics-surface) 88%, var(--analytics-primary));
            margin: 0;
        }

        .analytics-table thead th {
            padding-top: .85rem;
            padding-bottom: .85rem;
            color: var(--analytics-muted);
            background: color-mix(in srgb, var(--analytics-surface) 92%, var(--analytics-primary));
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .analytics-table tbody td {
            padding-top: .95rem;
            padding-bottom: .95rem;
            background-color: var(--analytics-surface);
            vertical-align: middle;
        }

        .analytics-product__name {
            min-width: 190px;
            font-weight: 650;
        }

        .analytics-product__share {
            width: min(220px, 100%);
            height: 5px;
            margin-top: .5rem;
            overflow: hidden;
            border-radius: 999px;
            background: color-mix(in srgb, var(--analytics-border) 72%, transparent);
        }

        .analytics-product__share-fill {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #55b7ff, #1677e8);
        }

        .analytics-change {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            white-space: nowrap;
            font-size: .85rem;
            font-weight: 650;
        }

        .analytics-change--up {
            color: #16a36a;
        }

        .analytics-change--down {
            color: #ef4444;
        }

        .analytics-change--neutral {
            color: var(--analytics-muted);
            font-weight: 500;
        }

        .analytics-empty {
            padding: 3.5rem 1.25rem;
            text-align: center;
        }

        .analytics-empty__icon {
            display: inline-grid;
            width: 58px;
            height: 58px;
            margin-bottom: 1rem;
            place-items: center;
            border-radius: 18px;
            color: var(--analytics-primary);
            background: var(--analytics-primary-soft);
            font-size: 1.5rem;
        }

        @media (max-width: 575.98px) {
            .analytics-filter {
                padding: 1rem;
            }

            .analytics-filter__actions .btn {
                flex: 1 1 auto;
            }

            .analytics-stat {
                min-height: 135px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $formatMoney = fn ($amount) => number_format((float) $amount, 0, ',', '.') . '₫';
        $hasPreviousData = (int) $previousUnitsSold > 0;
    @endphp

    <main class="sales-analytics container-fluid py-4">
        <header class="mb-4">
            <div class="sales-analytics__eyebrow">
                <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                Phân tích cửa hàng
            </div>
            <h1 class="h3 fw-bold mb-1">Phân tích bán hàng</h1>
            <p class="sales-analytics__muted mb-0">
                Xem sản phẩm bán chạy và mức thay đổi so với kỳ liền trước.
            </p>
        </header>

        <section class="analytics-panel analytics-filter mb-4" aria-label="Lọc thời gian phân tích">
            <form method="GET" action="{{ route('seller.reports.analytics') }}"
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
                    <div class="analytics-filter__actions d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i>
                            Xem phân tích
                        </button>
                        <a href="{{ route('seller.reports.analytics') }}"
                            class="btn btn-outline-secondary">
                            Đặt lại
                        </a>
                    </div>
                </div>
            </form>

            <div class="sales-analytics__muted small mt-3">
                Kỳ đang xem: {{ $dateFrom->format('d/m/Y') }} – {{ $dateTo->format('d/m/Y') }}
                <span class="mx-1">·</span>
                Kỳ so sánh: {{ $previousFrom->format('d/m/Y') }} – {{ $previousTo->format('d/m/Y') }}
            </div>

            @error('date_to')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </section>

        <section class="row g-3 mb-4" aria-label="Tổng quan bán hàng">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="analytics-stat">
                    <div class="analytics-stat__top">
                        <span class="analytics-stat__label">Sản phẩm đã bán</span>
                        <span class="analytics-stat__icon">
                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="analytics-stat__value">{{ number_format((int) $unitsSold) }}</div>
                    <small class="analytics-stat__note">
                        @if ($hasPreviousData)
                            Kỳ trước: {{ number_format((int) $previousUnitsSold) }} sản phẩm
                        @else
                            Chưa có dữ liệu bán ở kỳ trước
                        @endif
                    </small>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="analytics-stat">
                    <div class="analytics-stat__top">
                        <span class="analytics-stat__label">Mặt hàng có phát sinh bán</span>
                        <span class="analytics-stat__icon">
                            <i class="bi bi-grid" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="analytics-stat__value">{{ number_format((int) $productCount) }}</div>
                    <small class="analytics-stat__note">Trong kỳ đang chọn</small>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="analytics-stat">
                    <div class="analytics-stat__top">
                        <span class="analytics-stat__label">So với kỳ trước</span>
                        <span class="analytics-stat__icon">
                            <i class="bi bi-arrow-left-right" aria-hidden="true"></i>
                        </span>
                    </div>

                    @if (is_null($unitsChangePercent))
                        <div class="analytics-stat__value analytics-stat__value--name">
                            Chưa có dữ liệu so sánh
                        </div>
                        <small class="analytics-stat__note">Cần có sản phẩm bán ở kỳ trước</small>
                    @else
                        <div class="analytics-stat__value {{ $unitsChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $unitsChangePercent > 0 ? '+' : '' }}{{ number_format($unitsChangePercent, 1) }}%
                        </div>
                        <small class="analytics-stat__note">
                            {{ $unitsChangePercent >= 0 ? 'Số lượng bán tăng' : 'Số lượng bán giảm' }}
                        </small>
                    @endif
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="analytics-stat">
                    <div class="analytics-stat__top">
                        <span class="analytics-stat__label">Sản phẩm dẫn đầu</span>
                        <span class="analytics-stat__icon">
                            <i class="bi bi-trophy" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="analytics-stat__value analytics-stat__value--name">
                        {{ $topProduct?->product_name ?? 'Chưa có dữ liệu' }}
                    </div>
                    @if ($topProduct)
                        <small class="analytics-stat__note">
                            {{ number_format((int) $topProduct->units_sold) }} sản phẩm đã bán
                        </small>
                    @else
                        <small class="analytics-stat__note">Trong kỳ đang chọn</small>
                    @endif
                </article>
            </div>
        </section>

        <section class="analytics-panel">
            <header class="analytics-panel__header">
                <div>
                    <h2 class="h5 fw-bold mb-1">Hiệu quả theo sản phẩm</h2>
                    <p class="sales-analytics__muted small mb-0">
                        Xếp theo số lượng bán trong các đơn đã hoàn thành.
                    </p>
                </div>

                <span class="badge rounded-pill text-bg-primary px-3 py-2">Top 10</span>
            </header>

            @if ($products->isEmpty())
                <div class="analytics-empty">
                    <span class="analytics-empty__icon">
                        <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                    </span>
                    <h3 class="h6 fw-bold">Chưa có dữ liệu sản phẩm</h3>
                    <p class="sales-analytics__muted mb-0">
                        Sản phẩm sẽ xuất hiện tại đây sau khi có đơn hàng hoàn thành trong kỳ đã chọn.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle analytics-table">
                        <thead>
                            <tr>
                                <th class="ps-4" scope="col">#</th>
                                <th scope="col">Sản phẩm</th>
                                <th class="text-end" scope="col">Đã bán</th>
                                <th class="text-end" scope="col">Kỳ trước</th>
                                <th class="text-end" scope="col">Thay đổi</th>
                                <th class="text-end" scope="col">Doanh số sản phẩm</th>
                                <th class="text-end pe-4" scope="col">Số đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                @php
                                    $share = $unitsSold > 0
                                        ? min(100, ((int) $product->units_sold / (int) $unitsSold) * 100)
                                        : 0;
                                @endphp
                                <tr>
                                    <td class="ps-4 text-secondary">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="analytics-product__name">
                                            {{ $product->product_name }}
                                        </div>
                                        <div class="analytics-product__share"
                                            role="img"
                                            aria-label="{{ number_format($share, 1) }}% tổng sản phẩm đã bán">
                                            <span class="analytics-product__share-fill"
                                                style="width: {{ $share }}%"></span>
                                        </div>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format((int) $product->units_sold) }}
                                    </td>
                                    <td class="text-end">
                                        @if ($hasPreviousData)
                                            {{ number_format((int) $product->previous_units_sold) }}
                                        @else
                                            <span class="analytics-change analytics-change--neutral">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if (is_null($product->change_percent))
                                            <span class="analytics-change analytics-change--neutral">
                                                {{ $hasPreviousData ? 'Mới bán' : 'Chưa có kỳ trước' }}
                                            </span>
                                        @else
                                            <span class="analytics-change {{ $product->change_percent >= 0 ? 'analytics-change--up' : 'analytics-change--down' }}">
                                                <i class="bi {{ $product->change_percent >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"
                                                    aria-hidden="true"></i>
                                                {{ $product->change_percent > 0 ? '+' : '' }}{{ number_format($product->change_percent, 1) }}%
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ $formatMoney($product->product_sales) }}
                                    </td>
                                    <td class="text-end pe-4">
                                        {{ number_format((int) $product->order_count) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
@endsection
