@extends('store.layouts.app')

@section('title', $seller->shop_name . ' - Cửa hàng')

@section('content')
    <div class="container py-4 shop-profile-page">
        <section class="shop-profile-card mb-4">
            <div class="shop-profile-brand">
                <div class="shop-profile-avatar" aria-hidden="true">
                    {{ mb_strtoupper(mb_substr($seller->shop_name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <h1 class="h4 fw-bold mb-0">{{ $seller->shop_name }}</h1>
                        <span class="badge rounded-pill text-bg-success">Shop đang hoạt động</span>
                    </div>

                    <div class="small text-secondary mt-2">
                        <i class="bi bi-shop me-1" aria-hidden="true"></i>
                        Cửa hàng trên Website Bán hàng Online
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if (session('account_role') === 'user')
                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    data-chat-start
                                    data-seller-id="{{ $seller->seller_id }}"
                                    data-shop-name="{{ $seller->shop_name }}">
                                <i class="bi bi-chat-dots me-1" aria-hidden="true"></i>
                                Chat với Shop
                            </button>
                        @else
                            <a href="{{ route('login.form') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-chat-dots me-1" aria-hidden="true"></i>
                                Chat với Shop
                            </a>
                        @endif

                        <a href="#shopProducts" class="btn btn-outline-primary btn-sm">
                            Xem sản phẩm
                        </a>
                    </div>
                </div>
            </div>

            <div class="shop-profile-details">
                <div class="shop-profile-stat">
                    <span class="small text-secondary">Sản phẩm đang bán</span>
                    <strong>{{ number_format($productCount) }}</strong>
                </div>

                <div class="shop-profile-stat">
                    <span class="small text-secondary">Tham gia</span>
                    <strong>{{ $seller->created_at?->format('m/Y') ?? '—' }}</strong>
                </div>

                <div class="shop-profile-address">
                    <span class="small text-secondary">Địa chỉ Shop</span>
                    <strong>{{ $seller->shop_address ?: 'Chưa cập nhật địa chỉ' }}</strong>

                    @php
                        $shopLocation = collect([
                            $seller->shop_ward,
                            $seller->shop_district,
                            $seller->shop_province,
                        ])->filter()->implode(', ');
                    @endphp

                    @if ($shopLocation)
                        <span class="small text-secondary">{{ $shopLocation }}</span>
                    @endif
                </div>
            </div>
        </section>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="status">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif

        @if (($shopVouchers ?? collect())->isNotEmpty())
            <section class="shop-vouchers-section mb-4" aria-labelledby="shopVouchersTitle">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
                    <div>
                        <h2 id="shopVouchersTitle" class="h5 fw-bold mb-1">Voucher của Shop</h2>
                        <p class="small text-secondary mb-0">Lưu ưu đãi của {{ $seller->shop_name }} để dùng khi mua hàng.</p>
                    </div>
                    <span class="badge rounded-pill text-bg-light border">
                        {{ $shopVouchers->count() }} voucher
                    </span>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                    @foreach ($shopVouchers as $voucher)
                        @php
                            $isSaved = in_array((int) $voucher->voucher_id, $savedVoucherIds ?? [], true);
                            $discountLabel = $voucher->discount_type === 'percent'
                                ? 'Giảm ' . rtrim(rtrim(number_format((float) $voucher->discount_value, 2, '.', ''), '0'), '.') . '%'
                                : 'Giảm ' . number_format((float) $voucher->discount_value, 0, ',', '.') . '₫';
                        @endphp

                        <article class="shop-voucher-card h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <span class="badge text-bg-info">Voucher Shop</span>
                                <i class="bi bi-ticket-perforated shop-voucher-icon" aria-hidden="true"></i>
                            </div>

                            <div class="shop-voucher-discount mt-2">{{ $discountLabel }}</div>
                            <div class="small text-secondary">
                                Mã <strong class="text-dark">{{ $voucher->code }}</strong>
                            </div>

                            <div class="shop-voucher-details small text-secondary mt-2">
                                <div>
                                    Đơn tối thiểu {{ number_format((float) $voucher->min_order_amount, 0, ',', '.') }}₫
                                </div>
                                @if ($voucher->discount_type === 'percent' && $voucher->max_discount)
                                    <div>Giảm tối đa {{ number_format((float) $voucher->max_discount, 0, ',', '.') }}₫</div>
                                @endif
                                <div>Hạn dùng đến {{ $voucher->expires_at->format('d/m/Y H:i') }}</div>
                            </div>

                            <div class="mt-auto pt-3">
                                @if ($isSaved)
                                    <button type="button" class="btn btn-success btn-sm w-100" disabled>
                                        <i class="bi bi-check2 me-1" aria-hidden="true"></i>Đã lưu
                                    </button>
                                @elseif (session()->has('account_id'))
                                    <form method="POST" action="{{ route('store.account.vouchers.save', ['type' => 'seller', 'voucher' => $voucher->voucher_id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-bookmark-plus me-1" aria-hidden="true"></i>Lưu voucher
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login.form') }}" class="btn btn-outline-primary btn-sm w-100">
                                        Đăng nhập để lưu
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section id="shopProducts" class="shop-products-section">
            <div class="shop-products-heading">
                <div>
                    <h2 class="h5 fw-bold mb-1">Tất cả sản phẩm</h2>
                    <p class="small text-secondary mb-0">
                        Sản phẩm đang được bán bởi {{ $seller->shop_name }}.
                    </p>
                </div>

                <form action="{{ route('store.shops.show', $seller->seller_id) }}"
                      method="GET"
                      class="shop-search">
                    <label for="shopSearch" class="visually-hidden">
                        Tìm sản phẩm trong Shop
                    </label>

                    <input id="shopSearch"
                           type="search"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control form-control-sm"
                           placeholder="Tìm sản phẩm trong Shop">

                    <button type="submit"
                            class="btn btn-primary btn-sm"
                            aria-label="Tìm kiếm">
                        <i class="bi bi-search" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

            @if ($products->isNotEmpty())
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @foreach ($products as $product)
                        @php
                            $productImage = $product->images->first()?->image_url
                                ?: $product->image_url;

                            $productImageUrl = $productImage
                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($productImage)
                                : null;
                        @endphp

                        <div class="col">
                            <a href="{{ route('store.products.show', $product->product_id) }}"
                               class="shop-product-link text-decoration-none">
                                <article class="shop-product-card h-100">
                                    <div class="shop-product-image">
                                        @if ($productImageUrl)
                                            <img src="{{ $productImageUrl }}"
                                                 alt="{{ $product->product_name }}"
                                                 loading="lazy">
                                        @else
                                            <span class="small text-secondary">Chưa có ảnh</span>
                                        @endif
                                    </div>

                                    <div class="shop-product-info">
                                        <h3 class="shop-product-name">{{ $product->product_name }}</h3>

                                        @if ($product->category)
                                            <span class="small text-secondary">
                                                {{ $product->category->category_name }}
                                            </span>
                                        @endif

                                        <div class="shop-product-price">
                                            {{ number_format((float) $product->price, 0, ',', '.') }}₫
                                        </div>

                                        <div class="small text-secondary">
                                            Đã bán {{ number_format($product->sold_count) }}
                                        </div>
                                    </div>
                                </article>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="shop-no-products">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                    <strong>Shop chưa có sản phẩm phù hợp</strong>
                    <span>Thử từ khóa khác hoặc quay lại sau nhé.</span>
                </div>
            @endif
        </section>
    </div>

    @push('styles')
        <style>
            .shop-profile-card,
            .shop-products-section {
                padding: 24px;
                border: 1px solid var(--shop-border, #e2e8f0);
                border-radius: 12px;
                background: var(--shop-card-bg, #fff);
                box-shadow: var(--shop-shadow, 0 2px 8px rgba(0, 0, 0, .04));
            }

            .shop-profile-card {
                display: grid;
                grid-template-columns: minmax(280px, .8fr) minmax(0, 1.2fr);
                gap: 28px;
                align-items: center;
            }

            .shop-profile-brand {
                display: flex;
                align-items: center;
                gap: 18px;
                min-width: 0;
            }

            .shop-profile-avatar {
                display: grid;
                width: 88px;
                height: 88px;
                flex: 0 0 88px;
                place-items: center;
                border: 1px solid var(--shop-border, #e2e8f0);
                border-radius: 50%;
                background: #f8fafc;
                color: var(--shop-primary, #1a94ff);
                font-size: 2rem;
                font-weight: 700;
            }

            .shop-profile-details {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px 28px;
            }

            .shop-profile-stat,
            .shop-profile-address {
                display: flex;
                flex-direction: column;
                gap: 4px;
                min-width: 0;
            }

            .shop-profile-address {
                grid-column: 1 / -1;
            }

            .shop-profile-address strong {
                font-weight: 500;
                overflow-wrap: anywhere;
            }


            .shop-vouchers-section {
                padding: 22px;
                border: 1px solid var(--shop-border, #e2e8f0);
                border-radius: 12px;
                background: var(--shop-card-bg, #fff);
                box-shadow: var(--shop-shadow, 0 2px 8px rgba(0, 0, 0, .04));
            }

            .shop-voucher-card {
                display: flex;
                flex-direction: column;
                padding: 16px;
                border: 1px solid var(--shop-border, #e2e8f0);
                border-left: 4px solid var(--shop-primary, #1a94ff);
                border-radius: 10px;
                background: var(--shop-card-bg, #fff);
            }

            .shop-voucher-icon {
                color: var(--shop-primary, #1a94ff);
                font-size: 1.25rem;
            }

            .shop-voucher-discount {
                color: var(--shop-price, #ee4d2d);
                font-size: 1.15rem;
                font-weight: 700;
            }

            .shop-voucher-details {
                line-height: 1.7;
            }
            .shop-products-heading {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                margin-bottom: 20px;
            }

            .shop-search {
                display: flex;
                width: min(100%, 360px);
                gap: 8px;
            }

            .shop-product-link {
                display: block;
                height: 100%;
            }

            .shop-product-card {
                overflow: hidden;
                border: 1px solid var(--shop-border, #e2e8f0);
                border-radius: 10px;
                background: #fff;
                color: var(--shop-text, #0f172a);
                transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            }

            .shop-product-link:hover .shop-product-card {
                transform: translateY(-3px);
                border-color: var(--shop-primary, #1a94ff);
                box-shadow: 0 6px 16px rgba(26, 148, 255, .12);
            }

            .shop-product-image {
                display: grid;
                aspect-ratio: 1 / 1;
                place-items: center;
                padding: 12px;
                background: #f8fafc;
            }

            .shop-product-image img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .shop-product-info {
                display: flex;
                flex-direction: column;
                gap: 7px;
                padding: 14px;
            }

            .shop-product-name {
                display: -webkit-box;
                overflow: hidden;
                margin: 0;
                color: var(--shop-text, #0f172a);
                font-size: .9rem;
                font-weight: 500;
                line-height: 1.4;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            .shop-product-price {
                color: var(--shop-price, #ee4d2d);
                font-size: 1rem;
                font-weight: 700;
            }

            .shop-no-products {
                display: flex;
                min-height: 220px;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 8px;
                border: 1px dashed var(--shop-border, #e2e8f0);
                border-radius: 10px;
                color: var(--shop-text-muted, #64748b);
                text-align: center;
            }

            .shop-no-products .bi {
                color: var(--shop-primary, #1a94ff);
                font-size: 2rem;
            }

            .shop-no-products strong {
                color: var(--shop-text, #0f172a);
            }

            @media (max-width: 767.98px) {
                .shop-profile-card {
                    grid-template-columns: 1fr;
                    gap: 22px;
                    padding: 18px;
                }

                .shop-products-section {
                    padding: 18px;
                }


            .shop-products-heading {
                    align-items: stretch;
                    flex-direction: column;
                }

                .shop-search {
                    width: 100%;
                }
            }

            @media (max-width: 420px) {
                .shop-profile-brand {
                    align-items: flex-start;
                    gap: 12px;
                }

                .shop-profile-avatar {
                    width: 60px;
                    height: 60px;
                    flex-basis: 60px;
                }

                .shop-profile-details {
                    gap: 14px;
                }
            }
        </style>
    @endpush
@endsection
