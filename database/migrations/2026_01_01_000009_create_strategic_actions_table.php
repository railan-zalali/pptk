<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebun_id')->constrained('gardens')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->year('year');
            $table->date('realization_date')->nullable();

            // Tipe aksi: fertilizer_root, fertilizer_leaf, weed_control, cultivator, picking, machine, opt
            $table->string('action_type');
            $table->string('status')->default('planned'); // planned, in_progress, completed

            // 5.1 Pemupukan Akar (Root Fertilizer)
            $table->decimal('dosis_n_kg_ha', 10, 2)->nullable()->comment('Target Dosis N (Kg/Ha)');
            $table->decimal('realized_dosis_n_kg_ha', 10, 2)->nullable()->comment('Realisasi Dosis N (Kg/Ha)');
            $table->decimal('n_protas_percent', 10, 2)->nullable()->comment('% N terhadap Protas');
            $table->integer('application_frequency')->nullable();
            $table->string('fertilizer_type')->nullable();
            $table->text('technical_note')->nullable();

            // 5.2–5.4 Cakupan & Realisasi Area (Leaf Fertilizer, Weed Control, Cultivator)
            $table->decimal('coverage_target_percent', 5, 2)->nullable()->comment('Target Cakupan (%)');
            $table->decimal('realization_percent', 5, 2)->nullable()->comment('Realisasi (%)');
            $table->string('application_interval')->nullable();

            // 5.3 Penyiangan & 5.4 Kultivator
            $table->integer('rotation_per_year')->nullable();
            $table->string('method')->nullable();
            $table->string('focus_area')->nullable();

            // 5.5 Pemetikan
            $table->string('picking_system')->nullable();
            $table->string('cushion_consistency')->nullable();
            $table->boolean('kandas_risk')->nullable();

            // 5.6 Mesin Petik
            $table->integer('total_machine')->nullable();
            $table->decimal('avg_machine_age', 10, 2)->nullable();
            $table->string('renewal_status')->nullable();

            // 5.7 Pengendalian OPT
            $table->string('opt_status')->nullable();
            $table->boolean('tp_normalization')->nullable();
            $table->text('treatment_note')->nullable();

            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['kebun_id', 'year', 'action_type']);
            $table->index(['year', 'action_type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_actions');
    }
};
