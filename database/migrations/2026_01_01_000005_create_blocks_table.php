<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('afdeling_id')->constrained('afdelings')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('area_ha', 10, 2)->default(0);
            $table->integer('population')->default(0);
            $table->string('plant_type')->nullable();
            $table->integer('planting_year')->nullable();
            $table->string('initial_class')->nullable();
            $table->string('topography')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('afdeling_id');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
