<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->bigIncrements('order_item_id');

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();

            // Ảnh chụp thông tin sản phẩm tại thời điểm đặt hàng.
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->decimal('price', 14, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('total_price', 14, 2);

            // Ảnh chụp voucher và số tiền giảm trên dòng sản phẩm.
            $table->string('seller_voucher_code', 50)->nullable();
            $table->decimal('seller_voucher_discount', 14, 2)->default(0);

            $table->string('platform_voucher_code', 50)->nullable();
            $table->decimal('platform_voucher_discount', 14, 2)->default(0);

            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->nullOnDelete();

            $table->foreign('product_variant_id')
                ->references('variant_id')
                ->on('product_variants')
                ->nullOnDelete();

            $table->index('order_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
