<?php

namespace App\Http\Requests\Store\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // CheckoutController xác định tài khoản đang đăng nhập
        // bằng session account_id trước khi xử lý đơn hàng.
        return true;
    }

    public function rules(): array
    {
        $isApplyingVouchers = $this->input('intent') === 'apply_vouchers';

        return [
            'intent' => [
                'nullable',
                'in:place_order,apply_vouchers',
            ],

            'cart_item_ids' => ['required', 'array', 'min:1', 'max:100'],
            'cart_item_ids.*' => [
                'required',
                'integer',
                'min:1',
                'distinct',
            ],

            'voucher_codes' => ['nullable', 'array', 'max:100'],
            'voucher_codes.*' => [
                'nullable',
                'string',
                'alpha_dash:ascii',
                'max:30',
            ],

            'shipping_name' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'string',
                'max:100',
            ],
            'shipping_phone' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'string',
                'max:20',
                'regex:/^[0-9+\s().-]{8,20}$/',
            ],
            'street_address' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'string',
                'max:255',
            ],
            'province' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'string',
                'max:100',
            ],
            'district' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'string',
                'max:100',
            ],
            'ward' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'string',
                'max:100',
            ],

            'payment_method' => [
                $isApplyingVouchers ? 'nullable' : 'required',
                'in:cod,vnpay,bank_transfer',
            ],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'cart_item_ids' => 'sản phẩm đã chọn',
            'shipping_name' => 'tên người nhận',
            'shipping_phone' => 'số điện thoại',
            'street_address' => 'địa chỉ cụ thể',
            'province' => 'tỉnh/thành phố',
            'district' => 'quận/huyện',
            'ward' => 'phường/xã',
            'payment_method' => 'phương thức thanh toán',
            'note' => 'ghi chú',
            'voucher_codes' => 'mã voucher',
            'voucher_codes.*' => 'mã voucher',
        ];
    }

    public function messages(): array
    {
        return [
            'voucher_codes.*.alpha_dash' =>
                'Mã voucher chỉ gồm chữ không dấu, số, dấu gạch ngang hoặc gạch dưới.',
            'voucher_codes.*.max' => 'Mã voucher không được vượt quá 30 ký tự.',
        ];
    }
}
