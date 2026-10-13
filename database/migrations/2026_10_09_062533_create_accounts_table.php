<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('account_id');

            // Một email có thể dùng cho nhiều role khác nhau.
            $table->string('email', 100);

            // Khớp với Account model và RegisterController.
            $table->string('password_hash');

            $table->enum('role', ['user', 'seller', 'admin'])
                ->default('user');

            $table->enum('status', ['active', 'locked'])
                ->default('active');

            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();

            // Chặn trùng email trong cùng role,
            // nhưng cho phép cùng email ở user, seller và admin.
            $table->unique(
                ['email', 'role'],
                'accounts_email_role_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
