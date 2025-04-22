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
        Schema::create('account', function (Blueprint $table) {
            $table->integer('id')->unique('id_unique');
            $table->string('username', 256)->unique('username_unique');
            $table->string('password', 256);
            $table->integer('rule')->nullable()->index('account-rule-rule_idx');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->unique('status_unique');
            $table->dateTime('created');
            $table->dateTime('updated');

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
