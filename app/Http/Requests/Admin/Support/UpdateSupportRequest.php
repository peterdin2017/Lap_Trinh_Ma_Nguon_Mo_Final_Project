<?php

namespace App\Http\Requests\Admin\Support;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSupportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'admin_reply' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_reply.required' => 'Vui lòng nhập nội dung phản hồi cho khách.',
            'admin_reply.string' => 'Nội dung phản hồi không hợp lệ.',
            'admin_reply.max' => 'Phản hồi không được vượt quá 5.000 ký tự.',
        ];
    }
}
