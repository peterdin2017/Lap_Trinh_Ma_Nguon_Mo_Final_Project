@extends('seller.layouts.app')

@section('title', 'Voucher của Shop - Seller Center')

@section('content')
    <main class="container-fluid seller-voucher-page">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Voucher của Shop</h3>
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

        <section class="card seller-voucher-card mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('seller.vouchers.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-7 col-xl-8">
                            <label for="keyword" class="form-label">Tìm voucher</label>
                            <input
                                type="search"
                                id="keyword"
                                name="keyword"
                                class="form-control"
                                value="{{ request('keyword') }}"
                                placeholder="Nhập mã voucher"
                            >
                        </div>

                        <div class="col-12 col-md-3 col-xl-2">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" @selected(request('status') === 'active')>
                                    Đang bật
                                </option>
                                <option value="paused" @selected(request('status') === 'paused')>
                                    Đang tạm dừng
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-1" aria-hidden="true"></i>
                                    Lọc
                                </button>
                                <a
                                    href="{{ route('seller.vouchers.index') }}"
                                    class="btn btn-outline-secondary"
                                    title="Xóa bộ lọc"
                                    aria-label="Xóa bộ lọc"
                                >
                                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="card seller-voucher-card">
            <div class="card-header seller-voucher-card__header d-flex align-items-center justify-content-between gap-3 py-3">
                <div>
                    <h5 class="fw-bold mb-1">Danh sách voucher</h5>
                    <p class="seller-voucher-muted small mb-0">
                        Mã voucher được quản lý riêng theo từng Shop.
                    </p>
                </div>

                <span class="seller-voucher-count">
                    {{ $vouchers->total() }} voucher
                </span>
            </div>

            <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 seller-voucher-table">
                        <thead>
                            <tr>
                                <th class="ps-3">Mã voucher</th>
                                <th>Mức giảm</th>
                                <th>Điều kiện</th>
                                <th>Lượt dùng</th>
                                <th>Thời hạn</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-3">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($vouchers as $voucher)
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
                                            'text-bg-danger',
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
                                        <span class="seller-voucher-muted small">Đơn từ</span>
                                        <div class="fw-semibold">
                                            {{ number_format((float) $voucher->min_order_amount, 0, ',', '.') }}₫
                                        </div>
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
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-2">
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
                                                    class="btn btn-sm {{ $voucher->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    type="submit"
                                                >
                                                    {{ $voucher->status === 'active' ? 'Tắt' : 'Bật' }}
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="seller-voucher-empty">
                                        <i class="bi bi-ticket-perforated seller-voucher-empty__icon" aria-hidden="true"></i>
                                        <h3 class="h5 mt-3 mb-1">
                                            {{ request()->hasAny(['keyword', 'status']) ? 'Không tìm thấy voucher' : 'Shop chưa có voucher' }}
                                        </h3>
                                        <p class="seller-voucher-muted mb-3">
                                            {{ request()->hasAny(['keyword', 'status'])
                                                ? 'Thử thay đổi từ khóa hoặc trạng thái lọc.'
                                                : 'Tạo voucher để bắt đầu chương trình ưu đãi của Shop.' }}
                                        </p>
                                        @unless (request()->hasAny(['keyword', 'status']))
                                            <a href="{{ route('seller.vouchers.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                                                Tạo voucher
                                            </a>
                                        @endunless
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
            </div>

            @if ($vouchers->hasPages())
                    <div class="card-footer seller-voucher-card__footer">
                        {{ $vouchers->links() }}
                    </div>
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
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .05);
        }

        .seller-voucher-card__header,
        .seller-voucher-card__footer {
            padding: 1rem 1.25rem;
            color: var(--seller-text, #0F172A);
            background: var(--seller-card-bg, #fff);
            border-color: var(--seller-border, #E2E8F0);
        }

        .seller-voucher-muted {
            color: var(--seller-text-muted, #64748B);
        }

        .seller-voucher-empty {
            padding: 3.5rem 1rem !important;
            text-align: center;
        }

        .seller-voucher-empty__icon {
            color: var(--seller-text-muted, #94a3b8);
            font-size: 2.5rem;
        }

        .seller-voucher-count {
            display: inline-flex;
            align-items: center;
            padding: .45rem .7rem;
            border-radius: 999px;
            color: var(--seller-text, #0F172A);
            background: var(--seller-hover-bg, #F8FAFC);
            border: 1px solid var(--seller-border, #E2E8F0);
            font-weight: 600;
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
            padding: .9rem .75rem;
            color: var(--seller-text, #0F172A);
            background: var(--seller-hover-bg, #F8FAFC);
            font-size: .82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .seller-voucher-table tbody td {
            padding: .9rem .75rem;
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

        [data-theme="dark"] .seller-voucher-page .form-control,
        [data-theme="dark"] .seller-voucher-page .form-select {
            border-color: #334155;
            background-color: #1e293b;
            color: #e2e8f0;
        }

        [data-theme="dark"] .seller-voucher-page .form-control::placeholder {
            color: #94a3b8;
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
                min-width: 900px;
            }
        }
    </style>
@endsection
