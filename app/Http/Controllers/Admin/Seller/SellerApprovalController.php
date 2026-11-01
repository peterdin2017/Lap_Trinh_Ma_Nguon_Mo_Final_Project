<?php

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Seller\RejectSellerRequest;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
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
    public function reject(RejectSellerRequest $request, Seller $seller): RedirectResponse
    {
        $data = $request->validated();

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
