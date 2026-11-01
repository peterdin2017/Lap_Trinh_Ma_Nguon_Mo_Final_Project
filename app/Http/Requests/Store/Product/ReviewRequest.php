<?php

namespace App\Http\Requests\Store\Product;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_item_id' => ['required', 'integer', 'min:1'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_item_id.required' => 'Không tìm thấy sản phẩm trong đơn hàng để đánh giá.',
            'order_item_id.integer' => 'Dòng sản phẩm cần đánh giá không hợp lệ.',
            'rating.required' => 'Vui lòng chọn số sao.',
            'rating.integer' => 'Số sao không hợp lệ.',
            'rating.between' => 'Số sao phải từ 1 đến 5.',
            'comment.max' => 'Nhận xét tối đa 1000 ký tự.',
            'images.max' => 'Mỗi đánh giá chỉ được tải lên tối đa 5 ảnh.',
            'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Ảnh phải có định dạng JPEG, PNG hoặc WEBP.',
            'images.*.max' => 'Mỗi ảnh không được vượt quá 5MB.',
        ];
    }
}
