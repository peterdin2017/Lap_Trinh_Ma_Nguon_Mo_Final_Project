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
        $table->id('otp_id');
        $table->foreignId('account_id')
            ->constrained('accounts', 'account_id')->onDelete('cascade');
        $table->string('otp_code', 6);
        $table->unsignedTinyInteger('attempts')->default(0);
        $table->enum('purpose', ['register', 'reset_password']);
        $table->dateTime('expires_at');
        $table->tinyInteger('is_used')->default(0);
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
