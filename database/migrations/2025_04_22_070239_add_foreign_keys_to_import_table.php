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
        Schema::table('import', function (Blueprint $table) {
            $table->foreign(['suppiler_id'], 'import-supplier-supplier_id')->references(['id'])->on('supplier')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import', function (Blueprint $table) {
            $table->dropForeign('import-supplier-supplier_id');
        });
    }
};
