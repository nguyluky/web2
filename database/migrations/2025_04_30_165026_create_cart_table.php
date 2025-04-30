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
        Schema::create('cart', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('amount');
            
            $table->primary(['profile_id', 'product_variant_id']);
            
            $table->foreign('profile_id')->references('id')->on('profile');
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
