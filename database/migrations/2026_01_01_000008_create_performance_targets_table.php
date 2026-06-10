<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebun_id')->constrained('gardens')->onDelete('cascade');
            $table->year('year');
            $table->decimal('target_protas_min', 10, 2)->comment('Target Protas Minimum (Kg/Ha/Tahun)');
            $table->decimal('target_protas_max', 10, 2)->nullable()->comment('Target Protas Maksimum (Kg/Ha/Tahun)');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Satu kebun hanya satu target per tahun
            $table->unique(['kebun_id', 'year'], 'unique_performance_target');
            $table->index(['kebun_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_targets');
    }
};
