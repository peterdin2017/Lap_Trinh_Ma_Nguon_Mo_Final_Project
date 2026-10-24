<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,category_id'],
            'product_name' => ['required', 'string', 'max:200'],
            'brand' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'weight_gram' => ['nullable', 'integer', 'min:1', 'max:100000'],

            // Không bắt buộc tải lại ảnh chính khi sửa.
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,gif,bmp',
                'max:5120',
            ],

            // Ảnh phụ mới thêm vào sản phẩm, tối đa 8 ảnh trong một lần gửi.
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp,gif,bmp',
                'max:5120',
            ],

            // ID của các ảnh phụ cũ được chọn để xóa.
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer', 'distinct'],

            'variants_present' => ['sometimes', 'boolean'],
            'variants' => ['nullable', 'array', 'max:20'],
            'variants.*.variant_name' => ['required', 'string', 'max:100'],
            'variants.*.price' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'product_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',
            'brand.max' => 'Thương hiệu không được vượt quá 100 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 5000 ký tự.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán không được âm.',
            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng tồn kho không được âm.',
            'weight_gram.integer' => 'Cân nặng phải là số nguyên tính bằng gram.',
            'image.image' => 'Ảnh chính phải là tệp hình ảnh.',
            'image.mimes' => 'Ảnh chính phải có định dạng JPG, PNG, WEBP, GIF hoặc BMP.',
            'image.max' => 'Ảnh chính không được vượt quá 5MB.',
            'images.array' => 'Danh sách ảnh phụ không hợp lệ.',
            'images.max' => 'Mỗi lần chỉ được thêm tối đa 8 ảnh phụ.',
            'images.*.image' => 'Mỗi ảnh phụ phải là tệp hình ảnh.',
            'images.*.mimes' => 'Ảnh phụ phải có định dạng JPG, PNG, WEBP, GIF hoặc BMP.',
            'images.*.max' => 'Mỗi ảnh phụ không được vượt quá 5MB.',
            'remove_image_ids.*.integer' => 'Ảnh được chọn để xóa không hợp lệ.',
            'remove_image_ids.*.distinct' => 'Ảnh được chọn để xóa bị trùng.',

            'variants.array' => 'Danh sách mẫu mã không hợp lệ.',
            'variants.max' => 'Mỗi sản phẩm chỉ được có tối đa 20 mẫu mã.',
            'variants.*.variant_name.required' => 'Vui lòng nhập tên cho từng mẫu mã.',
            'variants.*.variant_name.max' => 'Tên mẫu mã không được vượt quá 100 ký tự.',
            'variants.*.price.required' => 'Vui lòng nhập giá cho từng mẫu mã.',
            'variants.*.price.numeric' => 'Giá mẫu mã phải là số.',
            'variants.*.price.min' => 'Giá mẫu mã không được âm.',
            'variants.*.stock_quantity.required' => 'Vui lòng nhập tồn kho cho từng mẫu mã.',
            'variants.*.stock_quantity.integer' => 'Tồn kho mẫu mã phải là số nguyên.',
            'variants.*.stock_quantity.min' => 'Tồn kho mẫu mã không được âm.',
        ];
    }
}
