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
            $table->id();
            $table->string('name');
            $table->string('tax')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('status');
            $table->timestamp('created_at');
            // No timestamps in model definition, but has created_at
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
