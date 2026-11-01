<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Product\ProductReportRequest;
use App\Models\Product;
use App\Models\ProductReport;
use Illuminate\Http\RedirectResponse;

class ProductReportController extends Controller
{
    public function store(ProductReportRequest $request, int $productId): RedirectResponse
    {
        $accountId = session('account_id');

        if (!$accountId || session('account_role') !== 'user') {
            return redirect()
                ->route('login.form')
                ->with('error', 'Vui lòng đăng nhập tài khoản khách hàng để báo cáo sản phẩm.');
        }

        $validated = $request->validated();

        $product = Product::query()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->firstOrFail();

        $report = ProductReport::query()->firstOrCreate(
            [
                'product_id' => $product->product_id,
                'account_id' => (int) $accountId,
            ],
            [
                'reason' => $validated['reason'],
                'description' => $validated['description'] ?? null,
                'status' => 'pending',
            ]
        );

        if (!$report->wasRecentlyCreated) {
            return back()
                ->withInput()
                ->with('report_error', 'Bạn đã báo cáo sản phẩm này rồi.');
        }

        return back()->with('report_success', 'Đã gửi báo cáo. Quản trị viên sẽ xem xét.');
    }
}
