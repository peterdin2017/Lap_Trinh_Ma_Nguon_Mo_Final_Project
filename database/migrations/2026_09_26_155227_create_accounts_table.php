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
        Schema::create('accounts', function (Blueprint $table) {
        $table->id('account_id');
        $table->string('email', 100)->unique();
        $table->string('password_hash');
        $table->enum('role', ['user', 'admin'])->default('user');
        $table->enum('status', ['active', 'locked'])->default('active');
        $table->tinyInteger('is_verified')->default(0);
        $table->timestamp('create_at')->useCurrent();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
