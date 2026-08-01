<?php

namespace Database\Seeders;

use App\Models\ResearchBudgetActivity;
use Illuminate\Database\Seeder;

/**
 * Seed daftar kegiatan penelitian ke tabel research_budget_activities.
 * Data ini identik dengan data yang di-insert oleh migration
 * 2026_07_20_000001_buat_tabel_research_budget_activities,
 * sehingga seeder bisa dijalankan ulang (misal: fresh seed saat testing)
 * tanpa kehilangan data.
 */
class ResearchBudgetActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            'Kina', 'KUT', 'Malabar/Sedep', 'Drone', 'GWP',
            'IPM Helopeltis', 'Bio Kompos', 'Daur Petik',
            'Inkubasi Riset', 'Inkubasi Booster',
        ];

        foreach ($activities as $i => $name) {
            ResearchBudgetActivity::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $i]
            );
        }
    }
}
