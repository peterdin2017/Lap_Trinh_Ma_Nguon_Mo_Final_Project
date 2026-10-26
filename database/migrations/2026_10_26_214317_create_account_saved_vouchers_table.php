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
        Schema::create('account_saved_vouchers', function (Blueprint $table): void {
            $table->bigIncrements('saved_voucher_id');
            $table->unsignedBigInteger('account_id');

            // seller: voucher của Shop; platform: voucher do Admin quản lý.
            $table->enum('voucher_type', ['seller', 'platform']);
            $table->unsignedBigInteger('voucher_id');

            $table->timestamps();

            $table->unique(
                ['account_id', 'voucher_type', 'voucher_id'],
                'account_saved_vouchers_unique'
            );
            $table->index(['account_id', 'voucher_type', 'created_at']);

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_saved_vouchers');
    }
};
