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
        Schema::create('order_return_requests', function (Blueprint $table) {
            $table->bigIncrements('return_request_id');

            // Mỗi đơn chỉ gửi một yêu cầu trả hàng/hoàn tiền.
            $table->unsignedBigInteger('order_id')->unique();
            $table->unsignedBigInteger('account_id');

            $table->enum('request_type', ['return', 'refund']);
            $table->string('reason', 100);
            $table->text('description')->nullable();

            // Seller xử lý các trạng thái này ở bước tiếp theo.
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'completed',
            ])->default('pending');

            $table->text('seller_note')->nullable();
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->timestamp('handled_at')->nullable();

            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('handled_by')
                ->references('account_id')
                ->on('accounts')
                ->nullOnDelete();

            $table->index(['account_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_return_requests');
    }
};
