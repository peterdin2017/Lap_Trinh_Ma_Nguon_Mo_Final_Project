<?php

namespace App\Http\Controllers\Seller\Voucher;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SellerVoucherController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $this->currentSeller($request);

        $vouchers = SellerVoucher::query()
            ->where('seller_id', $seller->seller_id)
            ->latest('created_at')
            ->paginate(12)
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

    public function store(Request $request): RedirectResponse
    {
        $seller = $this->currentSeller($request);
        $data = $this->validatedData($request, $seller);

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

    public function update(Request $request, int $voucher): RedirectResponse
    {
        $seller = $this->currentSeller($request);
        $voucherModel = $this->sellerVouchers($seller)->findOrFail($voucher);
        $data = $this->validatedData($request, $seller, $voucherModel);

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

    private function validatedData(
        Request $request,
        Seller $seller,
        ?SellerVoucher $voucher = null
    ): array {
        $isPercent = $request->input('discount_type') === 'percent';

        return $request->validate([
            'code' => [
                'required',
                'string',
                'alpha_dash:ascii',
                'max:30',
                Rule::unique('seller_vouchers', 'code')
                    ->where('seller_id', $seller->seller_id)
                    ->ignore($voucher?->voucher_id, 'voucher_id'),
            ],
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'discount_value' => [
                'required',
                'numeric',
                'gt:0',
                'max:' . ($isPercent ? '100' : '999999999999'),
            ],
            'min_order_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999',
            ],
            'max_discount' => [
                Rule::requiredIf($isPercent),
                'nullable',
                'numeric',
                'gt:0',
                'max:999999999999',
            ],
            'usage_limit' => ['nullable', 'integer', 'min:1', 'max:10000000'],
            'starts_at' => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after:starts_at'],
            'status' => ['required', Rule::in(['active', 'paused'])],
        ], [
            'code.required' => 'Vui lòng nhập mã voucher.',
            'code.alpha_dash' => 'Mã chỉ gồm chữ, số, dấu gạch ngang hoặc gạch dưới.',
            'code.unique' => 'Shop đã có mã voucher này.',
            'discount_type.required' => 'Vui lòng chọn kiểu giảm giá.',
            'discount_value.required' => 'Vui lòng nhập mức giảm.',
            'discount_value.gt' => 'Mức giảm phải lớn hơn 0.',
            'discount_value.max' => $isPercent
                ? 'Mức giảm phần trăm không được vượt quá 100%.'
                : 'Số tiền giảm vượt quá giới hạn cho phép.',
            'min_order_amount.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'max_discount.required' => 'Voucher giảm theo phần trăm cần có mức giảm tối đa.',
            'usage_limit.integer' => 'Số lượt sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Số lượt sử dụng phải lớn hơn 0.',
            'starts_at.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'expires_at.required' => 'Vui lòng chọn thời gian kết thúc.',
            'expires_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);
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
