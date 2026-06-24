<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel konfigurasi threshold untuk Rule-Based Insight Engine.
     * Memungkinkan admin mengubah ambang batas analisis tanpa mengubah kode.
     */
    public function up(): void
    {
        Schema::create('insight_configs', function (Blueprint $table) {
            $table->id();

            // Tipe insight: 'productivity', 'quality', 'strategic_fertilizer_root', dst.
            $table->string('insight_type', 100);

            // Kunci aturan: 'threshold_low', 'threshold_medium', 'threshold_high', dsb.
            $table->string('rule_key', 100);

            // Nilai ambang batas (numeric)
            $table->decimal('rule_value', 12, 4);

            // Keterangan untuk UI admin
            $table->string('description')->nullable();
            $table->string('unit', 50)->nullable(); // 'kg/ha', '%', 'tahun', dll.

            $table->timestamps();

            // Kombinasi unik agar tidak duplikat
            $table->unique(['insight_type', 'rule_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insight_configs');
    }
};
