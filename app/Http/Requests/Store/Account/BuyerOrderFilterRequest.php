<?php

namespace App\Http\Requests\Store\Account;

use Illuminate\Foundation\Http\FormRequest;

class BuyerOrderFilterRequest extends FormRequest
{
    private const STATUSES = ['all', 'pending', 'shipping', 'completed', 'cancelled', 'return_requested'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:' . implode(',', self::STATUSES)],
            'refresh' => ['sometimes', 'boolean'],
        ];
    }
}
