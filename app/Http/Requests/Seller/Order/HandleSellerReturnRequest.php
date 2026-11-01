<?php

namespace App\Http\Requests\Seller\Order;

use Illuminate\Foundation\Http\FormRequest;

class HandleSellerReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['decision' => ['required', 'in:approved,rejected']];
    }

    public function messages(): array
    {
        return [
            'decision.required' => 'Vui lòng chọn cách xử lý yêu cầu.',
            'decision.in' => 'Quyết định xử lý không hợp lệ.',
        ];
    }
}
