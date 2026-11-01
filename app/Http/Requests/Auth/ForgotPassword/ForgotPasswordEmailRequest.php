<?php

namespace App\Http\Requests\Auth\ForgotPassword;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $portal = $this->input('portal', $this->query('portal', 'store'));
        $this->merge([
            'portal' => in_array($portal, ['store', 'seller', 'admin'], true)
                ? $portal
                : 'store',
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:100'],
            'portal' => ['required', 'in:store,seller,admin'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 100 ký tự.',
        ];
    }
}
