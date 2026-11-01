<?php

namespace App\Http\Requests\Store\Product;

use Illuminate\Foundation\Http\FormRequest;

class CartBulkDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keys' => ['required', 'array', 'min:1', 'max:100'],
            'keys.*' => ['required', 'string', 'max:100'],
        ];
    }
}
