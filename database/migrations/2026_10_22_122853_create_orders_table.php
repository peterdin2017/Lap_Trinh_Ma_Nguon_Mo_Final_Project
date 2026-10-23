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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->string('order_code', 32)->unique();

            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('seller_id');

            $table->string('shipping_name', 100);
            $table->string('shipping_phone', 20);
            $table->text('shipping_address');

            $table->enum('payment_method', [
                'cod',
                'vnpay',
                'bank_transfer',
            ])->default('cod');

            $table->enum('payment_status', [
                'pending',
                'paid',
            ])->default('pending');

            $table->enum('order_status', [
                'pending',
                'processing',
                'shipping',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('shipping_fee', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('seller_id')
                ->references('seller_id')
                ->on('sellers')
                ->restrictOnDelete();

            $table->index(['account_id', 'created_at']);
            $table->index(['seller_id', 'order_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
