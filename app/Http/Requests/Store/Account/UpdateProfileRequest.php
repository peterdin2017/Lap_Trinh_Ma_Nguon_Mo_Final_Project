<?php

namespace App\Http\Requests\Store\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->session()->get('account_id');
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:15'],
            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other']),
            ],
            'birthdate' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            // Ảnh đại diện không bắt buộc; giới hạn 2 MB.
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập tên hiển thị.',
            'full_name.max' => 'Tên hiển thị không được vượt quá 100 ký tự.',
            'phone.string' => 'Số điện thoại không hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'birthdate.date' => 'Ngày sinh không hợp lệ.',
            'birthdate.before_or_equal' => 'Ngày sinh không được ở tương lai.',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng JPG, PNG hoặc WEBP.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'tên hiển thị',
            'phone' => 'số điện thoại',
            'gender' => 'giới tính',
            'birthdate' => 'ngày sinh',
            'avatar' => 'ảnh đại diện',
        ];
    }
}
