<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gardens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('regions')->onDelete('restrict');
            $table->string('kebun_name');
            $table->decimal('luas_total_ha', 10, 2);
            $table->enum('kebun_type', ['Model', 'Pengembangan']);
            $table->text('agro_climate_note')->nullable();
            $table->string('location')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('description')->nullable();
            $table->date('established_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('regional_id');
            $table->index('kebun_name');
            $table->index('kebun_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gardens');
    }
};
