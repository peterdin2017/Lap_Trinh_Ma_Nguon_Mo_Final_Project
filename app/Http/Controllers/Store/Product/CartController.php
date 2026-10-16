<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $cart = Cart::query()
            ->firstOrCreate(['account_id' => $accountId]);

        $items = $cart->items()
            ->with(['product', 'variant'])
            ->get();

        // Chuyển dữ liệu database về cấu trúc mà view giỏ hàng đang dùng.
        $cartItems = [];

        foreach ($items as $item) {
            $product = $item->product;

            if (! $product) {
                continue;
            }

            $key = $item->item_key;

            $cartItems[$key] = [
                'product_id' => $product->product_id,
                'variant_id' => $item->variant_id,
                'product_name' => $product->product_name,
                'variant_name' => $item->variant?->variant_name,
                'image_url' => $item->variant?->image_url ?: $product->image_url,
                'price' => $item->variant?->price ?? $product->price,
                'quantity' => $item->quantity,
                'stock_quantity' => $item->variant?->stock_quantity
                    ?? $product->stock_quantity,
            ];
        }

        return view('store.cart.index', ['cart' => $cartItems]);
    }

    public function store(Request $request): RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ], [
            'product_id.required' => 'Không tìm thấy sản phẩm.',
            'quantity.required' => 'Vui lòng chọn số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);

        $product = Product::query()
            ->with('variants')
            ->where('status', 'active')
            ->whereHas('seller', fn ($query) => $query->where('status', 'active'))
            ->findOrFail($data['product_id']);

        $variant = null;

        if ($product->variants->isNotEmpty()) {
            if (empty($data['variant_id'])) {
                return back()
                    ->withErrors(['variant_id' => 'Vui lòng chọn mẫu mã sản phẩm.'])
                    ->withInput();
            }

            $variant = $product->variants->firstWhere(
                'variant_id',
                (int) $data['variant_id']
            );

            if (! $variant) {
                return back()
                    ->withErrors(['variant_id' => 'Mẫu mã đã chọn không hợp lệ.'])
                    ->withInput();
            }
        } elseif (! empty($data['variant_id'])) {
            return back()
                ->withErrors(['variant_id' => 'Sản phẩm này không có mẫu mã.'])
                ->withInput();
        }

        $stock = (int) ($variant?->stock_quantity ?? $product->stock_quantity);
        $quantity = (int) $data['quantity'];

        if ($stock < 1) {
            return back()->withErrors([
                'quantity' => 'Mẫu sản phẩm này hiện đã hết hàng.',
            ]);
        }

        $variantId = $variant?->variant_id;
        $itemKey = $product->product_id . ':' . ($variantId ?? 'simple');

        $cart = Cart::query()->firstOrCreate([
            'account_id' => $accountId,
        ]);

        $cartItem = $cart->items()
            ->where('item_key', $itemKey)
            ->first();

        $newQuantity = ($cartItem?->quantity ?? 0) + $quantity;

        if ($newQuantity > $stock) {
            return back()
                ->withErrors([
                    'quantity' => "Chỉ còn {$stock} sản phẩm trong kho.",
                ])
                ->withInput();
        }

        $cart->items()->updateOrCreate(
            ['item_key' => $itemKey],
            [
                'product_id' => $product->product_id,
                'variant_id' => $variantId,
                'quantity' => $newQuantity,
            ]
        );

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function destroy(Request $request, string $key): RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        $cart = Cart::query()
            ->where('account_id', $accountId)
            ->first();

        if ($cart) {
            $cart->items()
                ->where('item_key', $key)
                ->delete();
        }

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
