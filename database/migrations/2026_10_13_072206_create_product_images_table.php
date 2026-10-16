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
     * Bảng product_images: ảnh phụ của sản phẩm (nhiều ảnh cho 1 sản phẩm).
     * Ảnh bìa nằm ở products.image_url, bảng này chỉ chứa ảnh thêm.
     * sort_order là thứ tự hiển thị.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('image_id');
            $table->unsignedBigInteger('product_id');

            $table->string('image_url');

            // Thứ tự hiển thị, số nhỏ hiện trước.
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            // Xoá sản phẩm thì xoá luôn ảnh phụ.
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
        Schema::dropIfExists('product_images');
    }
};
