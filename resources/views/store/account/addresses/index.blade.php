@extends('store.account.layouts.account')

@section('title', 'Địa chỉ nhận hàng')

@section('account_content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-3 mb-4">
                <div>
                    <h1 class="h4 fw-bold mb-1">Địa chỉ nhận hàng</h1>
                    <p class="text-secondary mb-0">Quản lý các địa chỉ giao hàng của bạn.</p>
                </div>

                <a href="{{ route('store.account.addresses.create') }}"
                   class="btn btn-primary text-nowrap">
                    <i class="bi bi-plus-lg me-1"></i>Thêm địa chỉ
                </a>
            </div>

            @if ($addresses->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-geo-alt text-secondary" style="font-size: 2.5rem;"></i>
                    <p class="text-secondary mt-3 mb-0">
                        Bạn chưa lưu địa chỉ nhận hàng nào.
                    </p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach ($addresses as $address)
                        <article class="border rounded p-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $address->recipient_name }}
                                        <span class="text-secondary fw-normal mx-1">|</span>
                                        <span class="text-secondary fw-normal">
                                            {{ $address->phone }}
                                        </span>

                                        @if ($address->is_default)
                                            <span class="badge text-bg-primary ms-2">Mặc định</span>
                                        @endif
                                    </div>

                                    <div class="text-secondary mt-2">
                                        {{ $address->street_address }},
                                        {{ $address->ward }},
                                        {{ $address->district }},
                                        {{ $address->province }}
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap align-items-start gap-2">
                                    <a href="{{ route('store.account.addresses.edit', $address->address_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Sửa
                                    </a>

                                    @unless ($address->is_default)
                                        <form method="POST"
                                              action="{{ route('store.account.addresses.default', $address->address_id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                Đặt mặc định
                                            </button>
                                        </form>
                                    @endunless

                                    <form method="POST"
                                          action="{{ route('store.account.addresses.destroy', $address->address_id) }}"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
