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
        Schema::table('rule_function', function (Blueprint $table) {
            $table->foreign(['function_id'], 'rule_function-function-function_id')->references(['id'])->on('function')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['rule_id'], 'rule_function-rule-rule_id')->references(['id'])->on('rule')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rule_function', function (Blueprint $table) {
            $table->dropForeign('rule_function-function-function_id');
            $table->dropForeign('rule_function-rule-rule_id');
        });
    }
};
