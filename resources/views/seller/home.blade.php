@extends('seller.layouts.app')

@section('title', 'Tổng quan - Seller Center')

@section('content')
    <style>
        .seller-dashboard {
            color: var(--seller-text);
        }

        .seller-dashboard__description,
        .seller-dashboard__metric-label,
        .seller-dashboard__welcome-description {
            color: var(--seller-text-muted);
        }

        .seller-dashboard .dashboard-card,
        .seller-dashboard .welcome-card {
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border: 1px solid var(--seller-border);
            border-radius: 12px;
            box-shadow: var(--seller-shadow);
        }

        .seller-dashboard .dashboard-card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .seller-dashboard .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(15, 23, 42, .08);
        }

        .seller-dashboard .dashboard-icon {
            display: inline-flex;
            width: 2.75rem;
            height: 2.75rem;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            color: var(--seller-primary);
            background: var(--seller-primary-light);
            border-radius: 50%;
        }

        @media (prefers-reduced-motion: reduce) {
            .seller-dashboard .dashboard-card,
            .seller-dashboard .dashboard-card:hover {
                transform: none;
                transition: none;
            }
        }
    </style>

    <div class="seller-dashboard">
        <header class="mb-4">
            <h2 class="h3 fw-bold mb-1">Tổng quan cửa hàng</h2>
            <p class="seller-dashboard__description mb-0">
                Theo dõi tình hình hoạt động của cửa hàng tại đây.
            </p>
        </header>

        <div class="row g-3">
            <div class="col-sm-6 col-xl-3">
                <article class="card dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="seller-dashboard__metric-label mb-2">
                                    Sản phẩm
                                </div>
                                <div class="fs-3 fw-bold">0</div>
                            </div>
                            <span class="dashboard-icon">
                                <i class="bi bi-box-seam fs-4" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="card dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="seller-dashboard__metric-label mb-2">
                                    Đơn hàng mới
                                </div>
                                <div class="fs-3 fw-bold">0</div>
                            </div>
                            <span class="dashboard-icon">
                                <i class="bi bi-bag fs-4" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="card dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="seller-dashboard__metric-label mb-2">
                                    Đơn cần xử lý
                                </div>
                                <div class="fs-3 fw-bold">0</div>
                            </div>
                            <span class="dashboard-icon">
                                <i class="bi bi-clock-history fs-4" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="card dashboard-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="seller-dashboard__metric-label mb-2">
                                    Doanh thu
                                </div>
                                <div class="fs-3 fw-bold">0₫</div>
                            </div>
                            <span class="dashboard-icon">
                                <i class="bi bi-currency-exchange fs-4" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <section class="welcome-card mt-4 p-4">
            <div class="d-flex align-items-start gap-3">
                <span class="dashboard-icon">
                    <i class="bi bi-shop fs-4" aria-hidden="true"></i>
                </span>

                <div>
                    <h3 class="h5 fw-bold mb-2">
                        Chào mừng đến với Seller Center
                    </h3>
                    <p class="seller-dashboard__welcome-description mb-0">
                        Các số liệu sẽ được cập nhật khi chức năng sản phẩm và đơn hàng được triển khai.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
