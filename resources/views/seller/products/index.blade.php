@extends('seller.layouts.app')

@section('title', 'Sản phẩm - Seller Center')

@section('content')
    @php
        // Tên hiển thị và màu sắc theo trạng thái sản phẩm.
        $statusMap = [
            'active' => ['Đang bán', 'success'],
            'hidden' => ['Đã ẩn', 'secondary'],
            'locked' => ['Bị khóa', 'danger'],
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Sản phẩm</h2>
            <p class="text-secondary mb-0">Danh sách sản phẩm của cửa hàng.</p>
        </div>

        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th class="text-end">Giá</th>
                        <th class="text-end">Tồn kho</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        @php
                            [$label, $color] = $statusMap[$product->status]
                                ?? [$product->status, 'secondary'];
                        @endphp

                        <tr>
                            <td style="width: 90px;">
                                @if ($product->image_url)
                                    <img
                                        src="{{ asset('storage/' . ltrim($product->image_url, '/')) }}"
                                        alt="{{ $product->product_name }}"
                                        width="64"
                                        height="64"
                                        class="rounded"
                                        style="object-fit: cover;"
                                    >
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 64px; height: 64px;">
                                        <i class="bi bi-image text-secondary"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="fw-semibold">{{ $product->product_name }}</td>
                            <td>{{ $product->category?->category_name ?? '—' }}</td>
                            <td class="text-end">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </td>
                            <td class="text-end">{{ $product->stock_quantity }}</td>
                            <td>
                                <span class="badge text-bg-{{ $color }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($product->status !== 'locked')
                                    <a
                                        href="{{ route('seller.products.edit', $product->product_id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Sửa
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('seller.products.visibility', $product->product_id) }}"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-sm {{ $product->status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                            onclick="return confirm('{{ $product->status === 'active' ? 'Bạn muốn ẩn sản phẩm này?' : 'Bạn muốn bật bán lại sản phẩm này?' }}')"
                                        >
                                            {{ $product->status === 'active' ? 'Ẩn' : 'Bán lại' }}
                                        </button>
                                    </form>
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
                                Shop chưa có sản phẩm nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="card-footer bg-white">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
