@extends('admin.layouts.app')

@section('title', 'Quản lý voucher sàn')

@push('styles')
    <style>
        .platform-vouchers {
            color: var(--app-text, #0f172a);
        }

        .platform-vouchers__card {
            overflow: hidden;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 12px;
            background: var(--app-card-bg, #fff);
            box-shadow: 0 2px 10px rgba(15, 23, 42, .05);
        }

        .platform-vouchers__card-header,
        .platform-vouchers__card-footer {
            padding: 1rem 1.25rem;
            border-color: var(--app-border, #e2e8f0);
            background: var(--app-card-bg, #fff);
        }

        .platform-vouchers__table {
            margin-bottom: 0;
            color: var(--app-text, #0f172a);
            vertical-align: middle;
        }

        .platform-vouchers__table thead th {
            padding: .9rem .75rem;
            border-bottom: 1px solid var(--app-border, #e2e8f0);
            background: #f8fafc;
            color: #475569;
            font-size: .84rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .platform-vouchers__table tbody td {
            padding: .9rem .75rem;
            border-color: var(--app-border, #e2e8f0);
            background: var(--app-card-bg, #fff);
        }

        .platform-vouchers__muted {
            color: var(--app-text-muted, #64748b);
        }

        .platform-vouchers__count {
            padding: .45rem .7rem;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 999px;
            background: var(--app-hover-bg, #f8fafc);
            color: var(--app-text, #0f172a);
            font-weight: 600;
        }

        .platform-vouchers__empty {
            padding: 3.5rem 1rem !important;
            text-align: center;
        }

        .platform-vouchers__empty-icon {
            color: var(--app-text-muted, #94a3b8);
            font-size: 2.5rem;
        }

        [data-bs-theme="dark"] .platform-vouchers__card,
        [data-bs-theme="dark"] .platform-vouchers__card-header,
        [data-bs-theme="dark"] .platform-vouchers__card-footer,
        [data-bs-theme="dark"] .platform-vouchers__table tbody td {
            border-color: #334155;
            background: #1e293b;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .platform-vouchers__table thead th {
            border-color: #334155;
            background: #172338;
            color: #cbd5e1;
        }

        [data-bs-theme="dark"] .platform-vouchers__muted,
        [data-bs-theme="dark"] .platform-vouchers__empty-icon {
            color: #94a3b8;
        }

        [data-bs-theme="dark"] .platform-vouchers__count {
            border-color: #334155;
            background: #263449;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .platform-vouchers__table tbody tr:hover td {
            background: #26364d;
        }

        [data-bs-theme="dark"] .platform-vouchers .form-control,
        [data-bs-theme="dark"] .platform-vouchers .form-select {
            border-color: #334155;
            background-color: #1e293b;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .platform-vouchers .form-control::placeholder {
            color: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid platform-vouchers">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Voucher của sàn</h3>
                <p class="platform-vouchers__muted mb-0">
                    Tạo và quản lý mã giảm giá do sàn phát hành.
                </p>
            </div>

            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                Tạo voucher
            </a>
        </div>



        @if ($errors->has('voucher'))
            <div class="alert alert-danger" role="alert">{{ $errors->first('voucher') }}</div>
        @endif

        <div class="platform-vouchers__card mb-4">
            <div class="p-3">
                <form method="GET" action="{{ route('admin.vouchers.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-7 col-xl-8">
                            <label for="keyword" class="form-label">Tìm voucher</label>
                            <input
                                type="search"
                                id="keyword"
                                name="keyword"
                                class="form-control"
                                value="{{ request('keyword') }}"
                                placeholder="Nhập mã hoặc tên voucher"
                            >
                        </div>

                        <div class="col-12 col-md-3 col-xl-2">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" @selected(request('status') === 'active')>
                                    Đang bật
                                </option>
                                <option value="inactive" @selected(request('status') === 'inactive')>
                                    Đang tắt
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
                                    href="{{ route('admin.vouchers.index') }}"
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
        </div>

        <div class="platform-vouchers__card">
            <div class="platform-vouchers__card-header d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Danh sách voucher</h5>
                    <p class="platform-vouchers__muted small mb-0">
                        Mã voucher được quản lý riêng bởi sàn.
                    </p>
                </div>
                <span class="platform-vouchers__count">{{ $vouchers->total() }} voucher</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover platform-vouchers__table">
                    <thead>
                        <tr>
                            <th class="ps-3">Voucher</th>
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
                                $isExpired = $voucher->expires_at?->isPast() ?? false;
                                $isNotStarted = $voucher->starts_at?->isFuture() ?? false;
                                $isLimitReached = $voucher->usage_limit !== null
                                    && $voucher->used_count >= $voucher->usage_limit;
                            @endphp

                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-primary">
                                        {{ $voucher->voucher_code }}
                                    </div>
                                    <div>{{ $voucher->voucher_name }}</div>

                                    @if ($voucher->description)
                                        <div
                                            class="platform-vouchers__muted small text-truncate"
                                            style="max-width: 260px;"
                                            title="{{ $voucher->description }}"
                                        >
                                            {{ $voucher->description }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if ($voucher->discount_type === 'percentage')
                                        <div class="fw-semibold">
                                            {{ rtrim(rtrim(number_format((float) $voucher->discount_value, 2, ',', '.'), '0'), ',') }}%
                                        </div>

                                        @if ($voucher->max_discount_amount !== null)
                                            <div class="platform-vouchers__muted small">
                                                Tối đa
                                                {{ number_format((float) $voucher->max_discount_amount, 0, ',', '.') }}đ
                                            </div>
                                        @endif
                                    @else
                                        <div class="fw-semibold">
                                            {{ number_format((float) $voucher->discount_value, 0, ',', '.') }}đ
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="platform-vouchers__muted">Đơn từ</span>
                                    <div class="fw-semibold">
                                        {{ number_format((float) $voucher->min_order_amount, 0, ',', '.') }}đ
                                    </div>
                                    <div class="platform-vouchers__muted small">
                                        Tối đa {{ $voucher->per_user_limit }} lượt/khách
                                    </div>
                                </td>

                                <td>
                                    {{ number_format((int) $voucher->used_count) }}
                                    /
                                    {{ $voucher->usage_limit !== null
                                        ? number_format((int) $voucher->usage_limit)
                                        : 'Không giới hạn' }}
                                </td>

                                <td class="small">
                                    <div>
                                        {{ $voucher->starts_at?->format('d/m/Y H:i') ?? '—' }}
                                    </div>
                                    <div class="platform-vouchers__muted">
                                        đến {{ $voucher->expires_at?->format('d/m/Y H:i') ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    @if ($voucher->status === 'inactive')
                                        <span class="badge text-bg-secondary">Đang tắt</span>
                                    @elseif ($isExpired)
                                        <span class="badge text-bg-danger">Đã hết hạn</span>
                                    @elseif ($isNotStarted)
                                        <span class="badge text-bg-info">Chưa bắt đầu</span>
                                    @elseif ($isLimitReached)
                                        <span class="badge text-bg-warning">Đã hết lượt</span>
                                    @else
                                        <span class="badge text-bg-success">Đang hoạt động</span>
                                    @endif
                                </td>

                                <td class="text-end pe-3">
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                        <a
                                            href="{{ route('admin.vouchers.edit', $voucher->platform_voucher_id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Sửa voucher"
                                            aria-label="Sửa voucher {{ $voucher->voucher_code }}"
                                        >
                                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.vouchers.toggle-status', $voucher->platform_voucher_id) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $voucher->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            >
                                                {{ $voucher->status === 'active' ? 'Tắt' : 'Bật' }}
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="platform-vouchers__empty">
                                    <i class="bi bi-ticket-perforated platform-vouchers__empty-icon" aria-hidden="true"></i>
                                    <h5 class="mt-3 mb-1">Chưa có voucher sàn</h5>
                                    <p class="platform-vouchers__muted mb-3">
                                        Tạo voucher để bắt đầu chương trình ưu đãi của sàn.
                                    </p>
                                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                                        Tạo voucher
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="platform-vouchers__card-footer">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
@endsection
