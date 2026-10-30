<?php

namespace App\Http\Requests\Store\Support;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $accountId = $this->session()->get('account_id');

        if (! is_numeric($accountId)) {
            return false;
        }

        return \App\Models\Account::query()
            ->whereKey((int) $accountId)
            ->whereIn('role', ['user', 'seller'])
            ->where('status', 'active')
            ->exists();
    }

    public function rules(): array
    {
        return [
            'topic' => [
                'required',
                'in:account,order,payment,shipping,return,product,other',
            ],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,pdf,mp4,xls,xlsx,csv,zip,doc,docx',
            ],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'topic.required' => 'Vui lòng chọn vấn đề cần hỗ trợ.',
            'topic.in' => 'Vấn đề hỗ trợ không hợp lệ.',
            'message.required' => 'Vui lòng nhập nội dung cần hỗ trợ.',
            'message.min' => 'Nội dung cần có ít nhất 10 ký tự.',
            'message.max' => 'Nội dung không được vượt quá 5.000 ký tự.',
            'attachments.max' => 'Bạn chỉ được tải lên tối đa 5 tệp.',
            'attachments.*.max' => 'Mỗi tệp không được vượt quá 10 MB.',
            'attachments.*.mimes' => 'Định dạng tệp chưa được hỗ trợ.',
            'consent.accepted' => 'Bạn cần đồng ý với điều khoản xử lý thông tin.',
        ];
    }
}
