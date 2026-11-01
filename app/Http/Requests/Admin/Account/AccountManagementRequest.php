<?php

namespace App\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccountManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return [
                'q' => ['nullable', 'string', 'max:100'],
                'role' => ['nullable', 'in:user,seller,admin'],
                'status' => ['nullable', 'in:active,locked'],
            ];
        }

        return ['status' => ['required', 'in:active,locked']];
    }
}
