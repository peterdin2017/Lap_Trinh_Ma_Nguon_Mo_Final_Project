<?php

namespace App\Http\Requests\Seller\Voucher;

use Illuminate\Foundation\Http\FormRequest;

class SellerVoucherIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,paused'],
        ];
    }
}
