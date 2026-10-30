<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformVoucher extends Model
{
    protected $table = 'platform_vouchers';

    protected $primaryKey = 'platform_voucher_id';

    protected $fillable = [
        'voucher_code',
        'voucher_name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'starts_at',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'per_user_limit' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function scopeAvailableAt(Builder $query, ?Carbon $time = null): Builder
    {
        $time ??= now();

        return $query
            ->where('status', 'active')
            ->where('starts_at', '<=', $time)
            ->where('expires_at', '>=', $time)
            ->where(function (Builder $query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    public function isAvailable(?Carbon $time = null): bool
    {
        $time ??= now();

        return $this->status === 'active'
            && $this->starts_at?->lessThanOrEqualTo($time) === true
            && $this->expires_at?->greaterThanOrEqualTo($time) === true
            && (
                $this->usage_limit === null
                || $this->used_count < $this->usage_limit
            );
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($orderAmount < (float) $this->min_order_amount) {
            return 0;
        }

        $discount = $this->discount_type === 'percentage'
            ? $orderAmount * ((float) $this->discount_value / 100)
            : (float) $this->discount_value;

        if ($this->max_discount_amount !== null) {
            $discount = min($discount, (float) $this->max_discount_amount);
        }

        return min(max($discount, 0), $orderAmount);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'platform_voucher_id',
            'platform_voucher_id'
        );
    }
}
