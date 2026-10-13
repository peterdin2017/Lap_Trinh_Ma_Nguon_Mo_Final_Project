<?php

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerApprovalController extends Controller
{
    // Hiển thị các shop đang chờ Admin duyệt.
    public function index(): View
    {
        $sellers = Seller::query()
            ->with('account')
            ->where('status', 'pending')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.sellers.index', compact('sellers'));
    }

    // Duyệt shop đang chờ xử lý.
    public function approve(Seller $seller): RedirectResponse
    {
        $updated = Seller::query()
            ->whereKey($seller->getKey())
            ->where('status', 'pending')
            ->update([
                'status' => 'active',
                'approved_at' => now(),
                'reject_reason' => null,
            ]);

        if (! $updated) {
            return back()->with(
                'error',
                'Hồ sơ này không còn ở trạng thái chờ duyệt.'
            );
        }

        return back()->with(
            'success',
            "Đã duyệt shop {$seller->shop_name}."
        );
    }

    // Từ chối shop và lưu lý do.
    public function reject(Request $request, Seller $seller): RedirectResponse
    {
        $data = $request->validate([
            'reject_reason' => ['required', 'string', 'max:255'],
        ], [
            'reject_reason.required' => 'Vui lòng nhập lý do từ chối hồ sơ.',
            'reject_reason.max' => 'Lý do từ chối không được vượt quá 255 ký tự.',
        ]);

        $updated = Seller::query()
            ->whereKey($seller->getKey())
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'reject_reason' => $data['reject_reason'],
                'approved_at' => null,
            ]);

        if (! $updated) {
            return back()->with(
                'error',
                'Hồ sơ này không còn ở trạng thái chờ duyệt.'
            );
        }

        return back()->with(
            'success',
            "Đã từ chối hồ sơ shop {$seller->shop_name}."
        );
    }
}
