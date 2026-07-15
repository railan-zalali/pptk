<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_realizations', function (Blueprint $table) {
            $table->decimal('dry_production_kg', 10, 2)->nullable()->after('wet_production_kg');
        });
    }

    public function down(): void
    {
        Schema::table('production_realizations', function (Blueprint $table) {
            $table->dropColumn('dry_production_kg');
        });
    }
};

