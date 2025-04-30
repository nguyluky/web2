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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->string('status')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('user_id')->references('id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
