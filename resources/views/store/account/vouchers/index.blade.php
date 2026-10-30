@extends('store.account.layouts.account')

@section('title', 'Voucher của tôi')

@section('account_content')
    @php
        $money = fn ($amount) => number_format((float) $amount, 0, ',', '.') . '₫';

        $voucherCode = fn ($voucher) => $voucher->code
            ?? $voucher->voucher_code
            ?? 'Voucher';

        $discountText = function ($voucher) use ($money) {
            $type = $voucher->discount_type ?? null;
            $value = $voucher->discount_value
                ?? $voucher->discount_amount
                ?? $voucher->discount_percent
                ?? null;

            if ($value === null) {
                return 'Ưu đãi';
            }

            if (in_array($type, ['percent', 'percentage'], true)) {
                $percent = rtrim(
                    rtrim(number_format((float) $value, 2, '.', ''), '0'),
                    '.'
                );

                $text = "Giảm {$percent}%";
                $maxDiscount = $voucher->max_discount
                    ?? $voucher->max_discount_amount
                    ?? null;

                if ($maxDiscount) {
                    $text .= ' tối đa ' . $money($maxDiscount);
                }

                return $text;
            }

            return 'Giảm ' . $money($value);
        };

        $voucherTypeLabel = fn ($type) => $type === 'platform'
            ? 'Voucher sàn'
            : 'Voucher Shop';

        // Controller cần truyền danh sách voucher sàn đang hoạt động
        // mà tài khoản chưa lưu.
        $availablePlatformVouchers = collect(
            $availablePlatformVouchers ?? []
        );

        $now = now();

        $validSavedVouchers = collect($savedVouchers ?? [])
            ->filter(function ($savedVoucher) use ($now): bool {
                $voucher = $savedVoucher->voucher;

                if (! $voucher || ($voucher->status ?? null) !== 'active') {
                    return false;
                }

                $expiresAt = $voucher->expires_at ?? null;
                if ($expiresAt !== null
                    && \Illuminate\Support\Carbon::parse($expiresAt)->lessThan($now)) {
                    return false;
                }

                $usageLimit = $voucher->usage_limit ?? null;

                return $usageLimit === null
                    || (int) $voucher->used_count < (int) $usageLimit;
            })
            ->values();
    @endphp

    <div class="buyer-vouchers">
        <header class="mb-4">
            <h1 class="h3 fw-bold mb-1">Voucher của tôi</h1>
            <p class="text-muted mb-0">
                Lưu voucher sàn tại đây. Voucher Shop được lưu từ trang của từng cửa hàng.
            </p>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Voucher sàn có thể lưu</h2>
                    <p class="text-muted small mb-0">
                        Voucher do sàn phát hành, có thể dùng cùng voucher Shop khi thanh toán.
                    </p>
                </div>

                <span class="badge rounded-pill text-bg-primary">
                    {{ $availablePlatformVouchers->count() }} voucher
                </span>
            </div>

            <div class="card-body">
                @if ($availablePlatformVouchers->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-ticket-perforated fs-2" aria-hidden="true"></i>
                        <p class="fw-semibold mt-2 mb-1">
                            Hiện không có voucher sàn mới
                        </p>
                        <p class="small mb-0">
                            Voucher sàn đang có hoặc đã lưu sẽ hiển thị tại đây.
                        </p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($availablePlatformVouchers as $voucher)
                            @php
                                $minimumOrder = $voucher->min_order_amount
                                    ?? $voucher->minimum_order_amount
                                    ?? 0;

                                $expiresAt = $voucher->expires_at ?? null;
                            @endphp

                            <div class="col-12 col-md-6">
                                <article class="voucher-card h-100">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="badge text-bg-primary">
                                            Voucher sàn
                                        </span>

                                        @if ($voucher->usage_limit !== null)
                                            <span class="small text-muted">
                                                Còn
                                                {{ max(0, (int) $voucher->usage_limit - (int) $voucher->used_count) }}
                                                lượt
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div class="voucher-card__code">
                                            {{ $voucherCode($voucher) }}
                                        </div>

                                        <div class="voucher-card__discount mt-2">
                                            {{ $discountText($voucher) }}
                                        </div>
                                    </div>

                                    <div class="small text-muted mt-3">
                                        <div>
                                            Đơn tối thiểu: {{ $money($minimumOrder) }}
                                        </div>

                                        @if ($expiresAt)
                                            <div>
                                                Hạn dùng:
                                                {{ \Illuminate\Support\Carbon::parse($expiresAt)->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>

                                    <form
                                        method="POST"
                                        action="{{ route('store.account.vouchers.save', [
                                            'type' => 'platform',
                                            'voucher' => $voucher->platform_voucher_id,
                                        ]) }}"
                                        class="mt-auto pt-3"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-outline-primary w-100"
                                        >
                                            <i class="bi bi-bookmark-plus me-1" aria-hidden="true"></i>
                                            Lưu voucher sàn
                                        </button>
                                    </form>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Voucher đã lưu</h2>
                    <p class="text-muted small mb-0">
                        Voucher Shop và voucher sàn được quản lý riêng theo loại.
                    </p>
                </div>

                <span class="badge rounded-pill text-bg-light">
                    {{ $validSavedVouchers->count() }} voucher
                </span>
            </div>

            <div class="card-body">
                @if ($validSavedVouchers->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-ticket-perforated fs-2" aria-hidden="true"></i>
                        <p class="fw-semibold mt-2 mb-1">
                            Bạn chưa lưu voucher nào
                        </p>
                        <p class="small mb-0">
                            Lưu voucher sàn ở phía trên hoặc lưu voucher Shop trên trang cửa hàng.
                        </p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($validSavedVouchers as $savedVoucher)
                            @php
                                $voucher = $savedVoucher->voucher;
                                $type = $savedVoucher->voucher_type;
                                $expiresAt = $voucher->expires_at ?? null;
                                $minimumOrder = $voucher->min_order_amount
                                    ?? $voucher->minimum_order_amount
                                    ?? $voucher->min_order_value
                                    ?? 0;
                            @endphp

                            <div class="col-12 col-md-6">
                                <article class="voucher-card h-100">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="badge {{ $type === 'platform' ? 'text-bg-primary' : 'text-bg-info' }}">
                                            {{ $voucherTypeLabel($type) }}
                                        </span>

                                        <form
                                            method="POST"
                                            action="{{ route('store.account.vouchers.remove', [
                                                'type' => $type,
                                                'voucher' => $savedVoucher->voucher_id,
                                            ]) }}"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Bỏ lưu
                                            </button>
                                        </form>
                                    </div>

                                    <div class="mt-3">
                                        <div class="voucher-card__code">
                                            {{ $voucherCode($voucher) }}
                                        </div>

                                        <div class="voucher-card__discount mt-2">
                                            {{ $discountText($voucher) }}
                                        </div>
                                    </div>

                                    <div class="small text-muted mt-3">
                                        <div>
                                            Đơn tối thiểu: {{ $money($minimumOrder) }}
                                        </div>

                                        @if ($expiresAt)
                                            <div>
                                                Hạn dùng:
                                                {{ \Illuminate\Support\Carbon::parse($expiresAt)->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>

    <style>
        .voucher-card {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: .75rem;
            background: #fff;
        }

        .voucher-card__code {
            overflow-wrap: anywhere;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .voucher-card__discount {
            color: #dc3545;
            font-weight: 700;
        }
    </style>
@endsection
