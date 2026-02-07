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
        // Fix Programs Table
        if (Schema::hasTable('programs')) {
            Schema::table('programs', function (Blueprint $table) {
                if (!Schema::hasColumn('programs', 'description')) {
                    $table->text('description')->nullable()->after('program_name');
                }
            });
        }

        // Fix Blocks Table
        if (Schema::hasTable('blocks')) {
            Schema::table('blocks', function (Blueprint $table) {
                if (!Schema::hasColumn('blocks', 'area_ha')) {
                    $table->decimal('area_ha', 10, 2)->default(0)->after('code');
                }
                if (!Schema::hasColumn('blocks', 'population')) {
                    $table->integer('population')->default(0)->after('area_ha');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['description']);
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn(['area_ha', 'population']);
        });
    }
};
