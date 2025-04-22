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
        Schema::table('warranty', function (Blueprint $table) {
            $table->foreign(['product_id'], 'warranty-order_detail-product_id')->references(['id'])->on('order_detail')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['supplier_id'], 'warranty-supplier-supplier_id')->references(['id'])->on('supplier')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warranty', function (Blueprint $table) {
            $table->dropForeign('warranty-order_detail-product_id');
            $table->dropForeign('warranty-supplier-supplier_id');
        });
    }
};
