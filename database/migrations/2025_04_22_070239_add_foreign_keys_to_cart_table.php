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
        Schema::table('cart', function (Blueprint $table) {
            $table->foreign(['product_id'], 'cart-product-product_id')->references(['id'])->on('product')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['profile_id'], 'cart-profile-profile_id')->references(['id'])->on('profile')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign('cart-product-product_id');
            $table->dropForeign('cart-profile-profile_id');
        });
    }
};
