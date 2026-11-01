<?php

namespace App\Http\Requests\Auth\ForgotPassword;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordPortalRequest extends FormRequest
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
        return ['portal' => ['required', 'in:store,seller,admin']];
    }
}
