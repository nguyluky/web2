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
            $table->integer('rule_id');
            $table->integer('function_id')->index('rule_function-function-function_id_idx');
            $table->string('name', 45)->nullable();

            $table->primary(['rule_id', 'function_id']);
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
