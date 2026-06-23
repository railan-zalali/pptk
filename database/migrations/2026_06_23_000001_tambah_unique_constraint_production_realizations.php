<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan unique constraint pada production_realizations
     * untuk memastikan tidak ada duplikat data per kebun, bulan, dan tahun
     * di level database (bukan hanya validasi Laravel).
     */
    public function up(): void
    {
        Schema::table('production_realizations', function (Blueprint $table) {
            $table->unique(['kebun_id', 'month', 'year'], 'unique_kebun_month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_realizations', function (Blueprint $table) {
            $table->dropUnique('unique_kebun_month_year');
        });
    }
};
