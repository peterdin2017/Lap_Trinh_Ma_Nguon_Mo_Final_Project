<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Product\CatalogRequest;
use App\Models\Category;
use App\Services\Store\ProductCatalog;
use Illuminate\View\View;

/** Trang danh sách: tìm kiếm, lọc theo danh mục / giá, sắp xếp, phân trang. */
class CatalogController extends Controller
{
    private const PER_PAGE = 12;

    public function index(CatalogRequest $request, ProductCatalog $catalog): View
    {
        $filters = $request->filters();

        $products = $catalog->search($filters)
            ->paginate(self::PER_PAGE)
            ->withQueryString(); // giữ nguyên tiêu chí lọc khi sang trang khác

        return view('store.products.index', [
            'products' => $products,
            'filters' => $filters,
            'categoryTree' => $catalog->categoryTree(),
            'currentCategory' => $filters['category'] ? Category::find($filters['category']) : null,
        ]);
    }
}
