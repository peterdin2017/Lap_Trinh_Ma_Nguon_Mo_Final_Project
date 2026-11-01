<?php

namespace App\Http\Requests\Admin\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return ['status' => ['nullable', 'in:pending,resolved,dismissed']];
        }

        return [
            'decision' => ['required', 'in:resolved,dismissed'],
            'admin_note' => ['required', 'string', 'max:255'],
        ];
    }
}
