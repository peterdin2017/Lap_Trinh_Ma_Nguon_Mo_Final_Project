@extends('admin.layouts.app')
@section('title', 'Kiểm duyệt sản phẩm')
@section('page-title', 'Kiểm duyệt sản phẩm')
@section('content')
    <div class="admin-section-head">
        <div>
            <h2>Danh sách sản phẩm</h2>
            <p>Chặn sản phẩm vi phạm, ghi rõ lý do và kiểm soát việc mở khóa.</p>
        </div>
    </div>
    <form method="GET" class="admin-filter"><input class="form-control" name="q" value="{{ request('q') }}"
            placeholder="Tên sản phẩm..."><select class="form-select" name="status">
            <option value="">Mọi trạng thái</option>
            @foreach (['active' => 'Đang bán', 'hidden' => 'Đang ẩn', 'locked' => 'Bị khóa'] as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary">Tìm kiếm</button>
    </form>
    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Shop / Danh mục</th>
                        <th>Giá bán</th>
                        <th>Trạng thái</th>
                        <th>Kiểm duyệt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex gap-3 align-items-center">
                                    @if ($product->image_src)
                                        <img src="{{ $product->image_src }}" class="admin-product-thumb"
                                        alt="">@else<div class="admin-product-thumb admin-thumb-empty"><i
                                                class="bi bi-image"></i></div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->product_name }}</strong>
                                        <div class="text-secondary small">#{{ $product->product_id }} ·
                                            {{ $product->brand }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->seller?->shop_name ?? 'Không xác định' }}<div class="text-secondary small">
                                    {{ $product->category?->category_name }}</div>
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                            <td><span
                                    class="admin-pill {{ $product->status === 'locked' ? 'danger' : ($product->status === 'active' ? 'success' : 'warning') }}">{{ ['locked' => 'Bị khóa', 'active' => 'Đang bán', 'hidden' => 'Đang ẩn'][$product->status] ?? $product->status }}</span>
                                @if ($product->locked_reason)
                                    <div class="small text-secondary mt-1">{{ $product->locked_reason }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($product->status === 'locked')
                                    <form method="POST" action="{{ route('admin.products.unlock', $product) }}"
                                        onsubmit="return confirm('Mở khóa và đưa sản phẩm về trạng thái ẩn?')">@csrf
                                        @method('PATCH')<button class="btn btn-sm btn-outline-success">Mở khóa</button>
                                </form>@else<details class="admin-details">
                                        <summary>Khóa sản phẩm</summary>
                                        <form method="POST" action="{{ route('admin.products.lock', $product) }}"
                                            class="mt-2">@csrf @method('PATCH')<input
                                                class="form-control form-control-sm mb-2" name="locked_reason"
                                                maxlength="255" required placeholder="Lý do khóa"><button
                                                class="btn btn-sm btn-outline-danger">Xác nhận khóa</button></form>
                                    </details>
                                @endif
                            </td>
                    </tr>@empty<tr>
                            <td colspan="5" class="text-center py-5">Không có sản phẩm phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $products->links() }}</div>
    </div>
@endsection
