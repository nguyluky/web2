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
        Schema::create('order_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement(); // Change to bigint
            $table->unsignedBigInteger('order_id'); // Change to bigint
            $table->unsignedBigInteger('product_variant_id'); // Change to bigint
            $table->unsignedBigInteger('serial');
            
            $table->foreign('order_id')->references('id')->on('order');
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
