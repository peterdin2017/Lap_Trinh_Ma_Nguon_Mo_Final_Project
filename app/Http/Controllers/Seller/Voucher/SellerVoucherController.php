<?php

namespace App\Http\Controllers\Seller\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Voucher\SellerVoucherIndexRequest;
use App\Http\Requests\Seller\Voucher\SellerVoucherRequest;
use App\Models\Seller;
use App\Models\SellerVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SellerVoucherController extends Controller
{
    public function index(SellerVoucherIndexRequest $request): View
    {
        $seller = $this->currentSeller($request);
        $filters = $request->validated();

        $vouchers = SellerVoucher::query()
            ->where('seller_id', $seller->seller_id)
            ->when(! empty($filters['keyword']), function ($query) use ($filters): void {
                $keyword = trim($filters['keyword']);
                $query->where('code', 'like', "%{$keyword}%");
            })
            ->when(! empty($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('seller.vouchers.index', compact('vouchers'));
    }

    public function create(Request $request): View
    {
        $this->currentSeller($request);

        return view('seller.vouchers.form', [
            'voucher' => new SellerVoucher([
                'discount_type' => 'percent',
                'min_order_amount' => 0,
                'status' => 'active',
            ]),
            'isEditing' => false,
        ]);
    }

    public function store(SellerVoucherRequest $request): RedirectResponse
    {
        $seller = $this->currentSeller($request);
        $data = $request->validated();

        SellerVoucher::query()->create([
            ...$data,
            'seller_id' => $seller->seller_id,
        ]);

        return redirect()->route('seller.vouchers.index')
            ->with('success', 'Đã tạo voucher của Shop.');
    }

    public function edit(Request $request, int $voucher): View
    {
        $seller = $this->currentSeller($request);
        $voucher = $this->sellerVouchers($seller)->findOrFail($voucher);

        return view('seller.vouchers.form', [
            'voucher' => $voucher,
            'isEditing' => true,
        ]);
    }

    public function update(SellerVoucherRequest $request, int $voucher): RedirectResponse
    {
        $seller = $this->currentSeller($request);
        $voucherModel = $this->sellerVouchers($seller)->findOrFail($voucher);
        $data = $request->validated();

        $voucherModel->update($data);

        return redirect()->route('seller.vouchers.index')
            ->with('success', 'Đã cập nhật voucher.');
    }

    public function toggleStatus(Request $request, int $voucher): RedirectResponse
    {
        $seller = $this->currentSeller($request);

        DB::transaction(function () use ($seller, $voucher): void {
            $voucherModel = $this->sellerVouchers($seller)
                ->lockForUpdate()
                ->findOrFail($voucher);

            $voucherModel->status = $voucherModel->status === 'active'
                ? 'paused'
                : 'active';

            $voucherModel->save();
        });

        return back()->with('success', 'Đã cập nhật trạng thái voucher.');
    }

    public function destroy(Request $request, int $voucher): RedirectResponse
    {
        $seller = $this->currentSeller($request);
        $voucherModel = $this->sellerVouchers($seller)->findOrFail($voucher);

        if ($voucherModel->used_count > 0) {
            throw ValidationException::withMessages([
                'voucher' => 'Voucher đã có lượt sử dụng nên không thể xóa. Hãy tạm dừng voucher.',
            ]);
        }

        $voucherModel->delete();

        return back()->with('success', 'Đã xóa voucher.');
    }

    private function sellerVouchers(Seller $seller)
    {
        return SellerVoucher::query()
            ->where('seller_id', $seller->seller_id);
    }

    private function currentSeller(Request $request): Seller
    {
        $seller = $request->user()?->seller;

        abort_unless(
            $seller instanceof Seller,
            403,
            'Không tìm thấy hồ sơ cửa hàng.'
        );

        return $seller;
    }
}
