<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Bảng product_variants: phân loại hàng của sản phẩm (VD: "Đen - Size M").
     * Mỗi phân loại có giá và tồn kho riêng.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('variant_id');
            $table->unsignedBigInteger('product_id');

            // Một dòng cho mỗi tổ hợp, ví dụ: "Đen - Size M".
            $table->string('variant_name', 100);
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();

            // Trong 1 sản phẩm, tên phân loại không được trùng.
            $table->unique(['product_id', 'variant_name']);

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
