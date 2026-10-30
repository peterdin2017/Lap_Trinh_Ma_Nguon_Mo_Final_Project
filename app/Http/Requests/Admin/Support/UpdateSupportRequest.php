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
            'status' => [
                'required',
                'in:pending,in_progress,resolved',
            ],

            'notify_customer' => [
                'nullable',
                'boolean',
            ],

            'admin_reply' => [
                'nullable',
                'string',
                'max:5000',
                'required_if:status,resolved',
                'required_if:notify_customer,1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái yêu cầu.',
            'status.in' => 'Trạng thái yêu cầu không hợp lệ.',
            'notify_customer.boolean' => 'Tùy chọn thông báo không hợp lệ.',
            'admin_reply.required_if' => 'Vui lòng nhập phản hồi trước khi hoàn tất hoặc thông báo cho khách.',
            'admin_reply.max' => 'Phản hồi không được vượt quá 5.000 ký tự.',
        ];
    }
}
