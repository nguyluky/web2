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
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            
            // No timestamps as per the model definition
            
            // Primary key of profile is also a foreign key to account
            $table->foreign('id')->references('id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile');
    }
};
