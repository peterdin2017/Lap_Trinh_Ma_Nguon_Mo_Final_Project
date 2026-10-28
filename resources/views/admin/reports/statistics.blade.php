@extends('admin.layouts.app')

@section('title', 'Thống kê toàn sàn')
@section('page-title', 'Thống kê toàn sàn')

@section('content')
    @php
        $maxDailyRevenue = max((float) $dailyStatistics->max('revenue'), 1);

        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang vận chuyển',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
    @endphp

    <div class="admin-statistics">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <span class="text-primary fw-semibold small">ADMIN CENTER / REPORTS</span>
                <h2 class="fw-bold mt-1 mb-1">Thống kê toàn sàn</h2>
                <p class="text-secondary mb-0">
                    Theo dõi đơn hàng, phí sàn và hoạt động theo khoảng thời gian.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a
                    href="{{ route('admin.reports.statistics.export', [
                        'from' => $from,
                        'to' => $to,
                    ]) }}"
                    class="btn btn-success"
                >
                    <i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i>
                    Xuất Excel
                </a>

                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-flag me-1" aria-hidden="true"></i>
                    Báo cáo vi phạm
                </a>
            </div>
        </div>

        <section class="admin-panel p-3 p-lg-4 mb-4" aria-label="Bộ lọc thời gian">
            <form action="{{ route('admin.reports.statistics') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label for="report-from" class="form-label fw-semibold">Từ ngày</label>
                        <input
                            id="report-from"
                            type="date"
                            name="from"
                            value="{{ $from }}"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="report-to" class="form-label fw-semibold">Đến ngày</label>
                        <input
                            id="report-to"
                            type="date"
                            name="to"
                            value="{{ $to }}"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1" aria-hidden="true"></i>
                            Lọc báo cáo
                        </button>

                        <a href="{{ route('admin.reports.statistics') }}" class="btn btn-outline-secondary">
                            Đặt lại
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3 mb-0" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif
            </form>
        </section>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Doanh thu phí sàn</div>
                    <div class="admin-kpi-number">
                        {{ number_format($revenue, 0, ',', '.') }}₫
                    </div>
                    <i class="bi bi-currency-exchange admin-kpi-symbol" aria-hidden="true"></i>
                    <small class="text-secondary">30.000₫ mỗi đơn hoàn thành</small>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Tổng đơn hàng</div>
                    <div class="admin-kpi-number">{{ number_format($totalOrders) }}</div>
                    <i class="bi bi-receipt admin-kpi-symbol" aria-hidden="true"></i>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Đơn hoàn thành</div>
                    <div class="admin-kpi-number">{{ number_format($completedOrders) }}</div>
                    <i class="bi bi-bag-check admin-kpi-symbol" aria-hidden="true"></i>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Đơn đã hủy</div>
                    <div class="admin-kpi-number">{{ number_format($cancelledOrders) }}</div>
                    <i class="bi bi-bag-x admin-kpi-symbol" aria-hidden="true"></i>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Khách hàng mới</div>
                    <div class="admin-kpi-number">{{ number_format($newCustomers) }}</div>
                    <i class="bi bi-person-plus admin-kpi-symbol" aria-hidden="true"></i>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Shop mới</div>
                    <div class="admin-kpi-number">{{ number_format($newShops) }}</div>
                    <i class="bi bi-shop admin-kpi-symbol" aria-hidden="true"></i>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="admin-kpi h-100">
                    <div class="admin-kpi-label">Sản phẩm mới</div>
                    <div class="admin-kpi-number">{{ number_format($newProducts) }}</div>
                    <i class="bi bi-box-seam admin-kpi-symbol" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-4">
                <section class="admin-panel h-100">
                    <div class="admin-panel-heading">
                        <h3>Đơn hàng theo trạng thái</h3>
                    </div>

                    <div class="p-3">
                        @foreach ($statusLabels as $status => $label)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span>{{ $label }}</span>
                                <span class="fw-bold">
                                    {{ number_format((int) ($statusCounts[$status] ?? 0)) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-8">
                <section class="admin-panel h-100">
                    <div class="admin-panel-heading">
                        <div>
                            <h3>Phí sàn theo ngày</h3>
                            <small class="text-secondary">
                                30.000₫ cho mỗi đơn hoàn thành; đơn hủy không tính phí.
                            </small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Tổng đơn</th>
                                    <th>Đơn hoàn thành</th>
                                    <th>Phí sàn</th>
                                    <th style="min-width: 120px;">Biểu đồ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dailyStatistics as $day)
                                    @php
                                        $barWidth = min(
                                            100,
                                            ($day['revenue'] / $maxDailyRevenue) * 100
                                        );
                                    @endphp

                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}
                                        </td>
                                        <td>{{ number_format($day['orders']) }}</td>
                                        <td>{{ number_format($day['completed_orders']) }}</td>
                                        <td class="fw-semibold">
                                            {{ number_format($day['revenue'], 0, ',', '.') }}₫
                                        </td>
                                        <td>
                                            <div
                                                class="progress"
                                                role="progressbar"
                                                aria-label="Phí sàn ngày {{ $day['label'] }}"
                                                aria-valuenow="{{ round($barWidth) }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            >
                                                <div
                                                    class="progress-bar"
                                                    style="width: {{ $barWidth }}%"
                                                ></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">
                                            Không có dữ liệu trong khoảng thời gian này.
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
