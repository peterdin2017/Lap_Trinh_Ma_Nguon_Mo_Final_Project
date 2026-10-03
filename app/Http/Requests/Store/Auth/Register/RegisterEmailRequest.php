<?php

namespace App\Http\Requests\Store\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;

class RegisterEmailRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    // Quy tắc kiểm tra
    public function rules():array
    {
        return[
            'email'=>[
                'required',
                'email',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
        ];
    }
}
