<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $table = 'accounts';

    protected $primaryKey = 'account_id';

    protected $fillable = [
        'email',
        'password_hash',
        'role',
        'status',
        'is_verified',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }
    public function seller(): HasOne
    {
        return $this->hasOne(
            Seller::class,
            'account_id',
            'account_id'
        );
    }
}
