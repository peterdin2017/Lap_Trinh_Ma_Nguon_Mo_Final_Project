<?php

namespace App\Http\Requests\Seller\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'seller';
    }

    public function rules(): array
    {
        return [
            'shop_name' => ['required', 'string', 'max:100'],
            'shop_address' => ['nullable', 'string', 'max:255'],
            'shop_province' => ['nullable', 'string', 'max:100'],
            'shop_district' => ['nullable', 'string', 'max:100'],
            'shop_ward' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'shop_name.required' => 'Vui lòng nhập tên shop.',
            'shop_name.max' => 'Tên shop không được vượt quá 100 ký tự.',
            'shop_address.max' => 'Địa chỉ shop không được vượt quá 255 ký tự.',
            'shop_province.max' => 'Tỉnh/thành phố không được vượt quá 100 ký tự.',
            'shop_district.max' => 'Quận/huyện không được vượt quá 100 ký tự.',
            'shop_ward.max' => 'Phường/xã không được vượt quá 100 ký tự.',
        ];
    }
}
