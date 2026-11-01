<?php

namespace App\Http\Requests\Seller\Report;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SellerSalesAnalyticsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date', 'before_or_equal:today'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $filters = $this->validated();
            $timezone = config('app.timezone', 'UTC');
            $today = CarbonImmutable::now($timezone)->startOfDay();
            $to = isset($filters['date_to'])
                ? CarbonImmutable::parse($filters['date_to'], $timezone)->startOfDay()
                : $today;
            $from = isset($filters['date_from'])
                ? CarbonImmutable::parse($filters['date_from'], $timezone)->startOfDay()
                : (isset($filters['date_to']) ? $to->startOfMonth() : $today->startOfMonth());

            if ($from->diffInDays($to) + 1 > 366) {
                $validator->errors()->add('date_to', 'Khoảng thời gian tối đa là 366 ngày.');
            }
        });
    }
}
