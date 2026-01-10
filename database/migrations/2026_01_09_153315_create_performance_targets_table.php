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
        Schema::create('performance_targets', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->foreignId('afdeling_id')->constrained()->onDelete('cascade');
            $table->decimal('target_protas_kg_ha', 10, 2);
            $table->decimal('target_yield_kg', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_targets');
    }
};
