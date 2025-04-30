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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('base_original_price', 10, 2)->nullable();
            $table->string('status')->nullable();
            $table->json('specifications')->nullable();
            $table->json('features')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
