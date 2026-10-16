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
     * Bảng categories: danh mục sản phẩm (VD: Thời trang, Điện thoại).
     * parent_id trỏ về chính bảng này để tạo danh mục cha/con.
     * parent_id = NULL nghĩa là danh mục cấp 1.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('category_id');

            // Danh mục cha. NULL = danh mục cấp 1 (không có cha).
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('category_name', 100);
            $table->timestamps();

            // Trỏ về chính bảng categories. Không cho xoá danh mục cha khi còn con.
            $table->foreign('parent_id')
                ->references('category_id')
                ->on('categories')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
