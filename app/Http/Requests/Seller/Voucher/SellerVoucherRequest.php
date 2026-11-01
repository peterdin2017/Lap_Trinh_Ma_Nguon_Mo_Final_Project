<?php

namespace App\Http\Requests\Seller\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SellerVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPercent = $this->input('discount_type') === 'percent';
        $sellerId = $this->user()?->seller?->seller_id;

        return [
            'code' => [
                'required', 'string', 'alpha_dash:ascii', 'max:30',
                Rule::unique('seller_vouchers', 'code')
                    ->where('seller_id', $sellerId ?? -1)
                    ->ignore($this->route('voucher'), 'voucher_id'),
            ],
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'discount_value' => [
                'required', 'numeric', 'gt:0', 'max:' . ($isPercent ? '100' : '999999999999'),
            ],
            'min_order_amount' => ['required', 'numeric', 'min:0', 'max:999999999999'],
            'max_discount' => [
                Rule::requiredIf($isPercent), 'nullable', 'numeric', 'gt:0', 'max:999999999999',
            ],
            'usage_limit' => ['nullable', 'integer', 'min:1', 'max:10000000'],
            'starts_at' => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after:starts_at'],
            'status' => ['required', Rule::in(['active', 'paused'])],
        ];
    }

    public function messages(): array
    {
        $isPercent = $this->input('discount_type') === 'percent';

        return [
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
        ];
    }
}
