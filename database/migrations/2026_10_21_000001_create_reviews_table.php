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
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('product_id');

            // unique: mỗi dòng hàng chỉ được đánh giá một lần.
            // Chưa đặt foreign key vì bảng order_items do Người 2 tạo;
            // sau khi gộp nhánh hãy thêm FK -> order_items.order_item_id.
            $table->unsignedBigInteger('order_item_id')->unique();

            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();
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
