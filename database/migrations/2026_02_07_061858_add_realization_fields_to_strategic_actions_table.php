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
        Schema::table('strategic_actions', function (Blueprint $table) {
            $table->decimal('realization_percent', 5, 2)->nullable()->after('coverage_target_percent');
            $table->decimal('realized_dosis_n_kg_ha', 10, 2)->nullable()->after('dosis_n_kg_ha');
            $table->date('realization_date')->nullable()->after('year');
            $table->string('status')->default('planned')->after('action_type'); // planned, in_progress, completed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strategic_actions', function (Blueprint $table) {
            $table->dropColumn(['realization_percent', 'realized_dosis_n_kg_ha', 'realization_date', 'status']);
        });
    }
};
