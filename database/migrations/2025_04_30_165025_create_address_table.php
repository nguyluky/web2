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
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->string('phone_number');
            $table->string('email');
            $table->text('name');
            $table->string('street')->nullable();
            $table->string('ward')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            
            // No timestamps as per the model definition
            
            $table->foreign('profile_id')->references('id')->on('profile');
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
