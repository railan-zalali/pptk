<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel untuk menyimpan data realisasi anggaran penelitian per kegiatan per bulan.
     * Struktur mengikuti format Excel "Realisasi Anggaran Penelitian 2025":
     * - Setiap kegiatan memiliki saldo_awal (opening balance)
     * - Income (pencairan dana) per bulan
     * - Pengeluaran per bulan
     * - Sisa anggaran dihitung otomatis: saldo_awal + total_income - total_pengeluaran
     */
    public function up(): void
    {
        Schema::create('research_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');          // Nama kegiatan: Kina, KUT, Malabar/Sedep, dll
            $table->year('year');                       // Tahun anggaran
            $table->integer('month');                   // Bulan (1-12)
            $table->decimal('income', 15, 2)->default(0);        // Pencairan dana (masuk)
            $table->decimal('expenditure', 15, 2)->default(0);   // Pengeluaran (keluar)
            $table->text('notes')->nullable();          // Catatan opsional
            $table->timestamps();

            // Unique constraint: satu record per kegiatan per bulan per tahun
            $table->unique(['activity_name', 'year', 'month'], 'research_budget_unique');
        });

        // Tabel terpisah untuk saldo awal per kegiatan per tahun
        Schema::create('research_budget_balances', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->year('year');
            $table->decimal('opening_balance', 15, 2)->default(0); // Saldo awal tahun
            $table->timestamps();

            $table->unique(['activity_name', 'year'], 'research_balance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_budget_balances');
        Schema::dropIfExists('research_budgets');
    }
};
