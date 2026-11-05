<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $visibleProducts = Product::query()
            ->where('status', 'active')
            ->whereHas('seller', fn ($query) => $query->availableForStore());

        if ($request->boolean('refresh')) {
            $snapshot = (clone $visibleProducts)
                ->latest('created_at')
                ->latest('product_id')
                ->paginate(12);

            return response()->json([
                'product_ids' => $snapshot->getCollection()
                    ->pluck('product_id')
                    ->map(fn ($id): int => (int) $id)
                    ->values(),
                'total' => $snapshot->total(),
            ]);
        }

        $products = (clone $visibleProducts)
            ->with(['category', 'seller'])
            ->latest('created_at')
            ->latest('product_id')
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
            ->join('accounts', 'sellers.account_id', '=', 'accounts.account_id')
            ->where('products.status', 'active')
            ->where('sellers.status', 'active')
            ->where('accounts.status', 'active')
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
