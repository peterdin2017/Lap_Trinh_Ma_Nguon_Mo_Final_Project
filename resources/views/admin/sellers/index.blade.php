@extends('admin.layouts.app')

@section('title', 'Duyệt cửa hàng - Admin Center')
@section('page-title', 'Duyệt cửa hàng')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Hồ sơ chờ duyệt</h2>
            <p class="text-secondary mb-0">Xem thông tin shop và quyết định duyệt hồ sơ người bán.</p>
        </div>
        <span class="badge rounded-pill text-bg-warning fs-6 px-3 py-2">
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Cửa hàng</th>
                            <th>Email người bán</th>
                            <th>Địa chỉ</th>
                            <th>Ngày gửi</th>
                            <th class="text-end px-3">Xử lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sellers as $seller)
                            <tr>
                                <td class="px-3">
                                    <div class="fw-semibold">{{ $seller->shop_name }}</div>
                                    <div class="small text-secondary">Mã shop: {{ $seller->seller_id }}</div>
                                </td>
                                <td>{{ $seller->account?->email ?? 'Không có email' }}</td>
                                <td>
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
                                <td class="text-end px-3">
                                    <div class="d-flex flex-column align-items-end gap-2">
                                        <form method="POST"
                                              action="{{ route('admin.sellers.approve', $seller) }}"
                                              onsubmit="return confirm('Duyệt cửa hàng {{ addslashes($seller->shop_name) }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success text-nowrap">
                                                <i class="bi bi-check-circle me-1"></i>Duyệt shop
                                            </button>
                                        </form>

                                        <details class="text-start">
                                            <summary class="text-danger small" style="cursor: pointer;">
                                                Từ chối hồ sơ
                                            </summary>
                                            <form method="POST"
                                                  action="{{ route('admin.sellers.reject', $seller) }}"
                                                  class="mt-2"
                                                  style="min-width: 230px;">
                                                @csrf
                                                <label for="reject_reason_{{ $seller->seller_id }}" class="form-label small">
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
                                    <i class="bi bi-check2-circle display-6 text-success"></i>
                                    <p class="fw-semibold mt-3 mb-1">Đã xử lý hết hồ sơ chờ duyệt</p>
                                    <p class="text-secondary mb-0">Khi có đăng ký mới, hồ sơ sẽ xuất hiện tại đây.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($sellers->hasPages())
            <div class="card-footer bg-white">
                {{ $sellers->links() }}
            </div>
        @endif
    </div>
@endsection
