<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class SellerProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:all,active,draft,out_of_stock,hidden'],
            'q' => ['nullable', 'string', 'max:200'],
            'category' => ['nullable', 'integer', 'exists:categories,category_id'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'refresh' => ['sometimes', 'boolean'],
        ];
    }
}
