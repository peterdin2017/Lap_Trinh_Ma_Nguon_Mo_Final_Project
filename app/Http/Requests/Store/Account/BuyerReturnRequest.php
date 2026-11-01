<?php

namespace App\Http\Requests\Store\Account;

use Illuminate\Foundation\Http\FormRequest;

class BuyerReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'request_type' => ['required', 'in:return,refund'],
            'reason' => ['required', 'in:damaged,wrong_item,missing_parts,not_as_described,changed_mind,other'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'request_type.required' => 'Vui lòng chọn hình thức yêu cầu.',
            'request_type.in' => 'Hình thức yêu cầu không hợp lệ.',
            'reason.required' => 'Vui lòng chọn lý do.',
            'reason.in' => 'Lý do yêu cầu không hợp lệ.',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự.',
        ];
    }
}
