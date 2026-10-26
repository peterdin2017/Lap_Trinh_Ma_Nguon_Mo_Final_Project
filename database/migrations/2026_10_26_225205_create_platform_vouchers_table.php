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
        Schema::create('platform_vouchers', function (Blueprint $table) {
            $table->bigIncrements('platform_voucher_id');

            $table->string('voucher_code', 50)->unique();
            $table->string('voucher_name', 150);
            $table->text('description')->nullable();

            // percentage: giảm theo phần trăm, fixed: giảm số tiền cố định
            $table->enum('discount_type', ['percentage', 'fixed'])
                ->default('fixed');

            $table->decimal('discount_value', 14, 2);
            $table->decimal('max_discount_amount', 14, 2)->nullable();
            $table->decimal('min_order_amount', 14, 2)->default(0);

            // Để null nếu không giới hạn tổng lượt sử dụng
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('per_user_limit')->default(1);

            $table->dateTime('starts_at');
            $table->dateTime('expires_at');

            $table->enum('status', ['active', 'inactive'])
                ->default('inactive');

            $table->timestamps();

            $table->index(['status', 'starts_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_vouchers');
    }
};
