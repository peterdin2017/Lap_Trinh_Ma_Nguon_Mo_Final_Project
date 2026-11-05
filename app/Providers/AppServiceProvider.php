<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('seller.layouts.sidebar', function ($view): void {
            $seller = request()->user()?->seller;
            $pendingOrdersCount = $seller
                ? Order::query()
                ->where('seller_id', $seller->seller_id)
                ->where('order_status', 'pending')
                ->count()
                : 0;

            $view->with('pendingOrdersCount', (int) $pendingOrdersCount);
        });

        View::composer('store.layouts.header', function ($view): void {
            $accountId = session('account_id');
            $accountRole = session('account_role');
            $cartQuantity = 0;
            $storeProfile = null;

            if ($accountRole === 'user' && is_numeric($accountId)) {
                $cart = Cart::query()
                    ->where('account_id', (int) $accountId)
                    ->withSum('items', 'quantity')
                    ->first();

                $cartQuantity = (int) ($cart?->items_sum_quantity ?? 0);

                $storeProfile = User::query()
                    ->where('account_id', (int) $accountId)
                    ->first(['full_name', 'avatar']);
            }

            $avatarPath = $storeProfile?->avatar ?: session('avatar');
            $avatarUrl = null;

            if ($avatarPath) {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
                $publicDisk = Storage::disk('public');

                $avatarUrl = filter_var($avatarPath, FILTER_VALIDATE_URL)
                    ? $avatarPath
                    : $publicDisk->url(ltrim($avatarPath, '/'));
            }

            $profileName = $storeProfile?->full_name
                ?: session('user_name')
                ?: 'Tài khoản';

            $view->with([
                'navCategories' => Category::query()
                    ->whereNull('parent_id')
                    ->orderBy('category_name')
                    ->limit(8)
                    // Select existing columns only; older databases may not have slug yet.
                    ->get(),
                'accountId' => $accountId,
                'accountRole' => $accountRole,
                'profileName' => $profileName,
                'avatarUrl' => $avatarUrl,
                'storeCartQuantity' => $cartQuantity,
            ]);
        });
    }
}
