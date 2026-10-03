<?php

namespace App\Http\Requests\Store\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ tên',

            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.mixed' => 'Mật khẩu phải có cả chữ hoa và chữ thường',
            'password.numbers' => 'Mật khẩu phải có ít nhất 1 chữ số',
            'password.symbols' => 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt',
        ];
    }
}
