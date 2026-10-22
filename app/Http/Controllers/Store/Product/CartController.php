<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // Hiển thị giỏ hàng của tài khoản đang đăng nhập.
    public function index(Request $request): View|RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        // Nếu chưa đăng nhập thì chuyển đến trang đăng nhập.
        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        // Tìm giỏ hàng của tài khoản; tạo giỏ mới nếu chưa có.
        $cart = Cart::query()
            ->firstOrCreate(['account_id' => $accountId]);

        // Lấy các mặt hàng cùng thông tin sản phẩm và mẫu mã.
        $items = $cart->items()
            ->with(['product', 'variant'])
            ->get();

        // Chuẩn bị dữ liệu theo cấu trúc mà view giỏ hàng sử dụng.
        $cartItems = [];

        foreach ($items as $item) {
            $product = $item->product;

            // Bỏ qua mặt hàng nếu sản phẩm đã bị xóa.
            if (! $product) {
                continue;
            }

            $cartItems[$item->item_key] = [
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

    // Thêm sản phẩm hoặc mẫu đã chọn vào giỏ hàng.
    public function store(Request $request): RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        // Yêu cầu đăng nhập trước khi thêm vào giỏ.
        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        // Kiểm tra dữ liệu nhận từ form.
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

        // Chỉ cho phép thêm sản phẩm đang bán từ shop hoạt động.
        $product = Product::query()
            ->with('variants')
            ->where('status', 'active')
            ->whereHas('seller', fn ($query) => $query->where('status', 'active'))
            ->findOrFail($data['product_id']);

        $variant = null;

        // Nếu sản phẩm có mẫu thì bắt buộc chọn một mẫu hợp lệ.
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
            // Không nhận mẫu nếu sản phẩm không có mẫu.
            return back()
                ->withErrors(['variant_id' => 'Sản phẩm này không có mẫu mã.'])
                ->withInput();
        }

        // Lấy tồn kho của mẫu; nếu không có mẫu thì dùng tồn kho sản phẩm.
        $stock = (int) ($variant?->stock_quantity ?? $product->stock_quantity);
        $quantity = (int) $data['quantity'];

        if ($stock < 1) {
            return back()->withErrors([
                'quantity' => 'Mẫu sản phẩm này hiện đã hết hàng.',
            ]);
        }

        // Tạo khóa riêng cho sản phẩm và mẫu để mỗi mẫu là một dòng giỏ riêng.
        $variantId = $variant?->variant_id;
        $itemKey = $product->product_id . ':' . ($variantId ?? 'simple');

        // Tìm hoặc tạo giỏ hàng của tài khoản.
        $cart = Cart::query()->firstOrCreate([
            'account_id' => $accountId,
        ]);

        // Tính số lượng mới, cộng với số lượng đã có trong giỏ.
        $cartItem = $cart->items()
            ->where('item_key', $itemKey)
            ->first();

        $newQuantity = ($cartItem?->quantity ?? 0) + $quantity;

        // Không cho thêm vượt quá tồn kho.
        if ($newQuantity > $stock) {
            return back()
                ->withErrors([
                    'quantity' => "Chỉ còn {$stock} sản phẩm trong kho.",
                ])
                ->withInput();
        }

        // Cập nhật dòng đã có hoặc tạo dòng mới trong giỏ.
        $cart->items()->updateOrCreate(
            ['item_key' => $itemKey],
            [
                'product_id' => $product->product_id,
                'variant_id' => $variantId,
                'quantity' => $newQuantity,
            ]
        );

        // Quay lại trang sản phẩm và gửi thông báo thành công.
        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    // Xóa một mặt hàng khỏi giỏ của tài khoản đang đăng nhập.
    public function destroy(Request $request, string $key): RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        if (! $accountId) {
            return redirect()->guest(route('login.form'));
        }

        // Tìm giỏ thuộc đúng tài khoản để không xóa giỏ người khác.
        $cart = Cart::query()
            ->where('account_id', $accountId)
            ->first();

        if ($cart) {
            $cart->items()
                ->where('item_key', $key)
                ->delete();
        }

        // Sau khi xóa, quay về trang giỏ hàng.
        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
