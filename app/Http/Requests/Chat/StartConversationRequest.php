<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class StartConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seller_id' => ['required', 'integer', 'exists:sellers,seller_id'],
            'product_id' => ['nullable', 'integer', 'exists:products,product_id'],
        ];
    }
}
