<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    protected $table = 'sellers';

    protected $primaryKey = 'seller_id';

    protected $fillable = [
        'account_id',
        'avatar',
        'shop_name',
        'shop_address',
        'shop_province',
        'shop_district',
        'shop_ward',
        'status',
        'reject_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    /** Shop chỉ được hiển thị/mua hàng khi hồ sơ đã duyệt và tài khoản còn hoạt động. */
    public function scopeAvailableForStore(Builder $query): Builder
    {
        return $query
            ->where('sellers.status', 'active')
            ->whereHas('account', fn (Builder $account) => $account->where('accounts.status', 'active'));
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(
            Account::class,
            'account_id',
            'account_id'
        );
    }

    public function products(): HasMany
    {
        return $this->hasMany(
            Product::class,
            'seller_id',
            'seller_id'
        );
    }
}
