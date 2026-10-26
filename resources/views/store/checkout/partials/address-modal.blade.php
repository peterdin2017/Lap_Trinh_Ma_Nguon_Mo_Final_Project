@if ($addresses->isNotEmpty())
    <div
        class="modal fade"
        id="checkout-address-modal"
        tabindex="-1"
        aria-labelledby="checkout-address-modal-title"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content checkout-address-modal">
                <div class="modal-header">
                    <h2
                        class="modal-title h5 fw-bold"
                        id="checkout-address-modal-title"
                    >
                        Chọn địa chỉ nhận hàng
                    </h2>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Đóng"
                    ></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex flex-column gap-2">
                        @foreach ($addresses as $address)
                            <button
                                type="button"
                                class="checkout-address-option {{ $defaultAddress?->address_id === $address->address_id ? 'is-default' : '' }}"
                                data-checkout-address
                                data-name="{{ $address->recipient_name }}"
                                data-phone="{{ $address->phone }}"
                                data-street="{{ $address->street_address }}"
                                data-ward="{{ $address->ward }}"
                                data-district="{{ $address->district }}"
                                data-province="{{ $address->province }}"
                                data-is-default="{{ $address->is_default ? '1' : '0' }}"
                            >
                                <span class="d-flex flex-wrap align-items-center gap-2">
                                    <strong>{{ $address->recipient_name }}</strong>
                                    <span class="text-secondary">
                                        {{ $address->phone }}
                                    </span>

                                    @if ($address->is_default)
                                        <span class="badge rounded-pill text-bg-primary">
                                            Mặc định
                                        </span>
                                    @endif
                                </span>

                                <span class="d-block small text-secondary mt-1">
                                    {{ $address->street_address }},
                                    {{ $address->ward }},
                                    {{ $address->district }},
                                    {{ $address->province }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <a
                        href="{{ route('store.account.addresses.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Quản lý địa chỉ
                    </a>

                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal"
                    >
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
