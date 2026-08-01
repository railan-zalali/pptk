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
        Schema::create('production_realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebun_id')->constrained('gardens')->onDelete('cascade');
            $table->integer('month');
            $table->year('year');

            // 6.1 Luasan Efektif
            $table->decimal('active_picking_area_ha', 10, 2)->nullable();

            // 6.2 Produksi Basah & Kering
            $table->decimal('wet_production_kg', 10, 2)->nullable();
            $table->decimal('dry_production_kg', 10, 2)->nullable();
            $table->decimal('quality_score', 5, 2)->nullable();

            // 6.3 Kapasitas Pemetikan
            $table->decimal('capacity_per_ha', 10, 2)->nullable();
            $table->decimal('avg_capacity', 10, 2)->nullable();

            // 6.4 Forecast Produksi
            $table->decimal('estimated_production', 10, 2)->nullable();
            $table->text('assumption_note')->nullable();

            $table->timestamps();

            // Unique: satu record per kebun, bulan, tahun
            $table->unique(['kebun_id', 'month', 'year'], 'unique_kebun_month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_realizations');
    }
};
