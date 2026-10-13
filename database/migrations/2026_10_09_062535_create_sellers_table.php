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
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('seller_id');
            $table->unsignedBigInteger('account_id')->unique();

            $table->string('shop_name', 100);
            $table->string('shop_address')->nullable();
            $table->string('shop_province', 100)->nullable();
            $table->string('shop_district', 100)->nullable();
            $table->string('shop_ward', 100)->nullable();

            $table->enum('status', [
                'pending',
                'active',
                'suspended',
                'rejected',
            ])->default('pending');

            $table->string('reject_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('sellers');
    }
};
