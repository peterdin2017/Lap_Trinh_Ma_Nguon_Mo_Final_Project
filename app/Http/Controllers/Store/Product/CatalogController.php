<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Product\CatalogRequest;
use App\Services\Store\ProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Trang danh sách: tìm kiếm, lọc theo danh mục / giá, sắp xếp, phân trang. */
class CatalogController extends Controller
{
    private const PER_PAGE = 12;

    public function index(
        CatalogRequest $request,
        ProductCatalog $catalog
    ): View|RedirectResponse|JsonResponse
    {
        $filters = $request->filters();
        $currentCategory = $filters['category']
            ? $catalog->findCategory($filters['category'])
            : null;

        if ($currentCategory
            && filled($currentCategory->slug)
            && ctype_digit((string) $filters['category'])
            && (string) $filters['category'] !== (string) $currentCategory->slug) {
            $query = $request->query();
            $query['category'] = $currentCategory->slug;

            return redirect()->route('store.products.index', $query, 301);
        }

        if ($request->expectsJson()) {
            $suggestions = $catalog->search($filters)
                ->limit(6)
                ->get();

            return response()->json([
                'products' => $suggestions->map(fn ($product): array => [
                    'id' => $product->product_id,
                    'name' => $product->product_name,
                    'brand' => $product->brand,
                    'price' => (float) $product->price,
                    'image_url' => $product->image_src,
                    'shop_name' => $product->seller?->shop_name,
                    'category_name' => $product->category?->category_name,
                    'url' => route('store.products.show', [
                        'slug' => $product->slug ?: $product->product_id,
                    ]),
                ])->values(),
            ]);
        }

        $products = $catalog->search($filters)
            ->paginate(self::PER_PAGE)
            ->withQueryString(); // giữ nguyên tiêu chí lọc khi sang trang khác

        return view('store.products.index', [
            'products' => $products,
            'filters' => $filters,
            'categoryTree' => $catalog->categoryTree(),
            'currentCategory' => $currentCategory,
        ]);
    }
}
