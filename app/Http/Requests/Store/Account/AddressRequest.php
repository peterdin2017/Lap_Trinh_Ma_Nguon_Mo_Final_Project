<?php

namespace App\Http\Requests\Store\Account;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->session()->get('account_id');
    }

    public function rules(): array
    {
        $location = [
            'required',
            'string',
            'max:100',
            'regex:/^[\p{L}\p{M}\d][\p{L}\p{M}\d\s.,\'\/-]*$/u',
        ];

        return [
            'recipient_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s.\'-]*$/u',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^(?:0|\+84)(?:2\d{8,9}|[35789]\d{8})$/',
            ],
            'province' => $location,
            'district' => $location,
            'ward' => $location,
            'street_address' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{M}\d][\p{L}\p{M}\d\s.,#\/-]*$/u',
            ],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.required' => 'Vui lòng nhập họ tên người nhận.',
            'recipient_name.regex' => 'Họ tên chỉ được chứa chữ cái và dấu cách.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Vui lòng nhập số điện thoại Việt Nam hợp lệ.',
            'province.required' => 'Vui lòng nhập tỉnh/thành phố.',
            'district.required' => 'Vui lòng nhập quận/huyện.',
            'ward.required' => 'Vui lòng nhập phường/xã.',
            'street_address.required' => 'Vui lòng nhập địa chỉ cụ thể.',
        ];
    }
}
