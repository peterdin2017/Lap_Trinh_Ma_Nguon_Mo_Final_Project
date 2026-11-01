<?php

namespace App\Http\Requests\Seller\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:processing,shipping,completed,cancelled'],
            'cancellation_reason' => [
                'required_if:status,cancelled', 'nullable', 'string', 'min:5', 'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
            'status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'cancellation_reason.required_if' => 'Vui lòng nhập lý do hủy đơn.',
            'cancellation_reason.min' => 'Lý do hủy đơn cần có ít nhất 5 ký tự.',
            'cancellation_reason.max' => 'Lý do hủy đơn không được vượt quá 1000 ký tự.',
        ];
    }
}
