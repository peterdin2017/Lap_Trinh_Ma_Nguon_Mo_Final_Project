<?php

namespace App\Http\Controllers\Store\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Checkout\CheckoutRequest;
use App\Models\Account;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const SHIPPING_FEE_PER_STORE = 30000;

    public function index(Request $request): View|RedirectResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $validated = $request->validate([
            'cart_item_ids' => ['required', 'array', 'min:1', 'max:100'],
            'cart_item_ids.*' => ['required', 'integer', 'min:1', 'distinct'],
        ]);

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

            return $price * $item->quantity;
        });

        $shippingFee = $cartGroups->count() * self::SHIPPING_FEE_PER_STORE;

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
            'totalAmount' => $subtotal + $shippingFee,
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

        $orderCodes = DB::transaction(function () use (
            $accountId,
            $data,
            $cartItemIds
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
                $isSimpleItem = $cartItem->item_key === $product->product_id . ':simple';

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

                $stock = (int) ($variant?->stock_quantity ?? $product->stock_quantity);
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
                ];
            }

            $shippingAddress = implode(', ', [
                $data['street_address'],
                $data['ward'],
                $data['district'],
                $data['province'],
            ]);

            $orderCodes = [];

            foreach ($linesBySeller as $sellerLines) {
                $seller = $sellerLines[0]['seller'];

                $orderSubtotal = collect($sellerLines)->sum(
                    fn ($line) => $line['price'] * $line['quantity']
                );

                $orderShippingFee = self::SHIPPING_FEE_PER_STORE;

                // Dùng ngày theo múi giờ Việt Nam cho mã đơn hàng.
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
                    'discount_amount' => 0,
                    'total_amount' => $orderSubtotal + $orderShippingFee,
                    'note' => $data['note'] ?? null,
                ]);

                foreach ($sellerLines as $line) {
                    $quantity = $line['quantity'];
                    $product = $line['product'];
                    $variant = $line['variant'];
                    $lineTotal = $line['price'] * $quantity;

                    $order->items()->create([
                        'product_id' => $product->product_id,
                        'product_variant_id' => $variant?->variant_id,
                        'product_name' => $product->product_name,
                        'variant_name' => $variant?->variant_name,
                        'price' => $line['price'],
                        'quantity' => $quantity,
                        'total_price' => $lineTotal,
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

                    if ($variantStockUpdated !== 1 || $aggregateStockUpdated !== 1) {
                        throw ValidationException::withMessages([
                            'cart_item_ids' => 'Tồn kho vừa thay đổi. Vui lòng kiểm tra lại giỏ hàng.',
                        ]);
                    }

                    // Cộng số lượng đã bán sau khi tạo chi tiết đơn thành công.
                    Product::query()
                        ->whereKey($product->product_id)
                        ->increment('sold_count', $quantity);
                }

                $orderCodes[] = $order->order_code;
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

    private function accountId(Request $request): ?int
    {
        $accountId = $request->session()->get('account_id');

        if (! is_numeric($accountId)) {
            return null;
        }

        $accountId = Account::query()
            ->whereKey((int) $accountId)
            ->whereIn('role', ['user', 'seller'])
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
        $isSimpleItem = $item->item_key === $product->product_id . ':simple';

        if (($hasVariants && ! $item->variant) || (! $hasVariants && ! $isSimpleItem)) {
            return false;
        }

        return (int) ($item->variant?->stock_quantity ?? $product->stock_quantity)
            >= (int) $item->quantity;
    }
}

