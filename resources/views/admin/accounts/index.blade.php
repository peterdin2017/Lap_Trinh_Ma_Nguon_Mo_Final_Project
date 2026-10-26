@extends('admin.layouts.app')
@section('title', 'Quản lý tài khoản')
@section('page-title', 'Quản lý tài khoản')
@section('content')
    @include('admin.partials.flash')
    <div class="admin-section-head">
        <div>
            <h2>Danh sách tài khoản</h2>
            <p>Tìm kiếm, kiểm tra vai trò và điều chỉnh quyền truy cập.</p>
        </div>
    </div>
    <form method="GET" class="admin-filter"><input name="q" class="form-control" value="{{ request('q') }}"
            placeholder="Tìm theo email..."><select name="role" class="form-select">
            <option value="">Tất cả vai trò</option>
            @foreach (['user' => 'Khách hàng', 'seller' => 'Người bán', 'admin' => 'Quản trị viên'] as $value => $text)
                <option value="{{ $value }}" @selected(request('role') === $value)>{{ $text }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select">
            <option value="">Mọi trạng thái</option>
            <option value="active" @selected(request('status') === 'active')>Hoạt động</option>
            <option value="locked" @selected(request('status') === 'locked')>Bị khóa</option>
        </select><button class="btn btn-primary">Lọc</button>
    </form>
    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td>#{{ $account->account_id }}</td>
                            <td class="fw-semibold">{{ $account->email }}</td>
                            <td>{{ ['user' => 'Khách hàng', 'seller' => 'Người bán', 'admin' => 'Quản trị viên'][$account->role] ?? $account->role }}
                            </td>
                            <td><span
                                    class="admin-pill {{ $account->status === 'active' ? 'success' : 'danger' }}">{{ $account->status === 'active' ? 'Hoạt động' : 'Đã khóa' }}</span>
                            </td>
                            <td>{{ $account->created_at?->format('d/m/Y') }}</td>
                            <td>
                                @if ($account->account_id !== auth()->user()->account_id)
                                    <form method="POST" action="{{ route('admin.accounts.status', $account) }}"
                                        onsubmit="return confirm('Xác nhận đổi trạng thái tài khoản?')">@csrf
                                        @method('PATCH')<input type="hidden" name="status"
                                            value="{{ $account->status === 'active' ? 'locked' : 'active' }}"><button
                                            class="btn btn-sm {{ $account->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $account->status === 'active' ? 'Khóa' : 'Mở khóa' }}</button>
                                </form>@else<span class="text-secondary small">Tài khoản hiện tại</span>
                                @endif
                            </td>
                    </tr>@empty<tr>
                            <td colspan="6" class="text-center py-5">Không có tài khoản phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $accounts->links() }}</div>
    </div>
@endsection
