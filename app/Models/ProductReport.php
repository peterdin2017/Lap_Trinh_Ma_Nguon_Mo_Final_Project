<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReport extends Model
{
    protected $table = 'product_reports';

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'product_id',
        'account_id',
        'reason',
        'description',
        'status',
        'handled_by',
        'handled_at',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'handled_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /** Tài khoản gửi báo cáo. */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    /** Admin xử lý báo cáo. */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'handled_by', 'account_id');
    }
}
