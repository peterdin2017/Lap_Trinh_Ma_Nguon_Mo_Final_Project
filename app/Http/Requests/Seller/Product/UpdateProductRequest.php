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
        $variantMode = $this->input('pricing_mode') === 'variants';

        return [
            'pricing_mode' => ['required', 'in:single,variants'],

            'category_id' => ['required', 'exists:categories,category_id'],
            'product_name' => ['required', 'string', 'max:200'],
            'brand' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],

            // Sản phẩm có mẫu mã sẽ lấy giá thấp nhất và tổng tồn kho từ các mẫu.
            'price' => [
                $variantMode ? 'nullable' : 'required',
                'numeric',
                'min:0',
                'max:9999999999',
            ],
            'stock_quantity' => [
                $variantMode ? 'nullable' : 'required',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'weight_gram' => ['nullable', 'integer', 'min:1', 'max:100000'],

            // Không bắt buộc tải lại ảnh chính khi sửa.
            'image' => [
                'nullable',
                'image',
                'max:10240',
            ],

            // Tối đa 8 ảnh phụ trong một lần gửi.
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => [
                'image',
                'max:10240',
            ],

            // ID của ảnh phụ cũ được chọn để xóa.
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer', 'distinct'],

            'variants_present' => ['sometimes', 'boolean'],
            'variants' => [
                $variantMode ? 'required' : 'nullable',
                'array',
                $variantMode ? 'min:1' : 'max:20',
                'max:20',
            ],

            // ID trống với mẫu mã mới; controller kiểm tra mẫu cũ thuộc sản phẩm nào.
            'variants.*.variant_id' => ['nullable', 'integer', 'distinct'],

            'variants.*.variant_name' => [
                $variantMode ? 'required' : 'nullable',
                'string',
                'max:100',
            ],
            'variants.*.price' => [
                $variantMode ? 'required' : 'nullable',
                'numeric',
                'min:0',
                'max:9999999999',
            ],
            'variants.*.stock_quantity' => [
                $variantMode ? 'required' : 'nullable',
                'integer',
                'min:0',
                'max:1000000',
            ],
            'variants.*.image' => ['nullable', 'image', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'pricing_mode.required' => 'Vui lòng chọn kiểu sản phẩm.',
            'pricing_mode.in' => 'Kiểu sản phẩm không hợp lệ.',

            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',

            'product_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',
            'brand.max' => 'Thương hiệu không được vượt quá 100 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 5000 ký tự.',

            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán không được âm.',
            'price.max' => 'Giá bán vượt quá giới hạn cho phép.',

            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng tồn kho không được âm.',
            'stock_quantity.max' => 'Số lượng tồn kho vượt quá giới hạn cho phép.',

            'weight_gram.integer' => 'Cân nặng phải là số nguyên tính bằng gram.',

            'image.image' => 'Ảnh chính tải lên không hợp lệ.',
            'image.max' => 'Ảnh chính không được vượt quá 10 MB.',

            'images.array' => 'Danh sách ảnh phụ không hợp lệ.',
            'images.max' => 'Mỗi lần chỉ được thêm tối đa 8 ảnh phụ.',
            'images.*.image' => 'Mỗi ảnh phụ tải lên không hợp lệ.',
            'images.*.max' => 'Mỗi ảnh phụ không được vượt quá 10 MB.',

            'remove_image_ids.*.integer' => 'Ảnh được chọn để xóa không hợp lệ.',
            'remove_image_ids.*.distinct' => 'Ảnh được chọn để xóa bị trùng.',

            'variants.required' => 'Vui lòng thêm ít nhất một mẫu mã.',
            'variants.array' => 'Danh sách mẫu mã không hợp lệ.',
            'variants.min' => 'Vui lòng thêm ít nhất một mẫu mã.',
            'variants.max' => 'Mỗi sản phẩm chỉ được có tối đa 20 mẫu mã.',

            'variants.*.variant_id.integer' => 'Mẫu mã được chọn không hợp lệ.',
            'variants.*.variant_id.distinct' => 'Mẫu mã bị gửi trùng.',

            'variants.*.variant_name.required' => 'Vui lòng nhập tên cho từng mẫu mã.',
            'variants.*.variant_name.max' => 'Tên mẫu mã không được vượt quá 100 ký tự.',

            'variants.*.price.required' => 'Vui lòng nhập giá cho từng mẫu mã.',
            'variants.*.price.numeric' => 'Giá mẫu mã phải là số.',
            'variants.*.price.min' => 'Giá mẫu mã không được âm.',
            'variants.*.price.max' => 'Giá mẫu mã vượt quá giới hạn cho phép.',

            'variants.*.stock_quantity.required' => 'Vui lòng nhập tồn kho cho từng mẫu mã.',
            'variants.*.stock_quantity.integer' => 'Tồn kho mẫu mã phải là số nguyên.',
            'variants.*.stock_quantity.min' => 'Tồn kho mẫu mã không được âm.',
            'variants.*.stock_quantity.max' => 'Tồn kho mẫu mã vượt quá giới hạn cho phép.',

            'variants.*.image.image' => 'Ảnh mẫu mã tải lên không hợp lệ.',
            'variants.*.image.max' => 'Ảnh mẫu mã không được vượt quá 10 MB.',
        ];
    }
}
