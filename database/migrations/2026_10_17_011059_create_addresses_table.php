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
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('address_id');
            $table->unsignedBigInteger('account_id');

            $table->string('recipient_name', 100);
            $table->string('phone', 15);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('street_address', 255);

            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['account_id', 'is_default']);

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
        Schema::dropIfExists('addresses');
    }
};
