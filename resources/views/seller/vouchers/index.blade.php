@extends('seller.layouts.app')

@section('title', 'Voucher của Shop - Seller Center')

@section('content')
    <main class="container-fluid py-4 seller-voucher-page">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Voucher của Shop</h1>
                <p class="seller-voucher-muted mb-0">
                    Tạo và quản lý mã ưu đãi dành cho khách mua hàng tại Shop.
                </p>
            </div>

            <a href="{{ route('seller.vouchers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                Tạo voucher
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="status">
                {{ session('success') }}
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Đóng"
                ></button>
            </div>
        @endif

        @if ($errors->has('voucher'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('voucher') }}
            </div>
        @endif

        <section class="card seller-voucher-card shadow-sm">
            <div class="card-header seller-voucher-card__header d-flex align-items-center justify-content-between gap-3 py-3">
                <div>
                    <h2 class="h5 fw-semibold mb-1">Danh sách voucher</h2>
                    <p class="seller-voucher-muted small mb-0">
                        Mã voucher được quản lý riêng theo từng Shop.
                    </p>
                </div>

                <span class="badge seller-voucher-count">
                    {{ $vouchers->total() }} voucher
                </span>
            </div>

            @if ($vouchers->isEmpty())
                <div class="text-center py-5 px-3">
                    <i class="bi bi-ticket-perforated fs-1 seller-voucher-muted" aria-hidden="true"></i>
                    <h3 class="h5 mt-3">Shop chưa có voucher</h3>
                    <p class="seller-voucher-muted">
                        Tạo voucher đầu tiên để thiết lập chương trình ưu đãi.
                    </p>
                    <a href="{{ route('seller.vouchers.create') }}" class="btn btn-outline-primary">
                        Tạo voucher
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 seller-voucher-table">
                        <thead>
                            <tr>
                                <th class="ps-3">Mã voucher</th>
                                <th>Mức giảm</th>
                                <th>Đơn tối thiểu</th>
                                <th>Lượt dùng</th>
                                <th>Thời hạn</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-3">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($vouchers as $voucher)
                                @php
                                    if ($voucher->status === 'paused') {
                                        [$voucherState, $voucherBadge] = [
                                            'Đã tạm dừng',
                                            'text-bg-secondary',
                                        ];
                                    } elseif ($voucher->starts_at->isFuture()) {
                                        [$voucherState, $voucherBadge] = [
                                            'Chưa bắt đầu',
                                            'text-bg-info',
                                        ];
                                    } elseif ($voucher->expires_at->isPast()) {
                                        [$voucherState, $voucherBadge] = [
                                            'Đã hết hạn',
                                            'text-bg-dark',
                                        ];
                                    } elseif (
                                        $voucher->usage_limit !== null
                                        && $voucher->used_count >= $voucher->usage_limit
                                    ) {
                                        [$voucherState, $voucherBadge] = [
                                            'Đã hết lượt',
                                            'text-bg-warning',
                                        ];
                                    } else {
                                        [$voucherState, $voucherBadge] = [
                                            'Đang hoạt động',
                                            'text-bg-success',
                                        ];
                                    }
                                @endphp

                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-bold text-primary">{{ $voucher->code }}</span>
                                        <div class="seller-voucher-muted small">
                                            {{ $voucher->discount_type === 'percent'
                                                ? 'Voucher phần trăm'
                                                : 'Voucher số tiền' }}
                                        </div>
                                    </td>

                                    <td class="fw-semibold">
                                        @if ($voucher->discount_type === 'percent')
                                            {{ rtrim(rtrim(number_format((float) $voucher->discount_value, 2, ',', '.'), '0'), ',') }}%
                                            <div class="seller-voucher-muted small">
                                                Tối đa
                                                {{ number_format((float) $voucher->max_discount, 0, ',', '.') }}₫
                                            </div>
                                        @else
                                            {{ number_format((float) $voucher->discount_value, 0, ',', '.') }}₫
                                        @endif
                                    </td>

                                    <td>
                                        {{ number_format((float) $voucher->min_order_amount, 0, ',', '.') }}₫
                                    </td>

                                    <td>
                                        {{ number_format($voucher->used_count) }} /
                                        {{ $voucher->usage_limit === null
                                            ? 'Không giới hạn'
                                            : number_format($voucher->usage_limit) }}
                                    </td>

                                    <td class="small">
                                        <div>{{ $voucher->starts_at->format('d/m/Y H:i') }}</div>
                                        <div class="seller-voucher-muted">
                                            đến {{ $voucher->expires_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge {{ $voucherBadge }}">
                                            {{ $voucherState }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                            <a
                                                href="{{ route('seller.vouchers.edit', $voucher->voucher_id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                aria-label="Sửa voucher {{ $voucher->code }}"
                                                title="Sửa voucher"
                                            >
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('seller.vouchers.toggle-status', $voucher->voucher_id) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="btn btn-sm btn-outline-secondary"
                                                    type="submit"
                                                >
                                                    {{ $voucher->status === 'active' ? 'Tạm dừng' : 'Bật lại' }}
                                                </button>
                                            </form>

                                            @if ($voucher->used_count === 0)
                                                <form
                                                    method="POST"
                                                    action="{{ route('seller.vouchers.destroy', $voucher->voucher_id) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa voucher này?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        class="btn btn-sm btn-outline-danger"
                                                        type="submit"
                                                        aria-label="Xóa voucher {{ $voucher->code }}"
                                                        title="Xóa voucher"
                                                    >
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($vouchers->hasPages())
                    <div class="card-footer seller-voucher-card__footer">
                        {{ $vouchers->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </section>
    </main>

    <style>
        .seller-voucher-page {
            color: var(--seller-text, #0F172A);
        }

        .seller-voucher-card {
            overflow: hidden;
            color: var(--seller-text, #0F172A);
            background: var(--seller-card-bg, #fff);
            border: 1px solid var(--seller-border, #E2E8F0);
            border-radius: 1rem;
        }

        .seller-voucher-card__header,
        .seller-voucher-card__footer {
            color: var(--seller-text, #0F172A);
            background: var(--seller-card-bg, #fff);
            border-color: var(--seller-border, #E2E8F0);
        }

        .seller-voucher-muted {
            color: var(--seller-text-muted, #64748B);
        }

        .seller-voucher-count {
            color: var(--seller-text, #0F172A);
            background: var(--seller-hover-bg, #F8FAFC);
            border: 1px solid var(--seller-border, #E2E8F0);
        }

        .seller-voucher-table {
            --bs-table-color: var(--seller-text, #0F172A);
            --bs-table-bg: var(--seller-card-bg, #fff);
            --bs-table-hover-color: var(--seller-text, #0F172A);
            --bs-table-hover-bg: var(--seller-hover-bg, #F8FAFC);
            --bs-table-border-color: var(--seller-border, #E2E8F0);
            color: var(--seller-text, #0F172A);
            background: var(--seller-card-bg, #fff);
        }

        .seller-voucher-table th,
        .seller-voucher-table td {
            color: var(--seller-text, #0F172A);
            background-color: transparent;
            border-color: var(--seller-border, #E2E8F0);
        }

        .seller-voucher-table thead th {
            color: var(--seller-text, #0F172A);
            background: var(--seller-hover-bg, #F8FAFC);
            font-size: .82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        [data-theme="dark"] .seller-voucher-page {
            --seller-card-bg: #1E293B;
            --seller-border: #334155;
            --seller-text: #F1F5F9;
            --seller-text-muted: #A8B5C7;
            --seller-hover-bg: #263449;
        }

        [data-theme="dark"] .seller-voucher-card__header,
        [data-theme="dark"] .seller-voucher-card__footer {
            background-color: var(--seller-card-bg) !important;
        }

        [data-theme="dark"] .seller-voucher-table,
        [data-theme="dark"] .seller-voucher-table > :not(caption) > * > * {
            color: var(--seller-text);
            background-color: var(--seller-card-bg);
            border-color: var(--seller-border);
        }

        [data-theme="dark"] .seller-voucher-table thead th {
            color: #E2E8F0;
            background-color: #263449;
        }

        [data-theme="dark"] .seller-voucher-table tbody tr:hover > * {
            color: var(--seller-text);
            background-color: var(--seller-hover-bg);
        }

        [data-theme="dark"] .seller-voucher-count {
            color: var(--seller-text);
            background-color: #263449;
            border-color: var(--seller-border);
        }

        [data-theme="dark"] .seller-voucher-page .text-bg-dark {
            color: #F8FAFC !important;
            background-color: #475569 !important;
        }

        @media (max-width: 767.98px) {
            .seller-voucher-page > .d-flex {
                align-items: stretch !important;
            }

            .seller-voucher-page > .d-flex .btn {
                width: 100%;
            }

            .seller-voucher-card__header {
                align-items: flex-start !important;
            }

            .seller-voucher-table {
                min-width: 850px;
            }
        }
    </style>
@endsection
