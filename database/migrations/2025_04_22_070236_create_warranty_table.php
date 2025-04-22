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
        Schema::create('warranty', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('product_id')->index('warranty-order_detail-product_id_idx');
            $table->integer('supplier_id')->index('warranty-supplier-supplier_id_idx');
            $table->string('issue_date', 45)->nullable();
            $table->string('expiration_date', 45)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'In Repair', 'Replaced', 'Completed', 'Cancelled'])->nullable()->default('Pending');
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty');
    }
};
