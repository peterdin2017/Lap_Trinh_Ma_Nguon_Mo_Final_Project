<?php

namespace App\Http\Requests\Seller\Review;

use Illuminate\Foundation\Http\FormRequest;

class SellerReviewReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'seller';
    }

    public function rules(): array
    {
        return [
            'seller_reply' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'seller_reply.required' => 'Vui lòng nhập nội dung phản hồi.',
            'seller_reply.string' => 'Nội dung phản hồi không hợp lệ.',
            'seller_reply.max' => 'Phản hồi không được vượt quá 2.000 ký tự.',
        ];
    }
}
