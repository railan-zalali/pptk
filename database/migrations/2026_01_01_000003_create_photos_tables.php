<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('region_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->index('region_id');
        });

        Schema::create('garden_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->onDelete('cascade');
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->index('garden_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garden_photos');
        Schema::dropIfExists('region_photos');
    }
};
