<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'seller'])
            ->where('status', 'active')
            ->whereHas('seller', function ($query) {
                $query->where('status', 'active');
            })
            ->latest()
            ->paginate(12);

        $categories = Category::query()
            ->orderBy('category_name')
            // Load the current table columns so this page also works before slug migration runs.
            ->get();

        $childrenByParent = [];

        foreach ($categories as $category) {
            if ($category->parent_id !== null) {
                $parentId = (int) $category->parent_id;
                $childrenByParent[$parentId][] = (int) $category->category_id;
            }
        }

        $directProductCounts = Product::query()
            ->join('sellers', 'products.seller_id', '=', 'sellers.seller_id')
            ->where('products.status', 'active')
            ->where('sellers.status', 'active')
            ->whereNotNull('products.category_id')
            ->selectRaw('products.category_id, COUNT(*) as total')
            ->groupBy('products.category_id')
            ->pluck('total', 'products.category_id');

        foreach ($categories as $category) {
            $pendingIds = [(int) $category->category_id];
            $visitedIds = [];
            $total = 0;

            while ($pendingIds !== []) {
                $currentId = array_pop($pendingIds);

                if (isset($visitedIds[$currentId])) {
                    continue;
                }

                $visitedIds[$currentId] = true;
                $total += (int) ($directProductCounts[$currentId] ?? 0);

                foreach ($childrenByParent[$currentId] ?? [] as $childId) {
                    $pendingIds[] = $childId;
                }
            }

            $category->setAttribute('products_count', $total);
        }

        $categories = $categories
            ->sortByDesc('products_count')
            ->values();

        return view('store.home', compact('products', 'categories'));
    }
}
