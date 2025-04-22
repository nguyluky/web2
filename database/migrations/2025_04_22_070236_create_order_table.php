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
        Schema::create('order', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('account_id')->index('account_id_2');
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled', 'returned', 'failed']);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('employee_id')->index('order-account-employee_id_idx');
            $table->enum('payment_method', ['cash', 'banking', 'credit_card', 'e_wallet']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
