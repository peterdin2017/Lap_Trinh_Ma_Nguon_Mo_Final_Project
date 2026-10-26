<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_vouchers', function (Blueprint $table): void {
            $table->bigIncrements('voucher_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('code', 30);
            $table->enum('discount_type', ['percent', 'fixed']);
            $table->decimal('discount_value', 14, 2);
            $table->decimal('min_order_amount', 14, 2)->default(0);
            $table->decimal('max_discount', 14, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'paused'])->default('active');
            $table->timestamps();

            $table->unique(['seller_id', 'code']);
            $table->index(['seller_id', 'status', 'starts_at', 'expires_at']);

            $table->foreign('seller_id')
                ->references('seller_id')
                ->on('sellers')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_vouchers');
    }
};
