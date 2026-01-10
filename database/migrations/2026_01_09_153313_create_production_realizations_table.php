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
            $table->foreignId('afdeling_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('harvested_area_ha', 10, 2)->comment('Luas petik realisasi');
            $table->decimal('wet_yield_kg', 10, 2)->comment('Produksi basah');
            $table->decimal('dry_yield_kg', 10, 2)->comment('Kalkulasi rendemen');
            $table->integer('manpower_count')->comment('Jumlah HK pemetik');
            $table->integer('effective_days');
            $table->timestamps();
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
