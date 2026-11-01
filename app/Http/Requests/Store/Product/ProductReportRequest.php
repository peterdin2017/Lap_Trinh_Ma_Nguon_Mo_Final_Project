<?php

namespace App\Http\Requests\Store\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductReportRequest extends FormRequest
{
    private const REASONS = ['fake', 'prohibited', 'wrong_info', 'copyright', 'other'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'in:' . implode(',', self::REASONS)],
            'description' => ['nullable', 'string', 'max:2000', 'required_if:reason,other'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Vui lòng chọn lý do báo cáo.',
            'reason.in' => 'Lý do báo cáo không hợp lệ.',
            'description.required_if' => 'Vui lòng mô tả chi tiết khi chọn lý do khác.',
            'description.max' => 'Mô tả không được vượt quá 2.000 ký tự.',
        ];
    }
}
