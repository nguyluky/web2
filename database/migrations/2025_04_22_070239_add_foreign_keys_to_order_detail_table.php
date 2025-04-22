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
        Schema::table('order_detail', function (Blueprint $table) {
            $table->foreign(['order_id'], 'order_detail-order-order_id')->references(['id'])->on('order')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_id'], 'order_detail-product-product_id')->references(['id'])->on('product')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropForeign('order_detail-order-order_id');
            $table->dropForeign('order_detail-product-product_id');
        });
    }
};
