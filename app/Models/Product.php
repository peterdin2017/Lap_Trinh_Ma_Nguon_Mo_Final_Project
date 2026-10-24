<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    // Chỉ cho phép gán hàng loạt các cột seller được nhập.
    // status, sold_count, rating... do hệ thống quản lý, không để seller sửa.
    protected $fillable = [
        'seller_id',
        'category_id',
        'product_name',
        'description',
        'brand',
        'price',
        'stock_quantity',
        'weight_gram',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating_avg' => 'decimal:1',
            'rating_count' => 'integer',
            'sold_count' => 'integer',
            'locked_at' => 'datetime',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Đường dẫn ảnh đầy đủ để hiện trên web: $product->image_src
    public function getImageSrcAttribute(): ?string
    {
        return $this->image_url ? asset('storage/' . $this->image_url) : null;
    }
    public function images()
    {
        return $this->hasMany(
            ProductImage::class,
            'product_id',
            'product_id'
        )->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(
            ProductVariant::class,
            'product_id',
            'product_id'
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }
}
