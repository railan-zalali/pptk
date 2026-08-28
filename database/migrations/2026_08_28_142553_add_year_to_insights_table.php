<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom year jika belum ada (idempotent)
        if (!Schema::hasColumn('insights', 'year')) {
            Schema::table('insights', function (Blueprint $table) {
                $table->unsignedSmallInteger('year')->nullable()->after('garden_id');
            });
        }

        // Tambah unique constraint jika belum ada
        $indexExists = collect(\Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM insights WHERE Key_name = 'insights_garden_type_year_unique'"
        ))->isNotEmpty();

        if (!$indexExists) {
            Schema::table('insights', function (Blueprint $table) {
                $table->unique(['garden_id', 'insight_type', 'year'], 'insights_garden_type_year_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('insights', function (Blueprint $table) {
            $table->dropUnique('insights_garden_type_year_unique');
            $table->dropColumn('year');
        });
    }
};
