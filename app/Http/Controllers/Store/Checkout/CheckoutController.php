<?php

namespace App\Http\Controllers\Store\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Checkout\CheckoutRequest;
use App\Http\Requests\Store\Checkout\CheckoutSelectionRequest;
use App\Models\Account;
use App\Models\AccountSavedVoucher;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PlatformVoucher;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\SellerVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const SHIPPING_FEE_PER_STORE = 30000;

    public function index(CheckoutSelectionRequest $request): View|RedirectResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $validated = $request->validated();

        $cart = Cart::query()
            ->where('account_id', $accountId)
            ->first();

        if (! $cart) {
            return redirect()->route('store.cart.index')
                ->withErrors(['cart' => 'Giỏ hàng của bạn hiện đang trống.']);
        }

        $cartItemIds = collect($validated['cart_item_ids'])
            ->map(fn ($id) => (int) $id);

        $items = $cart->items()
            ->whereIn('cart_item_id', $cartItemIds)
            ->with(['product.seller', 'product.variants', 'variant'])
            ->get();

        if ($items->count() !== $cartItemIds->count()) {
            return redirect()->route('store.cart.index')
                ->withErrors([
                    'cart' => 'Một số sản phẩm đã thay đổi. Vui lòng chọn lại sản phẩm.',
                ]);
        }

        foreach ($items as $item) {
            if (! $this->isAvailable($item)) {
                return redirect()->route('store.cart.index')
                    ->withErrors([
                        'cart' => 'Có sản phẩm đã hết hàng hoặc ngừng bán. Vui lòng kiểm tra lại giỏ hàng.',
                    ]);
            }
        }

        $cartGroups = $items->groupBy(
            fn ($item) => $item->product->seller_id
        );

        $subtotal = $items->sum(function ($item): float {
            $price = (float) ($item->variant?->price ?? $item->product->price);

            return $price * (int) $item->quantity;
        });

        $shippingFee = $cartGroups->count() * self::SHIPPING_FEE_PER_STORE;

        $voucherCodes = old('voucher_codes', []);
        if (! is_array($voucherCodes)) {
            $voucherCodes = [];
        }

        $platformVoucherCode = trim(
            (string) old('platform_voucher_code', '')
        );

        $voucherDiscounts = $this->previewVoucherDiscounts(
            $cartGroups,
            $voucherCodes
        );

        $sellerDiscountTotal = array_sum($voucherDiscounts);

        $platformVoucherDiscount = $this->previewPlatformVoucherDiscount(
            $platformVoucherCode,
            $accountId,
            (float) $subtotal,
            (float) $sellerDiscountTotal
        );

        $discountTotal = $sellerDiscountTotal + $platformVoucherDiscount;

        $savedSellerVoucherIds = AccountSavedVoucher::query()
            ->where('account_id', $accountId)
            ->where('voucher_type', 'seller')
            ->pluck('voucher_id');

        $savedSellerVouchers = SellerVoucher::query()
            ->whereIn('voucher_id', $savedSellerVoucherIds)
            ->whereIn('seller_id', $cartGroups->keys())
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where(function ($query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->orderBy('expires_at')
            ->get()
            ->groupBy('seller_id');

        $savedPlatformVoucherIds = AccountSavedVoucher::query()
            ->where('account_id', $accountId)
            ->where('voucher_type', 'platform')
            ->pluck('voucher_id');

        $savedPlatformVouchers = PlatformVoucher::query()
            ->whereIn('platform_voucher_id', $savedPlatformVoucherIds)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where(function ($query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->orderBy('expires_at')
            ->get();

        $addresses = Address::query()
            ->where('account_id', $accountId)
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('store.checkout.index', [
            'cartGroups' => $cartGroups,
            'cartItemIds' => $cartItemIds->all(),
            'addresses' => $addresses,
            'defaultAddress' => $addresses->first(),
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'voucherCodes' => $voucherCodes,
            'platformVoucherCode' => $platformVoucherCode,
            'voucherDiscounts' => $voucherDiscounts,
            'platformVoucherDiscount' => $platformVoucherDiscount,
            'savedSellerVouchers' => $savedSellerVouchers,
            'savedPlatformVouchers' => $savedPlatformVouchers,
            'discountTotal' => $discountTotal,
            'totalAmount' => max(
                0,
                $subtotal + $shippingFee - $discountTotal
            ),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $data = $request->validated();

        $cartItemIds = collect($data['cart_item_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $voucherCodes = $data['voucher_codes'] ?? [];
        $platformVoucherCode = trim(
            (string) ($data['platform_voucher_code'] ?? '')
        );

        if (($data['intent'] ?? 'place_order') === 'apply_vouchers') {
            $this->validateVouchersForCheckout(
                $accountId,
                $cartItemIds,
                $voucherCodes,
                $platformVoucherCode
            );

            return back()
                ->withInput($request->safe()->except(['intent']))
                ->with('success', 'Lựa chọn voucher đã được cập nhật.');
        }

        $orderCodes = DB::transaction(function () use (
            $accountId,
            $data,
            $cartItemIds,
            $voucherCodes,
            $platformVoucherCode
        ): array {
            $cart = Cart::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->firstOrFail();

            $cartItems = $cart->items()
                ->whereIn('cart_item_id', $cartItemIds)
                ->lockForUpdate()
                ->get();

            if ($cartItems->count() !== $cartItemIds->count()) {
                throw ValidationException::withMessages([
                    'cart_item_ids' => 'Giỏ hàng đã thay đổi. Vui lòng quay lại chọn sản phẩm.',
                ]);
            }

            $linesBySeller = [];

            foreach ($cartItems as $cartItem) {
                $product = Product::query()
                    ->whereKey($cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'cart_item_ids' => 'Có sản phẩm đã ngừng bán. Vui lòng cập nhật giỏ hàng.',
                    ]);
                }

                $seller = Seller::query()
                    ->where('seller_id', $product->seller_id)
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->first();

                if (! $seller) {
                    throw ValidationException::withMessages([
                        'cart_item_ids' => 'Có cửa hàng không còn hoạt động. Vui lòng cập nhật giỏ hàng.',
                    ]);
                }

                $variant = null;
                $hasVariants = $product->variants()->exists();
                $isSimpleItem = $cartItem->item_key
                    === $product->product_id . ':simple';

                if ($cartItem->variant_id !== null) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $product->product_id)
                        ->whereKey($cartItem->variant_id)
                        ->lockForUpdate()
                        ->first();
                }

                if (($hasVariants && ! $variant) || (! $hasVariants && ! $isSimpleItem)) {
                    throw ValidationException::withMessages([
                        'cart_item_ids' => 'Một mẫu sản phẩm không còn khả dụng. Vui lòng cập nhật giỏ hàng.',
                    ]);
                }

                $stock = (int) (
                    $variant?->stock_quantity ?? $product->stock_quantity
                );
                $quantity = (int) $cartItem->quantity;

                if ($stock < $quantity || $quantity < 1) {
                    throw ValidationException::withMessages([
                        'cart_item_ids' => "Sản phẩm {$product->product_name} không đủ tồn kho. Vui lòng cập nhật giỏ hàng.",
                    ]);
                }

                $linesBySeller[$seller->seller_id][] = [
                    'product' => $product,
                    'seller' => $seller,
                    'variant' => $variant,
                    'price' => (float) ($variant?->price ?? $product->price),
                    'quantity' => $quantity,
                    'seller_voucher_code' => null,
                    'seller_voucher_discount' => 0.0,
                    'platform_voucher_discount' => 0.0,
                ];
            }

            $sellerVoucherModels = [];
            $sellerDiscountTotal = 0.0;

            foreach ($linesBySeller as $sellerId => &$sellerLines) {
                $sellerSubtotal = collect($sellerLines)->sum(
                    fn ($line) => $line['price'] * $line['quantity']
                );

                $voucherCode = trim(
                    (string) ($voucherCodes[$sellerId] ?? '')
                );

                if ($voucherCode === '') {
                    continue;
                }

                $voucherData = $this->resolveSellerVoucher(
                    $voucherCode,
                    (int) $sellerId,
                    (float) $sellerSubtotal,
                    true
                );

                $sellerVoucherModels[$sellerId] = $voucherData['voucher'];

                foreach ($sellerLines as &$line) {
                    $line['seller_voucher_code'] = $voucherData['voucher']->code;
                }
                unset($line);

                $lineDiscounts = $this->allocateDiscount(
                    $sellerLines,
                    (float) $voucherData['discount'],
                    fn ($line) => $line['price'] * $line['quantity']
                );

                foreach ($sellerLines as $index => &$line) {
                    $line['seller_voucher_discount'] = $lineDiscounts[$index];
                }
                unset($line);

                $sellerDiscountTotal += (float) $voucherData['discount'];
            }
            unset($sellerLines);

            $cartSubtotal = 0.0;
            $subtotalAfterSellerVouchers = 0.0;

            foreach ($linesBySeller as $sellerLines) {
                foreach ($sellerLines as $line) {
                    $lineSubtotal = $line['price'] * $line['quantity'];
                    $cartSubtotal += $lineSubtotal;
                    $subtotalAfterSellerVouchers += max(
                        0,
                        $lineSubtotal - $line['seller_voucher_discount']
                    );
                }
            }

            $platformVoucher = null;
            $platformVoucherDiscount = 0.0;

            if ($platformVoucherCode !== '') {
                $platformVoucherData = $this->resolvePlatformVoucher(
                    $platformVoucherCode,
                    $accountId,
                    $cartSubtotal,
                    true
                );

                $platformVoucher = $platformVoucherData['voucher'];
                $platformVoucherDiscount = min(
                    (float) $platformVoucherData['discount'],
                    $subtotalAfterSellerVouchers
                );

                $allLines = [];
                foreach ($linesBySeller as $sellerId => $sellerLines) {
                    foreach ($sellerLines as $lineIndex => $line) {
                        $allLines[] = [
                            'seller_id' => $sellerId,
                            'line_index' => $lineIndex,
                            'line' => $line,
                        ];
                    }
                }

                $platformAllocations = $this->allocateDiscount(
                    array_map(
                        fn ($entry) => $entry['line'],
                        $allLines
                    ),
                    $platformVoucherDiscount,
                    fn ($line) => max(
                        0,
                        ($line['price'] * $line['quantity'])
                            - $line['seller_voucher_discount']
                    )
                );

                foreach ($allLines as $index => $entry) {
                    $linesBySeller[$entry['seller_id']][$entry['line_index']]
                        ['platform_voucher_discount'] = $platformAllocations[$index];
                }
            }

            $shippingAddress = implode(', ', [
                $data['street_address'],
                $data['ward'],
                $data['district'],
                $data['province'],
            ]);

            $orderCodes = [];
            $platformVoucherCodeRecorded = false;

            foreach ($linesBySeller as $sellerId => $sellerLines) {
                $seller = $sellerLines[0]['seller'];

                $orderSubtotal = collect($sellerLines)->sum(
                    fn ($line) => $line['price'] * $line['quantity']
                );

                $sellerDiscount = collect($sellerLines)->sum(
                    fn ($line) => $line['seller_voucher_discount']
                );

                $orderPlatformDiscount = collect($sellerLines)->sum(
                    fn ($line) => $line['platform_voucher_discount']
                );

                $orderDiscount = $sellerDiscount + $orderPlatformDiscount;
                $orderShippingFee = self::SHIPPING_FEE_PER_STORE;

                $localOrderDate = now('Asia/Ho_Chi_Minh')->format('Y-m-d');

                $order = Order::query()->create([
                    'order_code' => 'DH-' . $seller->seller_id . '-'
                        . $localOrderDate . '-'
                        . random_int(1000, 9999),
                    'account_id' => $accountId,
                    'seller_id' => $seller->seller_id,
                    'shipping_name' => $data['shipping_name'],
                    'shipping_phone' => $data['shipping_phone'],
                    'shipping_address' => $shippingAddress,
                    'payment_method' => $data['payment_method'],
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'subtotal' => $orderSubtotal,
                    'shipping_fee' => $orderShippingFee,
                    'discount_amount' => $orderDiscount,
                    'total_amount' => max(
                        0,
                        $orderSubtotal + $orderShippingFee - $orderDiscount
                    ),
                    'note' => $data['note'] ?? null,
                ]);

                foreach ($sellerLines as $line) {
                    $quantity = $line['quantity'];
                    $product = $line['product'];
                    $variant = $line['variant'];
                    $lineTotal = $line['price'] * $quantity;

                    /*
                     * Mã voucher sàn chỉ được ghi vào một dòng hàng trong cả
                     * checkout để xác định chính xác một lượt dùng, dù giỏ
                     * tạo nhiều đơn Shop. Tiền giảm voucher sàn vẫn được lưu
                     * trên từng dòng theo platform_voucher_discount.
                     */
                    $platformCodeForUsage = null;
                    $platformVoucherIdForUsage = null;

                    if (
                        $platformVoucher
                        && ! $platformVoucherCodeRecorded
                    ) {
                        $platformCodeForUsage = $platformVoucher->voucher_code;
                        $platformVoucherIdForUsage = $platformVoucher->platform_voucher_id;
                        $platformVoucherCodeRecorded = true;
                    }

                    $order->items()->create([
                        'product_id' => $product->product_id,
                        'product_variant_id' => $variant?->variant_id,
                        'product_name' => $product->product_name,
                        'variant_name' => $variant?->variant_name,
                        'price' => $line['price'],
                        'quantity' => $quantity,
                        'total_price' => $lineTotal,
                        'seller_voucher_code' => $line['seller_voucher_code'],
                        'seller_voucher_discount' => $line['seller_voucher_discount'],
                        'platform_voucher_code' => $platformCodeForUsage,
                        'platform_voucher_id' => $platformVoucherIdForUsage,
                        'platform_voucher_discount' => $line['platform_voucher_discount'],
                    ]);

                    $variantStockUpdated = $variant
                        ? ProductVariant::query()
                            ->whereKey($variant->variant_id)
                            ->where('stock_quantity', '>=', $quantity)
                            ->decrement('stock_quantity', $quantity)
                        : Product::query()
                            ->whereKey($product->product_id)
                            ->where('stock_quantity', '>=', $quantity)
                            ->decrement('stock_quantity', $quantity);

                    $aggregateStockUpdated = $variant
                        ? Product::query()
                            ->whereKey($product->product_id)
                            ->where('stock_quantity', '>=', $quantity)
                            ->decrement('stock_quantity', $quantity)
                        : 1;

                    if (
                        $variantStockUpdated !== 1
                        || $aggregateStockUpdated !== 1
                    ) {
                        throw ValidationException::withMessages([
                            'cart_item_ids' => 'Tồn kho vừa thay đổi. Vui lòng kiểm tra lại giỏ hàng.',
                        ]);
                    }

                    Product::query()
                        ->whereKey($product->product_id)
                        ->increment('sold_count', $quantity);
                }

                if (isset($sellerVoucherModels[$sellerId])) {
                    $sellerVoucherModels[$sellerId]->increment('used_count');
                }

                $orderCodes[] = $order->order_code;
            }

            if ($platformVoucher) {
                // Một lượt dùng voucher sàn cho toàn bộ checkout.
                $platformVoucher->increment('used_count');
            }

            $cart->items()
                ->whereIn('cart_item_id', $cartItemIds)
                ->delete();

            return $orderCodes;
        }, 3);

        $request->session()->flash('checkout_order_codes', $orderCodes);

        return redirect()->route('store.checkout.success');
    }

    public function success(Request $request): View|RedirectResponse
    {
        $orderCodes = $request->session()->get('checkout_order_codes', []);

        if (count($orderCodes) === 0) {
            return redirect()->route('store.cart.index');
        }

        return view('store.checkout.success', compact('orderCodes'));
    }

    private function validateVouchersForCheckout(
        int $accountId,
        Collection $cartItemIds,
        array $voucherCodes,
        string $platformVoucherCode
    ): void {
        $hasSellerVoucher = collect($voucherCodes)
            ->contains(fn ($code) => trim((string) $code) !== '');

        if (! $hasSellerVoucher && $platformVoucherCode === '') {
            return;
        }

        $cart = Cart::query()
            ->where('account_id', $accountId)
            ->first();

        if (! $cart) {
            throw ValidationException::withMessages([
                'cart_item_ids' => 'Giỏ hàng của bạn hiện đang trống.',
            ]);
        }

        $items = $cart->items()
            ->whereIn('cart_item_id', $cartItemIds)
            ->with(['product.seller', 'product.variants', 'variant'])
            ->get();

        if ($items->count() !== $cartItemIds->count()) {
            throw ValidationException::withMessages([
                'cart_item_ids' => 'Giỏ hàng đã thay đổi. Vui lòng quay lại chọn sản phẩm.',
            ]);
        }

        foreach ($items as $item) {
            if (! $this->isAvailable($item)) {
                throw ValidationException::withMessages([
                    'cart_item_ids' => 'Có sản phẩm đã hết hàng hoặc ngừng bán. Vui lòng kiểm tra lại giỏ hàng.',
                ]);
            }
        }

        $groups = $items->groupBy(
            fn ($item) => $item->product->seller_id
        );

        foreach ($voucherCodes as $sellerId => $code) {
            $code = trim((string) $code);

            if ($code === '') {
                continue;
            }

            $sellerId = (int) $sellerId;
            $group = $groups->get($sellerId);

            if (! $group) {
                throw ValidationException::withMessages([
                    "voucher_codes.{$sellerId}" => 'Shop này không có sản phẩm trong đơn hàng.',
                ]);
            }

            $sellerSubtotal = $group->sum(function ($item): float {
                $price = (float) ($item->variant?->price ?? $item->product->price);

                return $price * (int) $item->quantity;
            });

            $this->resolveSellerVoucher(
                $code,
                $sellerId,
                (float) $sellerSubtotal,
                false
            );
        }

        if ($platformVoucherCode !== '') {
            $cartSubtotal = $items->sum(function ($item): float {
                $price = (float) ($item->variant?->price ?? $item->product->price);

                return $price * (int) $item->quantity;
            });

            $this->resolvePlatformVoucher(
                $platformVoucherCode,
                $accountId,
                (float) $cartSubtotal,
                false
            );
        }
    }

    private function previewVoucherDiscounts(
        Collection $cartGroups,
        array $voucherCodes
    ): array {
        $discounts = [];

        foreach ($voucherCodes as $sellerId => $code) {
            $code = trim((string) $code);
            $sellerId = (int) $sellerId;
            $group = $cartGroups->get($sellerId);

            if ($code === '' || ! $group) {
                continue;
            }

            $sellerSubtotal = $group->sum(function ($item): float {
                $price = (float) ($item->variant?->price ?? $item->product->price);

                return $price * (int) $item->quantity;
            });

            try {
                $voucherData = $this->resolveSellerVoucher(
                    $code,
                    $sellerId,
                    (float) $sellerSubtotal,
                    false
                );

                $discounts[$sellerId] = $voucherData['discount'];
            } catch (ValidationException) {
                // Voucher không hợp lệ sẽ được báo khi khách bấm áp dụng.
            }
        }

        return $discounts;
    }

    private function previewPlatformVoucherDiscount(
        string $code,
        int $accountId,
        float $cartSubtotal,
        float $sellerDiscountTotal
    ): float {
        if ($code === '') {
            return 0.0;
        }

        try {
            $voucherData = $this->resolvePlatformVoucher(
                $code,
                $accountId,
                $cartSubtotal,
                false
            );

            return min(
                (float) $voucherData['discount'],
                max(0, $cartSubtotal - $sellerDiscountTotal)
            );
        } catch (ValidationException) {
            return 0.0;
        }
    }

    private function resolveSellerVoucher(
        string $code,
        int $sellerId,
        float $sellerSubtotal,
        bool $lockForUpdate
    ): array {
        $query = SellerVoucher::query()
            ->where('seller_id', $sellerId)
            ->whereRaw('LOWER(code) = ?', [strtolower($code)]);

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        $voucher = $query->first();

        if (! $voucher) {
            throw ValidationException::withMessages([
                "voucher_codes.{$sellerId}" => 'Mã voucher không tồn tại ở Shop này.',
            ]);
        }

        if (! $voucher->isAvailable()) {
            throw ValidationException::withMessages([
                "voucher_codes.{$sellerId}" => 'Voucher đã tạm dừng, hết hạn hoặc hết lượt sử dụng.',
            ]);
        }

        if ($sellerSubtotal < (float) $voucher->min_order_amount) {
            throw ValidationException::withMessages([
                "voucher_codes.{$sellerId}" => 'Đơn hàng chưa đạt giá trị tối thiểu của voucher.',
            ]);
        }

        if ($voucher->discount_type === 'percent') {
            $discount = $sellerSubtotal
                * (float) $voucher->discount_value
                / 100;

            if ($voucher->max_discount !== null) {
                $discount = min(
                    $discount,
                    (float) $voucher->max_discount
                );
            }
        } else {
            $discount = (float) $voucher->discount_value;
        }

        return [
            'voucher' => $voucher,
            'discount' => round(min($discount, $sellerSubtotal), 2),
        ];
    }

    private function resolvePlatformVoucher(
        string $code,
        int $accountId,
        float $cartSubtotal,
        bool $lockForUpdate
    ): array {
        $query = PlatformVoucher::query()
            ->whereRaw(
                'LOWER(voucher_code) = ?',
                [strtolower($code)]
            );

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        $voucher = $query->first();

        if (! $voucher) {
            throw ValidationException::withMessages([
                'platform_voucher_code' => 'Mã voucher sàn không tồn tại.',
            ]);
        }

        $now = now();

        $isAvailable = $voucher->status === 'active'
            && $voucher->starts_at
            && $voucher->expires_at
            && $voucher->starts_at->lte($now)
            && $voucher->expires_at->gte($now)
            && (
                $voucher->usage_limit === null
                || (int) $voucher->used_count < (int) $voucher->usage_limit
            );

        if (! $isAvailable) {
            throw ValidationException::withMessages([
                'platform_voucher_code' => 'Voucher sàn chưa có hiệu lực, đã tạm dừng hoặc hết lượt sử dụng.',
            ]);
        }

        if ($cartSubtotal < (float) $voucher->min_order_amount) {
            throw ValidationException::withMessages([
                'platform_voucher_code' => 'Tổng giá trị giỏ hàng chưa đạt mức tối thiểu của voucher sàn.',
            ]);
        }

        $usedByAccountQuery = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->where('orders.account_id', $accountId)
            ->where('order_items.platform_voucher_id', $voucher->platform_voucher_id);

        if ($lockForUpdate) {
            // A locking read sees uses committed while this transaction waited
            // for the voucher row lock above, even under repeatable-read isolation.
            $usedByAccountQuery->lockForUpdate();
        }

        $usedByAccount = $usedByAccountQuery
            ->get(['order_items.order_item_id'])
            ->count();

        if (
            $voucher->per_user_limit !== null
            && $usedByAccount >= (int) $voucher->per_user_limit
        ) {
            throw ValidationException::withMessages([
                'platform_voucher_code' => 'Bạn đã sử dụng hết số lượt của voucher sàn này.',
            ]);
        }

        if ($voucher->discount_type === 'percentage') {
            $discount = $cartSubtotal
                * (float) $voucher->discount_value
                / 100;

            if ($voucher->max_discount_amount !== null) {
                $discount = min(
                    $discount,
                    (float) $voucher->max_discount_amount
                );
            }
        } else {
            $discount = (float) $voucher->discount_value;
        }

        return [
            'voucher' => $voucher,
            'discount' => round(min($discount, $cartSubtotal), 2),
        ];
    }

    /**
     * Chia discount theo tỷ trọng giá trị từng dòng, đảm bảo tổng phân bổ
     * đúng bằng số tiền giảm (đến 2 chữ số thập phân).
     */
    private function allocateDiscount(
        array $lines,
        float $discount,
        callable $getBaseAmount
    ): array {
        if ($lines === [] || $discount <= 0) {
            return array_fill(0, count($lines), 0.0);
        }

        $bases = [];
        $baseTotal = 0.0;

        foreach ($lines as $index => $line) {
            $base = max(0, (float) $getBaseAmount($line));
            $bases[$index] = $base;
            $baseTotal += $base;
        }

        if ($baseTotal <= 0) {
            return array_fill(0, count($lines), 0.0);
        }

        $discountCents = (int) round($discount * 100);
        $allocatedCents = 0;
        $allocations = [];
        $lastIndex = array_key_last($lines);

        foreach ($lines as $index => $_line) {
            if ($index === $lastIndex) {
                $lineCents = $discountCents - $allocatedCents;
            } else {
                $lineCents = (int) round(
                    $discountCents * ($bases[$index] / $baseTotal)
                );
                $lineCents = min(
                    $lineCents,
                    (int) round($bases[$index] * 100)
                );
                $allocatedCents += $lineCents;
            }

            $allocations[$index] = $lineCents / 100;
        }

        return $allocations;
    }

    private function accountId(Request $request): ?int
    {
        $accountId = $request->session()->get('account_id');

        if (! is_numeric($accountId)) {
            return null;
        }

        $accountId = Account::query()
            ->whereKey((int) $accountId)
            ->where('role', 'user')
            ->where('status', 'active')
            ->value('account_id');

        return $accountId === null ? null : (int) $accountId;
    }

    private function isAvailable(CartItem $item): bool
    {
        $product = $item->product;

        if (
            ! $product
            || $product->status !== 'active'
            || $product->seller?->status !== 'active'
        ) {
            return false;
        }

        $hasVariants = $product->variants->isNotEmpty();
        $isSimpleItem = $item->item_key
            === $product->product_id . ':simple';

        if (($hasVariants && ! $item->variant) || (! $hasVariants && ! $isSimpleItem)) {
            return false;
        }

        return (int) ($item->variant?->stock_quantity ?? $product->stock_quantity)
            >= (int) $item->quantity;
    }
}
