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
        Schema::create('import_detail', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('import_id')->index('import_detail-import-import_id_idx');
            $table->integer('product_id')->index('import_detail-product-product_id_idx');
            $table->integer('import_price');
            $table->integer('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_detail');
    }
};
