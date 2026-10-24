@if ($product->seller)
    <section class="card border-0 shadow-sm my-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-4">
                    <div class="d-flex align-items-center gap-3">
                        <a
                            href="{{ route('store.shops.show', $product->seller->seller_id) }}"
                            class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0 text-decoration-none"
                            style="width: 72px; height: 72px;"
                            aria-label="Xem cửa hàng {{ $product->seller->shop_name }}"
                        >
                            <span class="fs-3 fw-bold text-primary">
                                {{ mb_strtoupper(mb_substr($product->seller->shop_name, 0, 1)) }}
                            </span>
                        </a>

                        <div>
                            <h2 class="h5 mb-2">
                                <a
                                    href="{{ route('store.shops.show', $product->seller->seller_id) }}"
                                    class="text-dark text-decoration-none"
                                >
                                    {{ $product->seller->shop_name }}
                                </a>
                            </h2>

                            <span class="badge text-bg-success">Shop đang hoạt động</span>

                            @if (session('account_role') === 'user')
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary d-block mt-2"
                                    data-chat-start
                                    data-seller-id="{{ $product->seller->seller_id }}"
                                    data-shop-name="{{ $product->seller->shop_name }}"
                                    data-product-id="{{ $product->product_id }}"
                                >
                                    <i class="bi bi-chat-dots me-1" aria-hidden="true"></i>
                                    Chat với Shop
                                </button>
                            @else
                                <a
                                    href="{{ route('login.form') }}"
                                    class="btn btn-sm btn-outline-primary d-block mt-2"
                                >
                                    <i class="bi bi-chat-dots me-1" aria-hidden="true"></i>
                                    Chat với Shop
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="small text-secondary">Sản phẩm đang bán</div>
                            <div class="fw-semibold">
                                {{ $product->seller->products()->where('status', 'active')->count() }}
                            </div>
                        </div>

                        <div class="col-6 col-md-4">
                            <div class="small text-secondary">Tham gia</div>
                            <div class="fw-semibold">
                                {{ $product->seller->created_at?->format('m/Y') ?? '—' }}
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="small text-secondary">Địa chỉ Shop</div>
                            <div>{{ $product->seller->shop_address ?: 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
