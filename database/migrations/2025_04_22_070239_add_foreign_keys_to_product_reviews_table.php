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
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreign(['product_id'], 'product_reviews_ibfk_1')->references(['id'])->on('product')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'product_reviews_ibfk_2')->references(['id'])->on('account')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign('product_reviews_ibfk_1');
            $table->dropForeign('product_reviews_ibfk_2');
        });
    }
};
