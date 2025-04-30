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
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('status');
            $table->timestamp('created_at');
            $table->unsignedBigInteger('employee_id');
            $table->string('payment_method');
            
            $table->foreign('account_id')->references('id')->on('account');
            $table->foreign('employee_id')->references('id')->on('account');
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
