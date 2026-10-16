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
     * Bảng product_reports: khách báo cáo sản phẩm vi phạm.
     * Admin xem báo cáo rồi khoá sản phẩm hoặc bỏ qua.
     * Mỗi người chỉ báo cáo 1 sản phẩm 1 lần.
     */
    public function up(): void
    {
        Schema::create('product_reports', function (Blueprint $table) {
            $table->bigIncrements('report_id');

            $table->unsignedBigInteger('product_id');

            // Người báo cáo.
            $table->unsignedBigInteger('account_id');

            // fake: hàng giả | prohibited: hàng cấm | wrong_info: sai thông tin
            // copyright: vi phạm bản quyền | other: lý do khác
            $table->enum('reason', [
                'fake',
                'prohibited',
                'wrong_info',
                'copyright',
                'other',
            ]);
            $table->text('description')->nullable();

            // pending: chờ xử lý | resolved: đã xử lý (thường là khoá sản phẩm)
            // dismissed: báo cáo không đúng, bỏ qua
            $table->enum('status', ['pending', 'resolved', 'dismissed'])
                ->default('pending');

            // Admin xử lý báo cáo.
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->string('admin_note')->nullable();
            $table->timestamps();

            // Mỗi người chỉ báo cáo 1 sản phẩm 1 lần.
            $table->unique(['product_id', 'account_id']);

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnDelete();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            // Xoá tài khoản admin thì chỉ để trống cột này, không mất báo cáo.
            $table->foreign('handled_by')
                ->references('account_id')
                ->on('accounts')
                ->nullOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reports');
    }
};
