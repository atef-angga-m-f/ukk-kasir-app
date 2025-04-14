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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice');
            $table->foreignId('member_id')->nullable()->constrained('members');
            $table->foreignId('kasir_id')->constrained('users');
            $table->json('products');
            $table->integer('payment_amount');
            $table->integer('total_amount');
            $table->integer('return_amount');
            $table->integer('discount_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
