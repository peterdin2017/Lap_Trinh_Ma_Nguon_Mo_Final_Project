<?php

namespace App\Http\Requests\Seller\Order;

use Illuminate\Foundation\Http\FormRequest;

class SellerOrderFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:all,pending,processing,shipping,completed,cancelled'],
            'q' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'refresh' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'q.max' => 'Từ khóa tìm kiếm không được vượt quá 100 ký tự.',
            'date_to.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
        ];
    }
}
