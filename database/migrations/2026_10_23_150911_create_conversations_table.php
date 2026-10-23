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
        Schema::create('conversations', function (Blueprint $table) {
            $table->bigIncrements('conversation_id');
            $table->unsignedBigInteger('customer_account_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('seller_id')
                ->references('seller_id')
                ->on('sellers')
                ->cascadeOnDelete();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->nullOnDelete();

            // Mỗi khách có một cuộc trò chuyện với mỗi cửa hàng.
            $table->unique(['customer_account_id', 'seller_id']);
            $table->index(['seller_id', 'last_message_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
