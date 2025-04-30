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
        Schema::create('import', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suppiler_id'); // Keeping the typo as in the model
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->nullable();
            
            $table->foreign('suppiler_id')->references('id')->on('supplier');
            $table->foreign('employee_id')->references('id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import');
    }
};
