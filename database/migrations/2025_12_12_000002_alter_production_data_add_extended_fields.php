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
        Schema::table('production_data', function (Blueprint $table) {
            $table->decimal('production', 10, 2)->nullable()->after('record_date');
            $table->decimal('productivity', 10, 2)->nullable()->after('production');
            $table->string('weather_condition')->nullable()->after('quality_score');
            $table->integer('temperature_avg')->nullable()->after('weather_condition');
            $table->integer('rainfall_mm')->nullable()->after('temperature_avg');
            $table->integer('humidity_percent')->nullable()->after('rainfall_mm');
            $table->integer('soil_moisture_percent')->nullable()->after('humidity_percent');
            $table->integer('pest_incidence')->nullable()->after('soil_moisture_percent');
            $table->integer('disease_incidence')->nullable()->after('pest_incidence');
            $table->integer('fertilizer_used')->nullable()->after('disease_incidence');
            $table->integer('labor_hours')->nullable()->after('fertilizer_used');
            $table->text('notes')->nullable()->after('labor_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_data', function (Blueprint $table) {
            $table->dropColumn([
                'production',
                'productivity',
                'weather_condition',
                'temperature_avg',
                'rainfall_mm',
                'humidity_percent',
                'soil_moisture_percent',
                'pest_incidence',
                'disease_incidence',
                'fertilizer_used',
                'labor_hours',
                'notes',
            ]);
        });
    }
};
