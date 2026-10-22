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
            ->withCount('products')
            ->orderByDesc('products_count')
            ->get();

        return view('store.home', compact('products', 'categories'));
    }
}
