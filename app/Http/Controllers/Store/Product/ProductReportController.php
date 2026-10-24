<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReportController extends Controller
{
    private const REASONS = [
        'fake',
        'prohibited',
        'wrong_info',
        'copyright',
        'other',
    ];

    public function store(Request $request, int $productId): RedirectResponse
    {
        $accountId = session('account_id');

        if (!$accountId || session('account_role') !== 'user') {
            return redirect()
                ->route('login.form')
                ->with('error', 'Vui lòng đăng nhập tài khoản khách hàng để báo cáo sản phẩm.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'in:' . implode(',', self::REASONS)],
            'description' => ['nullable', 'string', 'max:2000', 'required_if:reason,other'],
        ], [
            'reason.required' => 'Vui lòng chọn lý do báo cáo.',
            'reason.in' => 'Lý do báo cáo không hợp lệ.',
            'description.required_if' => 'Vui lòng mô tả chi tiết khi chọn lý do khác.',
            'description.max' => 'Mô tả không được vượt quá 2.000 ký tự.',
        ]);

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
