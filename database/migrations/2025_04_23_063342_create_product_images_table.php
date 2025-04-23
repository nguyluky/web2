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
        Schema::create('product_images', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('product_id')->index('product_id');
            $table->integer('variant_id')->nullable()->index('variant_id');
            $table->string('image_url');
            $table->boolean('is_primary')->nullable()->default(false);
            $table->integer('sort_order')->nullable()->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
