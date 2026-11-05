<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Product\CartAddRequest;
use App\Http\Requests\Store\Product\CartBulkDeleteRequest;
use App\Http\Requests\Store\Product\CartQuantityRequest;
use App\Models\Account;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $cart = Cart::query()->firstOrCreate([
            'account_id' => $accountId,
        ]);

        $items = $cart->items()
            ->with(['product.seller.account', 'product.variants', 'variant'])
            ->get();

        $cartItems = [];

        foreach ($items as $item) {
            $product = $item->product;

            if (! $product) {
                continue;
            }

            $isSimpleItem = $item->item_key === $product->product_id . ':simple';

            $hasInvalidVariant = (
                $item->variant_id === null && ! $isSimpleItem
            ) || (
                $isSimpleItem && $product->variants->isNotEmpty()
            );

            $stock = $hasInvalidVariant
                ? 0
                : (int) ($item->variant?->stock_quantity ?? $product->stock_quantity);

            $isAvailable = ! $hasInvalidVariant
                && $product->status === 'active'
                && $product->seller?->status === 'active'
                && $product->seller?->account?->status === 'active'
                && $stock > 0;

            $cartItems[$item->item_key] = [
                'cart_item_id' => (int) $item->getKey(),
                'seller_id' => $product->seller?->seller_id ?? 0,
                'shop_name' => $product->seller?->shop_name ?? 'Cửa hàng',
                'shop_slug' => $product->seller?->slug,
                'product_id' => $product->product_id,
                'product_slug' => $product->slug,
                'variant_id' => $item->variant_id,
                'product_name' => $product->product_name,
                'variant_name' => $hasInvalidVariant
                    ? 'Mẫu này không còn khả dụng'
                    : $item->variant?->variant_name,
                'image_url' => $item->variant?->image_url ?: $product->image_url,
                'price' => $hasInvalidVariant
                    ? 0
                    : ($item->variant?->price ?? $product->price),
                'quantity' => $item->quantity,
                'stock_quantity' => $stock,
                'available' => $isAvailable,
            ];
        }

        // Preserve item_key array keys (e.g. "11:simple") for item update/delete URLs.
        $cartGroups = collect($cartItems)->groupBy('seller_id', true);

        return view('store.cart.index', compact('cartGroups'));
    }

    public function store(CartAddRequest $request): JsonResponse|RedirectResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.',
                    'login_url' => route('login.form'),
                ], 401);
            }

            return redirect()->guest(route('login.form'));
        }

        $data = $request->validated();

        $cartItemId = DB::transaction(function () use ($accountId, $data): int {
            $product = Product::query()
                ->where('status', 'active')
                ->whereHas('seller', fn ($query) => $query->availableForStore())
                ->lockForUpdate()
                ->findOrFail($data['product_id']);

            $hasVariants = $product->variants()->exists();
            $variant = null;
            $requestedVariantId = $data['variant_id'] ?? null;

            if ($hasVariants && ! $requestedVariantId) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Vui lòng chọn mẫu mã sản phẩm.',
                ]);
            }

            if ($requestedVariantId) {
                $variant = $product->variants()
                    ->whereKey((int) $requestedVariantId)
                    ->lockForUpdate()
                    ->first();

                if (! $variant) {
                    throw ValidationException::withMessages([
                        'variant_id' => 'Mẫu mã đã chọn không hợp lệ.',
                    ]);
                }
            }

            $stock = (int) ($variant?->stock_quantity ?? $product->stock_quantity);
            $quantity = (int) $data['quantity'];

            if ($stock < 1) {
                throw ValidationException::withMessages([
                    'quantity' => 'Mẫu sản phẩm này hiện đã hết hàng.',
                ]);
            }

            $itemKey = $product->product_id . ':'
                . ($variant?->variant_id ?? 'simple');

            $cart = Cart::query()->firstOrCreate([
                'account_id' => $accountId,
            ]);

            $cart = Cart::query()
                ->where('cart_id', $cart->cart_id)
                ->lockForUpdate()
                ->firstOrFail();

            $cartItem = $cart->items()
                ->where('item_key', $itemKey)
                ->lockForUpdate()
                ->first();

            $newQuantity = (int) ($cartItem?->quantity ?? 0) + $quantity;

            if ($newQuantity > $stock) {
                throw ValidationException::withMessages([
                    'quantity' => "Chỉ còn {$stock} sản phẩm trong kho.",
                ]);
            }

            $cartItem = $cart->items()->updateOrCreate(
                ['item_key' => $itemKey],
                [
                    'product_id' => $product->product_id,
                    'variant_id' => $variant?->variant_id,
                    'quantity' => $newQuantity,
                ]
            );

            return (int) $cartItem->getKey();
        });

        if (($data['intent'] ?? 'cart') === 'buy_now') {
            return redirect()
                ->route('store.cart.index', ['buy_now' => $cartItemId])
                ->with('success', 'Đã chuẩn bị sản phẩm để mua. Kiểm tra giỏ hàng trước khi tiếp tục.');
        }

        if ($request->expectsJson()) {
            $cart = Cart::query()
                ->where('account_id', $accountId)
                ->withCount('items')
                ->withSum('items', 'quantity')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                'cart_quantity' => (int) ($cart?->items_sum_quantity ?? 0),
                'cart_item_count' => (int) ($cart?->items_count ?? 0),
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function destroy(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vui lòng đăng nhập lại.',
                ], 401);
            }

            return redirect()->guest(route('login.form'));
        }

        $deletedCount = DB::transaction(function () use ($accountId, $key): int {
            $cart = Cart::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->first();

            if (! $cart) {
                return 0;
            }

            return $cart->items()
                ->where('item_key', $key)
                ->delete();
        }, 3);

        if ($request->expectsJson()) {
            if ($deletedCount === 0) {
                return response()->json([
                    'message' => 'Sản phẩm không còn trong giỏ hàng.',
                ], 404);
            }

            $cart = Cart::query()
                ->where('account_id', $accountId)
                ->withSum('items', 'quantity')
                ->first();

            return response()->json([
                'success' => true,
                'deleted_count' => $deletedCount,
                'cart_quantity' => (int) ($cart?->items_sum_quantity ?? 0),
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            ]);
        }

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function updateQuantity(CartQuantityRequest $request, string $key): JsonResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            return response()->json([
                'message' => 'Vui lòng đăng nhập lại.',
            ], 401);
        }

        $data = $request->validated();

        $item = DB::transaction(function () use ($accountId, $data, $key): array {
            $cart = Cart::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->firstOrFail();

            $cartItem = $cart->items()
                ->where('item_key', $key)
                ->lockForUpdate()
                ->firstOrFail();

            $product = Product::query()
                ->where('status', 'active')
                ->whereHas('seller', fn ($query) => $query->availableForStore())
                ->lockForUpdate()
                ->findOrFail($cartItem->product_id);

            $variant = null;

            if ($cartItem->variant_id !== null) {
                $variant = $product->variants()
                    ->whereKey($cartItem->variant_id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            $expectedKey = $product->product_id . ':'
                . ($variant?->variant_id ?? 'simple');

            if ($expectedKey !== $cartItem->item_key) {
                throw ValidationException::withMessages([
                    'quantity' => 'Mẫu sản phẩm này không còn khả dụng. Hãy xóa khỏi giỏ hàng.',
                ]);
            }

            $stock = (int) ($variant?->stock_quantity ?? $product->stock_quantity);
            $quantity = (int) $data['quantity'];

            if ($stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Chỉ còn {$stock} sản phẩm trong kho.",
                ]);
            }

            $cartItem->update(['quantity' => $quantity]);
            $unitPrice = (float) ($variant?->price ?? $product->price);

            return [
                'quantity' => $quantity,
                'stock_quantity' => $stock,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $quantity,
            ];
        }, 3);

        $cartQuantity = Cart::query()
            ->where('account_id', $accountId)
            ->first()
            ?->items()
            ->sum('quantity') ?? 0;

        return response()->json([
            'success' => true,
            'item' => $item,
            'cart_quantity' => (int) $cartQuantity,
        ]);
    }

    public function destroySelected(CartBulkDeleteRequest $request): JsonResponse
    {
        $accountId = $this->accountId($request);

        if (! $accountId) {
            return response()->json([
                'message' => 'Vui lòng đăng nhập lại.',
            ], 401);
        }

        $data = $request->validated();

        $deletedCount = DB::transaction(function () use ($accountId, $data): int {
            $cart = Cart::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->firstOrFail();

            return $cart->items()
                ->whereIn('item_key', array_unique($data['keys']))
                ->delete();
        }, 3);

        $cart = Cart::query()
            ->where('account_id', $accountId)
            ->withSum('items', 'quantity')
            ->first();

        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'cart_quantity' => (int) ($cart?->items_sum_quantity ?? 0),
            'message' => 'Đã xóa các sản phẩm đã chọn khỏi giỏ hàng.',
        ]);
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
}
