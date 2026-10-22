<?php

namespace App\Http\Requests\Store\Product;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Vui lòng chọn số sao.',
            'rating.integer' => 'Số sao không hợp lệ.',
            'rating.between' => 'Số sao phải từ 1 đến 5.',
            'comment.max' => 'Nhận xét tối đa 1000 ký tự.',
        ];
    }
}
