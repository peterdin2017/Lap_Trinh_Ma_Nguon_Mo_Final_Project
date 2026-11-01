<?php

namespace App\Http\Requests\Seller\Review;

use Illuminate\Foundation\Http\FormRequest;

class SellerReviewIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', 'in:all,unanswered,answered'],
            'rating' => ['nullable', 'in:1,2,3,4,5'],
        ];
    }
}
