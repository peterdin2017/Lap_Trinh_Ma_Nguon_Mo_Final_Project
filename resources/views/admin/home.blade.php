@extends('admin.layouts.app')

@section('title', 'Tổng quan - Admin Center')
@section('page-title', 'Tổng quan hệ thống')

@section('content')
    <div class="mb-4">
        <h2 class="h4 fw-bold mb-1">Tổng quan hệ thống</h2>
        <p class="text-secondary mb-0">Theo dõi tài khoản, cửa hàng và hồ sơ cần xử lý.</p>
    </div>

    <div class="row g-3">
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary">Tài khoản</div>
                        <div class="fs-3 fw-bold">{{ $accountCount }}</div>
                    </div>
                    <i class="bi bi-people fs-1 text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary">Cửa hàng</div>
                        <div class="fs-3 fw-bold">{{ $shopCount }}</div>
                    </div>
                    <i class="bi bi-shop fs-1 text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary">Chờ duyệt</div>
                        <div class="fs-3 fw-bold">{{ $pendingShopCount }}</div>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="h5 fw-bold mb-1">Hồ sơ người bán</h3>
                <p class="text-secondary mb-0">Xem hồ sơ mới gửi và duyệt hoặc từ chối cửa hàng.</p>
            </div>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-primary">
                <i class="bi bi-clipboard-check me-2"></i>Mở danh sách duyệt
                @if ($pendingShopCount > 0)
                    <span class="badge text-bg-light text-primary ms-1">{{ $pendingShopCount }}</span>
                @endif
            </a>
        </div>
    </div>
@endsection
