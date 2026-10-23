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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('message_id');
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('sender_account_id');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('conversation_id')
                ->references('conversation_id')
                ->on('conversations')
                ->cascadeOnDelete();

            $table->foreign('sender_account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->index(['conversation_id', 'message_id']);
            $table->index(['conversation_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
