<?php

namespace App\Http\Requests\Store\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ShopSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:100']];
    }
}
