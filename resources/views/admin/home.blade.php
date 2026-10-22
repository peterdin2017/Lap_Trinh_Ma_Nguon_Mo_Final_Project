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
            <div class="card admin-stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary mb-1">Tài khoản</div>
                        <div class="fs-3 fw-bold">{{ $accountCount }}</div>
                    </div>
                    <span class="admin-stat-card__icon">
                        <i class="bi bi-people" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card admin-stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary mb-1">Cửa hàng</div>
                        <div class="fs-3 fw-bold">{{ $shopCount }}</div>
                    </div>
                    <span class="admin-stat-card__icon">
                        <i class="bi bi-shop" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card admin-stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary mb-1">Chờ duyệt</div>
                        <div class="fs-3 fw-bold">{{ $pendingShopCount }}</div>
                    </div>
                    <span class="admin-stat-card__icon admin-stat-card__icon--warning">
                        <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card admin-content-card mt-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-4">
            <div>
                <h3 class="h5 fw-bold mb-1">Hồ sơ người bán</h3>
                <p class="text-secondary mb-0">
                    Xem hồ sơ mới gửi và duyệt hoặc từ chối cửa hàng.
                </p>
            </div>

            <a href="{{ route('admin.sellers.index') }}" class="btn btn-primary">
                <i class="bi bi-clipboard-check me-2" aria-hidden="true"></i>
                Mở danh sách duyệt
                @if ($pendingShopCount > 0)
                    <span class="badge text-bg-light text-primary ms-1">{{ $pendingShopCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <style>
        .admin-stat-card,
        .admin-content-card {
            color: var(--app-text);
            background-color: var(--app-card-bg);
            border: 1px solid var(--app-border);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 4%);
        }

        .admin-stat-card__icon {
            display: inline-flex;
            width: 52px;
            height: 52px;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background-color: var(--app-primary-light);
            color: var(--app-primary);
            font-size: 1.5rem;
        }

        .admin-stat-card__icon--warning {
            background-color: #FFF7E6;
            color: #D99000;
        }

        [data-bs-theme="dark"] .admin-stat-card__icon--warning {
            background-color: #3A2D16;
            color: #FBBF24;
        }
    </style>
@endsection
