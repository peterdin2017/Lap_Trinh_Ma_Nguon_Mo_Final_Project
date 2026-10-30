<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerNotification extends Model
{
    protected $table = 'customer_notifications';

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'account_id',
        'support_request_id',
        'title',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    public function supportRequest(): BelongsTo
    {
        return $this->belongsTo(
            CustomerSupportRequest::class,
            'support_request_id',
            'request_id'
        );
    }
}
