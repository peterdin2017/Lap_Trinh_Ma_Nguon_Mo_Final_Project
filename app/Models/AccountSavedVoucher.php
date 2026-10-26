<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSavedVoucher extends Model
{
    protected $table = 'account_saved_vouchers';

    protected $primaryKey = 'saved_voucher_id';

    protected $fillable = [
        'account_id',
        'voucher_type',
        'voucher_id',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }
}
