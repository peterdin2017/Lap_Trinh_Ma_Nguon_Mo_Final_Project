<?php

namespace App\Http\Requests\Admin\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlatformVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route quản trị đã được bảo vệ bằng middleware admin.
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('voucher_code')) {
            $this->merge([
                'voucher_code' => strtoupper(trim((string) $this->input('voucher_code'))),
            ]);
        }
    }

    public function rules(): array
    {
        $voucherId = $this->route('platformVoucher');

        return [
            'voucher_code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique('platform_vouchers', 'voucher_code')
                    ->ignore($voucherId, 'platform_voucher_id'),
            ],
            'voucher_name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => [
                'required',
                'numeric',
                'gt:0',
                Rule::when(
                    $this->input('discount_type') === 'percentage',
                    ['max:100']
                ),
            ],
            'max_discount_amount' => ['nullable', 'numeric', 'gt:0'],
            'min_order_amount' => ['required', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['required', 'integer', 'min:1'],
            'starts_at' => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after:starts_at'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'voucher_code.required' => 'Vui lòng nhập mã voucher.',
            'voucher_code.regex' => 'Mã chỉ được chứa chữ in hoa, số, dấu gạch ngang và gạch dưới.',
            'voucher_code.unique' => 'Mã voucher này đã tồn tại.',

            'voucher_name.required' => 'Vui lòng nhập tên voucher.',
            'voucher_name.max' => 'Tên voucher không được vượt quá 150 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự.',

            'discount_type.required' => 'Vui lòng chọn kiểu giảm giá.',
            'discount_type.in' => 'Kiểu giảm giá không hợp lệ.',
            'discount_value.required' => 'Vui lòng nhập mức giảm.',
            'discount_value.numeric' => 'Mức giảm phải là số.',
            'discount_value.gt' => 'Mức giảm phải lớn hơn 0.',
            'discount_value.max' => 'Voucher phần trăm không được vượt quá 100%.',

            'max_discount_amount.numeric' => 'Mức giảm tối đa phải là số.',
            'max_discount_amount.gt' => 'Mức giảm tối đa phải lớn hơn 0.',
            'min_order_amount.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu không được âm.',

            'usage_limit.integer' => 'Tổng lượt sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Tổng lượt sử dụng phải từ 1 trở lên.',
            'per_user_limit.required' => 'Vui lòng nhập số lượt dùng tối đa mỗi khách.',
            'per_user_limit.integer' => 'Số lượt dùng mỗi khách phải là số nguyên.',
            'per_user_limit.min' => 'Số lượt dùng mỗi khách phải từ 1 trở lên.',

            'starts_at.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'starts_at.date' => 'Thời gian bắt đầu không hợp lệ.',
            'expires_at.required' => 'Vui lòng chọn thời gian kết thúc.',
            'expires_at.date' => 'Thời gian kết thúc không hợp lệ.',
            'expires_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',

            'status.required' => 'Vui lòng chọn trạng thái voucher.',
            'status.in' => 'Trạng thái voucher không hợp lệ.',
        ];
    }
}
