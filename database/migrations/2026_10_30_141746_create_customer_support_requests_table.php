<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_support_requests', function (Blueprint $table): void {
            $table->bigIncrements('request_id');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('requester_role', 20);
            $table->string('topic', 50);
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('admin_reply')->nullable();
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->nullOnDelete();

            $table->foreign('handled_by')
                ->references('account_id')
                ->on('accounts')
                ->nullOnDelete();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_support_requests');
    }
};
