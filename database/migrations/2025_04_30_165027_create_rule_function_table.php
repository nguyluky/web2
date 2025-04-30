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
        Schema::create('rule_function', function (Blueprint $table) {
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('function_id');
            $table->string('name')->nullable();
            
            $table->primary(['rule_id', 'function_id']);
            
            $table->foreign('rule_id')->references('id')->on('rule');
            $table->foreign('function_id')->references('id')->on('function');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_function');
    }
};
