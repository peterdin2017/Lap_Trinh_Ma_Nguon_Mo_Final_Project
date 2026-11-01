<?php

namespace App\Http\Requests\Store\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutSelectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_item_ids' => ['required', 'array', 'min:1', 'max:100'],
            'cart_item_ids.*' => ['required', 'integer', 'min:1', 'distinct'],
        ];
    }
}
