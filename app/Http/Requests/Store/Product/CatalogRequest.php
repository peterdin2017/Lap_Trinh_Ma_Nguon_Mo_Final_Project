<?php

namespace App\Http\Requests\Store\Product;

use App\Services\Store\ProductCatalog;
use Illuminate\Foundation\Http\FormRequest;

class CatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:191'],
            'min_price' => ['nullable', 'integer', 'min:0', 'max:1000000000000'],
            'max_price' => ['nullable', 'integer', 'min:0', 'max:1000000000000'],
            'sort' => ['nullable', 'in:' . implode(',', ProductCatalog::SORTS)],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'Từ khóa tìm kiếm tối đa 100 ký tự.',
            'min_price.integer' => 'Giá tối thiểu phải là số nguyên.',
            'max_price.integer' => 'Giá tối đa phải là số nguyên.',
            'min_price.min' => 'Giá không được âm.',
            'max_price.min' => 'Giá không được âm.',
            'sort.in' => 'Cách sắp xếp không hợp lệ.',
        ];
    }

    /**
     * Bộ lọc đã chuẩn hóa. Khoảng giá ngược (min > max) được đổi chỗ cho đúng.
     *
     * @return array{q: string, category: int|string|null, min_price: ?int, max_price: ?int, sort: string}
     */
    public function filters(): array
    {
        $validated = $this->validated();
        $min = isset($validated['min_price']) ? (int) $validated['min_price'] : null;
        $max = isset($validated['max_price']) ? (int) $validated['max_price'] : null;

        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        $category = trim((string) ($validated['category'] ?? ''));

        return [
            'q' => trim((string) ($validated['q'] ?? '')),
            'category' => $category === ''
                ? null
                : (ctype_digit($category) ? (int) $category : $category),
            'min_price' => $min,
            'max_price' => $max,
            'sort' => $validated['sort'] ?? 'newest',
        ];
    }
}
