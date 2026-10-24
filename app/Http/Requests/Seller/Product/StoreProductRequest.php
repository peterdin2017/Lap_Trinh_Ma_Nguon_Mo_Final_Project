<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            // Ảnh chính bắt buộc, tối đa 10 MB.
            'image' => [
                'required',
                'image',
                'max:10240',
            ],

            // Tối đa 8 ảnh phụ, mỗi ảnh tối đa 10 MB.
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => [
                'image',
                'max:10240',
            ],

            'variants_present' => ['nullable', 'boolean'],
            'variants' => ['nullable', 'array', 'max:20'],
            'variants.*.variant_name' => ['required', 'string', 'max:100'],
            'variants.*.price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999',
            ],
            'variants.*.stock_quantity' => [
                'required',
                'integer',
                'min:0',
                'max:1000000',
            ],
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

            'image.required' => 'Vui lòng chọn ảnh chính cho sản phẩm.',
            'image.image' => 'Ảnh chính tải lên không hợp lệ.',
            'image.max' => 'Ảnh chính không được vượt quá 10 MB.',

            'images.array' => 'Danh sách ảnh phụ không hợp lệ.',
            'images.max' => 'Sản phẩm chỉ được tải tối đa 8 ảnh phụ.',
            'images.*.image' => 'Một trong các ảnh phụ không hợp lệ.',
            'images.*.max' => 'Mỗi ảnh phụ không được vượt quá 10 MB.',

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
