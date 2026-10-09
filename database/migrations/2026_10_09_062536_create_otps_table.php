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
        Schema::create('otps', function (Blueprint $table) {
            $table->bigIncrements('otp_id');
            $table->unsignedBigInteger('account_id');
            $table->enum('account_type', ['user', 'seller']);
            $table->string('email', 100);
            $table->string('otp_hash');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->enum('purpose', ['register', 'reset_password']);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['account_type', 'email', 'purpose']);

            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
