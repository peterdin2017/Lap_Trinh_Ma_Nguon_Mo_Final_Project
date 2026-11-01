<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\ProductModerationRequest;
use App\Models\Category;
use App\Models\Product;

class ProductModerationController extends Controller
{
    public function index(ProductModerationRequest $request)
    {
        $filters = $request->validated();

        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        if ($from && $to && $from > $to) {
            [$from, $to] = [$to, $from];
        }

        $products = Product::query()
            ->with(['seller', 'category'])
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where('product_name', 'like', '%' . $keyword . '%');
            })
            ->when($filters['status'] ?? null, function ($query, string $status): void {
                $query->where('status', $status);
            })
            ->when($filters['category'] ?? null, function ($query, int $categoryId): void {
                $query->where('category_id', $categoryId);
            })
            ->when($from, fn ($query) => $query->whereDate('published_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('published_at', '<=', $to))
            ->orderByDesc('product_id')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->orderBy('category_name')->get([
            'category_id',
            'category_name',
        ]);

        return view('admin.products.index', compact(
            'products',
            'categories',
            'from',
            'to'
        ));
    }
    public function lock(ProductModerationRequest $request, Product $product)
    {
        $data = $request->validated();
        if ($product->status === 'locked') return back()->with('error', 'Sản phẩm đã bị khóa.');
        $product->forceFill(['status' => 'locked', 'locked_reason' => $data['locked_reason'], 'locked_at' => now()])->save();
        return back()->with('success', 'Đã khóa sản phẩm.');
    }
    public function unlock(Product $product)
    {
        if ($product->status !== 'locked') return back()->with('error', 'Sản phẩm không ở trạng thái khóa.');
        // Không có cột trạng thái trước khi khóa: mở về hidden để seller chủ động đăng bán.
        $product->forceFill(['status' => 'hidden', 'locked_reason' => null, 'locked_at' => null])->save();
        return back()->with('success', 'Đã mở khóa; sản phẩm chuyển sang ẩn để seller tự mở bán.');
    }
}
