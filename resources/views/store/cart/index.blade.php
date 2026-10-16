@extends('store.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <main class="container py-4">
        <h1 class="h3 fw-bold mb-4">Giỏ hàng</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @if (empty($cart))
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-cart3 display-4 text-secondary"></i>
                    <p class="mt-3 mb-0 text-secondary">Giỏ hàng của bạn đang trống.</p>
                    <a href="{{ route('store.home') }}" class="btn btn-primary mt-3">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label fw-semibold" for="select-all">
                            Chọn tất cả
                        </label>
                    </div>
                </div>

                <div class="list-group list-group-flush" id="cart-items">
                    @foreach ($cart as $key => $item)
                        @php
                            $lineTotal = (float) $item['price'] * (int) $item['quantity'];
                        @endphp

                        <div
                            class="list-group-item p-3"
                            data-line-total="{{ $lineTotal }}"
                        >
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <input
                                        class="form-check-input cart-item-checkbox"
                                        type="checkbox"
                                        value="{{ $key }}"
                                        aria-label="Chọn {{ $item['product_name'] }}"
                                    >
                                </div>

                                <div class="col-4 col-sm-3 col-md-2">
                                    <a href="{{ route('store.products.show', $item['product_id']) }}">
                                        @if (!empty($item['image_url']))
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item['image_url']) }}"
                                                alt="{{ $item['product_name'] }}"
                                                class="img-fluid rounded cart-product-image"
                                            >
                                        @else
                                            <div class="cart-product-placeholder rounded">
                                                <i class="bi bi-image text-secondary fs-2"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <div class="col">
                                    <a
                                        href="{{ route('store.products.show', $item['product_id']) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $item['product_name'] }}
                                    </a>

                                    @if (!empty($item['variant_name']))
                                        <div class="small text-secondary mt-1">
                                            Mẫu: {{ $item['variant_name'] }}
                                        </div>
                                    @endif

                                    <div class="text-danger fw-semibold mt-2">
                                        {{ number_format($item['price'], 0, ',', '.') }}₫
                                    </div>

                                    <div class="small text-secondary">
                                        Số lượng: {{ $item['quantity'] }}
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <form
                                        method="POST"
                                        action="{{ route('store.cart.items.destroy', ['key' => $key]) }}"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i>Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-footer bg-white p-3">
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md">
                            <div class="text-secondary">
                                Đã chọn <span id="selected-count">0</span> sản phẩm
                            </div>

                            <div class="fw-semibold mt-1">
                                Tổng tiền:
                                <span class="text-danger fs-5" id="selected-total">0₫</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-auto d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('store.home') }}" class="btn btn-outline-primary">
                                Tiếp tục mua sắm
                            </a>

                            <button
                                type="button"
                                class="btn btn-primary"
                                id="buy-selected"
                                disabled
                            >
                                Mua hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <style>
        .cart-product-image,
        .cart-product-placeholder {
            width: 110px;
            height: 110px;
            object-fit: contain;
            background: #f8f9fa;
        }

        .cart-product-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-checkbox,
        #select-all {
            width: 1.15rem;
            height: 1.15rem;
            cursor: pointer;
        }
    </style>

    @if (!empty($cart))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAll = document.getElementById('select-all');
                const itemCheckboxes = Array.from(
                    document.querySelectorAll('.cart-item-checkbox')
                );
                const selectedCount = document.getElementById('selected-count');
                const selectedTotal = document.getElementById('selected-total');
                const buyButton = document.getElementById('buy-selected');

                const formatCurrency = (amount) =>
                    new Intl.NumberFormat('vi-VN').format(amount) + '₫';

                function updateSummary() {
                    const selected = itemCheckboxes.filter((checkbox) => checkbox.checked);

                    const total = selected.reduce((sum, checkbox) => {
                        const row = checkbox.closest('[data-line-total]');
                        return sum + Number(row.dataset.lineTotal || 0);
                    }, 0);

                    selectedCount.textContent = selected.length;
                    selectedTotal.textContent = formatCurrency(total);
                    buyButton.disabled = selected.length === 0;

                    selectAll.checked =
                        itemCheckboxes.length > 0 &&
                        selected.length === itemCheckboxes.length;

                    selectAll.indeterminate =
                        selected.length > 0 &&
                        selected.length < itemCheckboxes.length;
                }

                selectAll.addEventListener('change', function () {
                    itemCheckboxes.forEach((checkbox) => {
                        checkbox.checked = selectAll.checked;
                    });

                    updateSummary();
                });

                itemCheckboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', updateSummary);
                });

                updateSummary();
            });
        </script>
    @endif
@endsection
