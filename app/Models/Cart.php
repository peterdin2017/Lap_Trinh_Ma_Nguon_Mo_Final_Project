<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CartItem;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'cart_id';

    protected $fillable = [
        'account_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
}
