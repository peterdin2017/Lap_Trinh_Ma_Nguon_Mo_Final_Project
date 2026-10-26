<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReturnRequest extends Model
{
    protected $table = 'order_return_requests';

    protected $primaryKey = 'return_request_id';

    protected $fillable = [
        'order_id',
        'account_id',
        'request_type',
        'reason',
        'description',
        'status',
        'seller_note',
        'handled_by',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'handled_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'handled_by', 'account_id');
    }
}
