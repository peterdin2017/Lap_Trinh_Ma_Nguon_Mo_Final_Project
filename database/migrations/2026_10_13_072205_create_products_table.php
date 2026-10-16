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
     * Bảng products: sản phẩm do seller đăng bán.
     * Mỗi sản phẩm thuộc 1 shop (sellers) và 1 danh mục (categories).
     * status: active = đang bán, hidden = shop tự ẩn, locked = admin khoá.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');

            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');

            $table->string('product_name', 200);
            $table->text('description')->nullable();
            $table->string('brand', 100)->nullable();

            // Có phân loại hàng thì price = giá thấp nhất, stock = tổng tồn.
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('weight_gram')->nullable();

            // Ảnh bìa (đường dẫn tương đối, không lưu URL tuyệt đối).
            $table->string('image_url')->nullable();

            // Số liệu lưu sẵn để trang danh sách hiện nhanh.
            $table->unsignedInteger('sold_count')->default(0);
            $table->decimal('rating_avg', 2, 1)->default(0);
            $table->unsignedInteger('rating_count')->default(0);

            // active: đang bán | hidden: shop tự ẩn | locked: admin khoá
            $table->enum('status', ['active', 'hidden', 'locked'])
                ->default('active');
            $table->string('locked_reason')->nullable();
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();

            // RESTRICT: không cho xoá shop hoặc danh mục khi còn sản phẩm.
            $table->foreign('seller_id')
                ->references('seller_id')
                ->on('sellers')
                ->restrictOnDelete();

            $table->foreign('category_id')
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
        Schema::dropIfExists('products');
    }
};
