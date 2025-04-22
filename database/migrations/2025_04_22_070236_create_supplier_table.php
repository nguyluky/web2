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
        Schema::create('supplier', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 45);
            $table->string('tax', 45)->nullable()->unique('tax_unique');
            $table->string('contact_name', 45)->nullable();
            $table->string('phone_number', 45);
            $table->string('email', 45)->nullable()->unique('email_unique');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
