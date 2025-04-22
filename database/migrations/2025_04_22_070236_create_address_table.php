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
        Schema::create('address', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('profile_id')->index('address-profile-profile_id_idx');
            $table->string('phone_number', 45);
            $table->string('street', 45)->nullable();
            $table->string('ward', 45)->nullable();
            $table->string('district', 45)->nullable();
            $table->string('city', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
