@extends('seller.layouts.app')

@section('title', 'Sản phẩm - Seller Center')

@section('content')
    @php
        $statusMap = [
            'active' => ['Đang bán', 'status-active'],
            'hidden' => ['Đã ẩn', 'status-hidden'],
            'locked' => ['Bị khóa', 'status-locked'],
        ];
    @endphp

    <style>
        .seller-products-page {
            color: var(--seller-text);
        }

        .seller-products-page .products-heading,
        .seller-products-page .products-table-card {
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border: 1px solid var(--seller-border);
            border-radius: 12px;
            box-shadow: var(--seller-shadow);
        }

        .seller-products-page .products-table-card {
            overflow: hidden;
        }

        .seller-products-page .products-table {
            min-width: 850px;
            color: var(--seller-text);
            border-color: var(--seller-border);
        }

        .seller-products-page .products-table thead th {
            padding: .9rem 1rem;
            color: var(--seller-text-muted);
            background: var(--seller-hover-bg);
            border-bottom-color: var(--seller-border);
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .seller-products-page .products-table tbody td {
            padding: 1rem;
            color: var(--seller-text);
            background: var(--seller-card-bg);
            border-color: var(--seller-border);
            vertical-align: middle;
        }

        .seller-products-page .products-table tbody tr:hover td {
            background: var(--seller-hover-bg);
        }

        .seller-products-page .product-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border: 1px solid var(--seller-border);
            border-radius: .625rem;
            background: var(--seller-page-bg);
        }

        .seller-products-page .product-name {
            max-width: 280px;
            color: var(--seller-text);
            font-weight: 600;
            line-height: 1.4;
        }

        .seller-products-page .product-price {
            color: var(--seller-price) !important;
            font-weight: 700;
            white-space: nowrap;
        }

        .seller-products-page .status-active {
            color: #166534;
            background: #DCFCE7;
        }

        .seller-products-page .status-hidden {
            color: var(--seller-text-muted);
            background: var(--seller-hover-bg);
            border: 1px solid var(--seller-border);
        }

        .seller-products-page .status-locked {
            color: #991B1B;
            background: #FEE2E2;
        }

        [data-theme="dark"] .seller-products-page .status-active {
            color: #86EFAC;
            background: #14532D;
        }

        [data-theme="dark"] .seller-products-page .status-locked {
            color: #FCA5A5;
            background: #7F1D1D;
        }

        .seller-products-page .products-empty-icon {
            width: 3.5rem;
            height: 3.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--seller-primary);
            background: var(--seller-primary-light);
        }
    </style>

    <div class="seller-products-page">
        <div class="products-heading p-4 mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <span class="badge rounded-pill mb-2"
                      style="color: var(--seller-primary); background: var(--seller-primary-light);">
                    Seller Center
                </span>
                <h2 class="h3 fw-bold mb-1">Sản phẩm</h2>
                <p class="text-secondary mb-0">
                    Quản lý thông tin, giá bán và trạng thái sản phẩm của cửa hàng.
                </p>
            </div>

            <a href="{{ route('seller.products.create') }}"
               class="btn btn-primary px-3 rounded-3">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                Thêm sản phẩm
            </a>
        </div>

        {{-- Flash message được hiển thị một lần từ layout chung. --}}

        <section class="products-table-card">
            <div class="px-3 px-lg-4 py-3 border-bottom"
                 style="background: var(--seller-card-bg); border-color: var(--seller-border) !important;">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <h3 class="h6 fw-bold mb-1">Danh sách sản phẩm</h3>
                        <p class="small text-secondary mb-0">
                            Các sản phẩm đang được quản lý bởi cửa hàng của bạn.
                        </p>
                    </div>
                    <span class="badge rounded-pill text-secondary"
                          style="background: var(--seller-page-bg); border: 1px solid var(--seller-border);">
                        {{ $products->total() }} sản phẩm
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table products-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 90px;">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th class="text-end">Giá</th>
                            <th class="text-end">Tồn kho</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            @php
                                [$label, $statusClass] = $statusMap[$product->status]
                                    ?? [$product->status, 'status-hidden'];
                            @endphp

                            <tr>
                                <td>
                                    @if ($product->image_url)
                                        <img
                                            src="{{ asset('storage/' . ltrim($product->image_url, '/')) }}"
                                            alt="{{ $product->product_name }}"
                                            width="64"
                                            height="64"
                                            class="product-image"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="product-image d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-secondary fs-4" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="product-name">{{ $product->product_name }}</div>
                                </td>

                                <td class="text-secondary">
                                    {{ $product->category?->category_name ?? '—' }}
                                </td>

                                <td class="text-end product-price">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </td>

                                <td class="text-end">
                                    <span class="fw-semibold">
                                        {{ number_format($product->stock_quantity) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $statusClass }}">
                                        {{ $label }}
                                    </span>
                                </td>

                                <td class="text-center text-nowrap">
                                    @if ($product->status !== 'locked')
                                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                            <a href="{{ route('seller.products.edit', $product->product_id) }}"
                                               class="btn btn-sm btn-outline-primary rounded-2">
                                                <i class="bi bi-pencil me-1" aria-hidden="true"></i>
                                                Sửa
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('seller.products.visibility', $product->product_id) }}"
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm {{ $product->status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-2"
                                                    onclick="return confirm('{{ $product->status === 'active' ? 'Bạn muốn ẩn sản phẩm này?' : 'Bạn muốn bật bán lại sản phẩm này?' }}')"
                                                >
                                                    {{ $product->status === 'active' ? 'Ẩn' : 'Bán lại' }}
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="small text-secondary">
                                            Quản trị viên đã khóa
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-5">
                                    <span class="products-empty-icon mb-3">
                                        <i class="bi bi-box-seam fs-3" aria-hidden="true"></i>
                                    </span>
                                    <h4 class="h6 fw-bold">Shop chưa có sản phẩm</h4>
                                    <p class="small mb-3">
                                        Thêm sản phẩm đầu tiên để bắt đầu quản lý cửa hàng.
                                    </p>
                                    <a href="{{ route('seller.products.create') }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                                        Thêm sản phẩm
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="px-3 px-lg-4 py-3 border-top"
                     style="background: var(--seller-card-bg); border-color: var(--seller-border) !important;">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>
    </div>
@endsection
