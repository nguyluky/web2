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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('product_id')->index('product_id');
            $table->string('sku', 50)->nullable()->unique('sku');
            $table->decimal('price', 15);
            $table->decimal('original_price', 15)->nullable();
            $table->integer('stock')->nullable()->default(0);
            $table->string('status', 20)->nullable()->default('active');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->json('attributes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
