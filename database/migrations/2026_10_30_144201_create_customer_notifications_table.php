<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_notifications', function (Blueprint $table): void {
            $table->bigIncrements('notification_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('support_request_id')->nullable();
            $table->string('title', 150);
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('support_request_id')
                ->references('request_id')
                ->on('customer_support_requests')
                ->nullOnDelete();

            $table->index(['account_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
    }
};
