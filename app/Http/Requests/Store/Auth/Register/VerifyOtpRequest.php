<?php

namespace App\Http\Requests\Store\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'otp_code' => [
                'required',
                'digits:6',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'otp_code.required' => 'Vui lòng nhập mã OTP',
            'otp_code.digits' => 'Mã OTP phải gồm 6 chữ số',
        ];
    }
}
