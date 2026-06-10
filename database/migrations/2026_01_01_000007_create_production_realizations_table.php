<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebun_id')->constrained('gardens')->onDelete('cascade');
            $table->unsignedTinyInteger('month'); // 1-12
            $table->year('year');

            // 6.1 Luasan Efektif TM (Tanaman Menghasilkan)
            $table->decimal('active_picking_area_ha', 10, 2)->nullable();

            // 6.2 Produksi Basah
            $table->decimal('wet_production_kg', 12, 2)->nullable();

            // 6.3 Kapasitas Pemetikan
            $table->decimal('capacity_per_ha', 10, 2)->nullable();
            $table->decimal('avg_capacity', 10, 2)->nullable();

            // 6.4 Forecast / RKAP
            $table->decimal('estimated_production', 12, 2)->nullable();
            $table->text('assumption_note')->nullable();

            // 6.5 Mutu Pucuk (Quality Score: 0-100)
            $table->decimal('quality_score', 5, 2)->nullable();

            $table->timestamps();

            // Satu kebun hanya boleh punya satu record per bulan per tahun
            $table->unique(['kebun_id', 'year', 'month'], 'unique_production_period');
            $table->index(['kebun_id', 'year']);
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_realizations');
    }
};
