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
        Schema::dropIfExists('production_data');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('production_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained();
            $table->date('record_date');
            $table->decimal('productivity_kg_ha_year', 10, 2)->nullable();
            $table->decimal('rkap_percentage', 5, 2)->nullable();
            $table->decimal('wet_production_kg', 10, 2)->nullable();
            $table->decimal('quality_score', 3, 1)->nullable();
            $table->string('month', 20)->nullable();
            $table->string('year', 4)->nullable();
            $table->timestamps();
            
            $table->index(['garden_id', 'record_date']);
            $table->index(['month', 'year']);
        });
    }
};
