@extends('admin.layouts.app')
@section('title', 'Danh mục ngành hàng')
@section('page-title', 'Quản lý danh mục')
@section('content')
    <div class="admin-section-head">
        <div>
            <h2>Danh mục ngành hàng</h2>
            <p>Tạo danh mục cha/con và quản lý cấu trúc phân loại sản phẩm.</p>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="admin-panel p-4">
                <h3 class="h5 fw-bold mb-3">Thêm danh mục</h3>
                <form method="POST" action="{{ route('admin.categories.store') }}">@csrf<label class="form-label">Tên danh
                        mục</label><input name="category_name" maxlength="100" required class="form-control mb-3"
                        placeholder="VD: Điện thoại"><label class="form-label">Danh mục cha</label><select name="parent_id"
                        class="form-select mb-3">
                        <option value="">Danh mục cấp 1</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->category_id }}">{{ $parent->category_name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100">Thêm danh mục</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="admin-panel">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tên danh mục</th>
                                <th>Thuộc danh mục</th>
                                <th>Sản phẩm / Con</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td><strong>{{ $category->category_name }}</strong>
                                        <div class="text-secondary small">#{{ $category->category_id }}</div>
                                    </td>
                                    <td>{{ $category->parent?->category_name ?? 'Cấp 1' }}</td>
                                    <td>{{ $category->products_count }} / {{ $category->children_count }}</td>
                                    <td>
                                        <details class="admin-details">
                                            <summary>Chỉnh sửa</summary>
                                            <form method="POST" action="{{ route('admin.categories.update', $category) }}"
                                                class="mt-2">@csrf @method('PUT')<input
                                                    class="form-control form-control-sm mb-2" name="category_name"
                                                    value="{{ $category->category_name }}" required maxlength="100"><select
                                                    name="parent_id" class="form-select form-select-sm mb-2">
                                                    <option value="">Cấp 1</option>
                                                    @foreach ($parents as $parent)
                                                        @if ($parent->category_id !== $category->category_id)
                                                            <option value="{{ $parent->category_id }}"
                                                                @selected($category->parent_id === $parent->category_id)>{{ $parent->category_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-sm btn-primary">Lưu</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                onsubmit="return confirm('Xóa danh mục này?')" class="mt-2">@csrf
                                                @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button>
                                            </form>
                                        </details>
                                    </td>
                            </tr>@empty<tr>
                                    <td colspan="4" class="text-center py-4">Chưa có danh mục.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $categories->links() }}</div>
            </div>
        </div>
    </div>
@endsection
