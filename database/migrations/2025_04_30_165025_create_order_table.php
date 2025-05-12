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
        Schema::create('order', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement(); // Change to bigint
            $table->unsignedBigInteger('profile_id');
            $table->string('status');
            $table->unsignedBigInteger('payment_method');
            $table->unsignedBigInteger('address_id');
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profile');
            // $table->foreign('payment_method')->references('id')->on('payment');
            $table->foreign('address_id')->references('id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
