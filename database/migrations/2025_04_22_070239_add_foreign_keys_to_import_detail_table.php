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
        Schema::table('import_detail', function (Blueprint $table) {
            $table->foreign(['import_id'], 'import_detail-import-import_id')->references(['id'])->on('import')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_id'], 'import_detail-product-product_id')->references(['id'])->on('product')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_detail', function (Blueprint $table) {
            $table->dropForeign('import_detail-import-import_id');
            $table->dropForeign('import_detail-product-product_id');
        });
    }
};
