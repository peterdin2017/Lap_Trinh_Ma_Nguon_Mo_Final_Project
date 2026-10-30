<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerSupportRequest extends Model
{
    protected $table = 'customer_support_requests';

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'account_id',
        'requester_role',
        'topic',
        'message',
        'attachments',
        'status',
        'admin_reply',
        'handled_by',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'handled_at' => 'datetime',
        ];
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
