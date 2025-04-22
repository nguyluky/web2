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
            $table->integer('id')->primary();
            $table->string('sku', 50)->nullable()->unique('sku');
            $table->string('name');
            $table->string('slug')->unique('slug');
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable()->index('category_id');
            $table->decimal('base_price', 15);
            $table->decimal('base_original_price', 15)->nullable();
            $table->string('status', 20)->nullable()->default('active');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->json('specifications')->nullable();
            $table->json('features')->nullable();
            $table->json('meta_data')->nullable();
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
