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
        Schema::table('pph_types', function (Blueprint $table) {
            $table->boolean('is_gross_up')
                ->default(false)
                ->after('tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pph_types', function (Blueprint $table) {
            $table->dropColumn('is_gross_up');
        });
    }
};
