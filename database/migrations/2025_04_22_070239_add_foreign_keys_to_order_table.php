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
        Schema::table('order', function (Blueprint $table) {
            $table->foreign(['account_id'], 'order-account-account_id')->references(['id'])->on('account')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['employee_id'], 'order-account-employee_id')->references(['id'])->on('account')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign('order-account-account_id');
            $table->dropForeign('order-account-employee_id');
        });
    }
};
