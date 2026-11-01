<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductModerationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return [
                'q' => ['nullable', 'string', 'max:200'],
                'status' => ['nullable', 'in:draft,active,hidden,locked'],
                'category' => ['nullable', 'integer', 'exists:categories,category_id'],
                'from' => ['nullable', 'date_format:Y-m-d'],
                'to' => ['nullable', 'date_format:Y-m-d'],
            ];
        }

        return ['locked_reason' => ['required', 'string', 'max:255']];
    }
}
