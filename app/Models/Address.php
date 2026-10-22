<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'addresses';

    protected $primaryKey = 'address_id';

    protected $fillable = [
        'account_id',
        'recipient_name',
        'phone',
        'province',
        'district',
        'ward',
        'street_address',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }
}
