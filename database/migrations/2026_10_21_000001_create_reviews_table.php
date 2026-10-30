<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng reviews: đánh giá theo TỪNG DÒNG HÀNG đã mua.
     * products.rating_avg / rating_count (đã có sẵn) được cập nhật mỗi khi có đánh giá mới.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table): void {
            $table->bigIncrements('review_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_item_id')->unique();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();

            // Phản hồi của Shop; mỗi đánh giá có tối đa một phản hồi.
            $table->text('seller_reply')->nullable();
            $table->timestamp('seller_replied_at')->nullable();

            $table->timestamps();

            $table->index('product_id');

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
