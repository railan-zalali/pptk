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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('afdeling_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->decimal('area_ha', 10, 2)->default(0);
            $table->integer('population')->default(0);
            $table->enum('plant_type', ['seedling', 'klon_gmb', 'klon_tri']);
            $table->year('planting_year');
            $table->enum('initial_class', ['A', 'B', 'C', 'D', 'E']);
            $table->enum('topography', ['datar', 'gelombang', 'curam']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
