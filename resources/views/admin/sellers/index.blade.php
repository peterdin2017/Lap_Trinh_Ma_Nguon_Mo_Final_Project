@extends('admin.layouts.app')

@section('title', 'Duyệt cửa hàng - Admin Center')
@section('page-title', 'Duyệt cửa hàng')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Hồ sơ chờ duyệt</h2>
            <p class="text-secondary mb-0">
                Xem thông tin shop và quyết định duyệt hồ sơ người bán.
            </p>
        </div>

        <span class="admin-pending-count">
            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
            {{ $sellers->total() }} hồ sơ đang chờ
        </span>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <section class="admin-table-card">
        <div class="table-responsive">
            <table class="table admin-sellers-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 ps-lg-4">Cửa hàng</th>
                        <th>Email người bán</th>
                        <th>Địa chỉ</th>
                        <th>Ngày gửi</th>
                        <th class="text-end pe-3 pe-lg-4">Xử lý</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sellers as $seller)
                        <tr>
                            <td class="ps-3 ps-lg-4">
                                <div class="fw-semibold">{{ $seller->shop_name }}</div>
                                <div class="small text-secondary">
                                    Mã shop: {{ $seller->seller_id }}
                                </div>
                            </td>

                            <td class="text-break">
                                {{ $seller->account?->email ?? 'Không có email' }}
                            </td>

                            <td class="admin-sellers-table__address">
                                {{ collect([
                                    $seller->shop_address,
                                    $seller->shop_ward,
                                    $seller->shop_district,
                                    $seller->shop_province,
                                ])->filter()->implode(', ') ?: 'Chưa cung cấp' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $seller->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="pe-3 pe-lg-4">
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <form method="POST"
                                          action="{{ route('admin.sellers.approve', $seller) }}"
                                          onsubmit="return confirm('Duyệt cửa hàng {{ addslashes($seller->shop_name) }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success text-nowrap">
                                            <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                                            Duyệt shop
                                        </button>
                                    </form>

                                    <details class="admin-reject">
                                        <summary>
                                            <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                                            Từ chối hồ sơ
                                        </summary>

                                        <form method="POST"
                                              action="{{ route('admin.sellers.reject', $seller) }}"
                                              class="admin-reject__form">
                                            @csrf
                                            <label for="reject_reason_{{ $seller->seller_id }}"
                                                   class="form-label small fw-semibold">
                                                Lý do từ chối
                                            </label>
                                            <input id="reject_reason_{{ $seller->seller_id }}"
                                                   name="reject_reason"
                                                   type="text"
                                                   maxlength="255"
                                                   class="form-control form-control-sm mb-2"
                                                   placeholder="Nhập lý do"
                                                   required>
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                Xác nhận từ chối
                                            </button>
                                        </form>
                                    </details>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="admin-empty-state">
                                    <span class="admin-empty-state__icon">
                                        <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                    </span>
                                    <p class="fw-semibold mt-3 mb-1">
                                        Đã xử lý hết hồ sơ chờ duyệt
                                    </p>
                                    <p class="text-secondary mb-0">
                                        Khi có đăng ký mới, hồ sơ sẽ xuất hiện tại đây.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sellers->hasPages())
            <div class="admin-table-card__footer">
                {{ $sellers->links() }}
            </div>
        @endif
    </section>

    <style>
        .admin-pending-count {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            align-self: flex-start;
            padding: .55rem .9rem;
            border: 1px solid var(--app-border);
            border-radius: 999px;
            background: var(--app-primary-light);
            color: var(--app-primary);
            font-size: .875rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .admin-table-card {
            overflow: hidden;
            border: 1px solid var(--app-border);
            border-radius: 12px;
            background: var(--app-card-bg);
        }

        .admin-sellers-table {
            --bs-table-color: var(--app-text);
            --bs-table-bg: var(--app-card-bg);
            --bs-table-border-color: var(--app-border);
            --bs-table-hover-bg: var(--app-primary-light);
            --bs-table-hover-color: var(--app-text);
        }

        .admin-sellers-table thead th {
            padding-top: .9rem;
            padding-bottom: .9rem;
            border-bottom-width: 1px;
            background: var(--app-page-bg);
            color: var(--app-text-muted);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .admin-sellers-table tbody td {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .admin-sellers-table__address {
            min-width: 220px;
            max-width: 320px;
        }

        .admin-reject {
            position: relative;
            color: #DC3545;
            font-size: .875rem;
        }

        .admin-reject summary {
            cursor: pointer;
            list-style: none;
        }

        .admin-reject summary::-webkit-details-marker {
            display: none;
        }

        .admin-reject__form {
            position: absolute;
            z-index: 5;
            top: calc(100% + .5rem);
            right: 0;
            width: min(280px, calc(100vw - 2rem));
            padding: 1rem;
            border: 1px solid var(--app-border);
            border-radius: 10px;
            background: var(--app-card-bg);
            color: var(--app-text);
            box-shadow: 0 8px 24px rgb(15 23 42 / 12%);
        }

        .admin-empty-state {
            color: var(--app-text);
        }

        .admin-empty-state__icon {
            display: inline-flex;
            width: 56px;
            height: 56px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--app-primary-light);
            color: var(--app-primary);
            font-size: 1.75rem;
        }

        .admin-table-card__footer {
            padding: .75rem 1rem;
            border-top: 1px solid var(--app-border);
            background: var(--app-card-bg);
        }

        .admin-table-card__footer nav p {
            margin-bottom: 0;
        }

        .admin-table-card__footer .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .admin-sellers-table {
                min-width: 900px;
            }
        }
    </style>
@endsection
