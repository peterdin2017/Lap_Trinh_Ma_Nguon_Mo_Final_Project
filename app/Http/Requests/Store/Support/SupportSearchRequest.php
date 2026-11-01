<?php

namespace App\Http\Requests\Store\Support;

use Illuminate\Foundation\Http\FormRequest;

class SupportSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:200']];
    }
}
