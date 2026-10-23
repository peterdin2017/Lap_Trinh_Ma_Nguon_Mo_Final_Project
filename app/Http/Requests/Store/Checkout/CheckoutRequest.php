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
        return [
            'cart_item_ids' => ['required', 'array', 'min:1', 'max:100'],
            'cart_item_ids.*' => [
                'required',
                'integer',
                'min:1',
                'distinct',
            ],

            'shipping_name' => ['required', 'string', 'max:100'],
            'shipping_phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\s().-]{8,20}$/',
            ],
            'street_address' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],

            'payment_method' => [
                'required',
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
        ];
    }
}
