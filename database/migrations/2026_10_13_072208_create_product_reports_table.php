<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reports', function (Blueprint $table) {
            $table->bigIncrements('report_id');

            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('account_id');

            $table->enum('reason', [
                'fake',
                'prohibited',
                'wrong_info',
                'copyright',
                'other',
            ]);

            $table->text('description')->nullable();

            $table->enum('status', [
                'pending',
                'resolved',
                'dismissed',
            ])->default('pending');

            $table->unsignedBigInteger('handled_by')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->string('admin_note')->nullable();

            $table->timestamps();

            // Mỗi tài khoản chỉ báo cáo một sản phẩm một lần.
            $table->unique(['product_id', 'account_id']);

            // Hỗ trợ trang admin lọc báo cáo mới/chờ xử lý.
            $table->index(['status', 'created_at']);

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnDelete();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('handled_by')
                ->references('account_id')
                ->on('accounts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reports');
    }
};
