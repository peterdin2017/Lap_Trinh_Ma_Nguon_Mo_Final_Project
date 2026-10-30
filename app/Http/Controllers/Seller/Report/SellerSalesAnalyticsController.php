<?php

namespace App\Http\Controllers\Seller\Report;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SellerSalesAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $request->user()?->seller;

        abort_unless(
            $seller instanceof Seller,
            403,
            'Tài khoản hiện tại chưa liên kết với cửa hàng Seller.'
        );

        $filters = $request->validate([
            'date_from' => ['nullable', 'date', 'before_or_equal:today'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $timezone = config('app.timezone', 'UTC');
        $today = CarbonImmutable::now($timezone)->startOfDay();

        $dateTo = isset($filters['date_to'])
            ? CarbonImmutable::parse($filters['date_to'], $timezone)->endOfDay()
            : $today->endOfDay();

        $dateFrom = isset($filters['date_from'])
            ? CarbonImmutable::parse($filters['date_from'], $timezone)->startOfDay()
            : (isset($filters['date_to'])
                ? CarbonImmutable::parse($filters['date_to'], $timezone)->startOfMonth()
                : $today->startOfMonth());

        $periodDays = $dateFrom->startOfDay()->diffInDays($dateTo->startOfDay()) + 1;

        if ($periodDays > 366) {
            throw ValidationException::withMessages([
                'date_to' => 'Khoảng thời gian tối đa là 366 ngày.',
            ]);
        }

        $previousFrom = $dateFrom->subDays($periodDays)->startOfDay();
        $previousTo = $dateFrom->subDay()->endOfDay();

        $aggregateProducts = function (CarbonImmutable $from, CarbonImmutable $to) use ($seller) {
            return DB::table('order_items')
                ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
                ->where('orders.seller_id', $seller->seller_id)
                ->where('orders.order_status', 'completed')
                ->whereBetween('orders.created_at', [$from, $to])
                ->select([
                    'order_items.product_id',
                    'order_items.product_name',
                ])
                ->selectRaw('SUM(order_items.quantity) AS units_sold')
                ->selectRaw('SUM(order_items.total_price) AS product_sales')
                ->selectRaw('COUNT(DISTINCT orders.order_id) AS order_count')
                ->groupBy('order_items.product_id', 'order_items.product_name')
                ->orderByDesc('units_sold')
                ->get();
        };

        $currentProducts = $aggregateProducts($dateFrom, $dateTo);
        $previousProducts = $aggregateProducts($previousFrom, $previousTo);

        $previousByProduct = $previousProducts->keyBy(
            fn ($product) => $this->productKey($product)
        );

        $products = $currentProducts
            ->map(function ($product) use ($previousByProduct) {
                $previous = $previousByProduct->get($this->productKey($product));
                $previousUnits = (int) ($previous->units_sold ?? 0);

                $product->previous_units_sold = $previousUnits;
                $product->previous_product_sales = (float) ($previous->product_sales ?? 0);
                $product->change_percent = $previousUnits > 0
                    ? (($product->units_sold - $previousUnits) / $previousUnits) * 100
                    : null;

                return $product;
            })
            ->take(10)
            ->values();

        $unitsSold = (int) $currentProducts->sum('units_sold');
        $previousUnitsSold = (int) $previousProducts->sum('units_sold');

        $unitsChangePercent = $previousUnitsSold > 0
            ? (($unitsSold - $previousUnitsSold) / $previousUnitsSold) * 100
            : null;

        return view('seller.reports.analytics', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'previousFrom' => $previousFrom,
            'previousTo' => $previousTo,
            'products' => $products,
            'unitsSold' => $unitsSold,
            'previousUnitsSold' => $previousUnitsSold,
            'unitsChangePercent' => $unitsChangePercent,
            'productCount' => $currentProducts->count(),
            'topProduct' => $currentProducts->first(),
        ]);
    }

    private function productKey(object $product): string
    {
        return $product->product_id !== null
            ? 'id:' . $product->product_id
            : 'name:' . $product->product_name;
    }
}
