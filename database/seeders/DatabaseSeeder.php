<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan seeder mengikuti dependensi:
     *
     * ResearchBudgetSeeder   — independen (data keuangan dari Excel, tidak bergantung tabel lain)
     * UserSeeder             — independen (hanya tabel users)
     * CoreStructureSeeder    — independen (Region → Garden → Afdeling → Block)
     * ProductionSeeder       — bergantung pada Garden
     * StrategicSeeder        — bergantung pada Garden
     * VisitCommunitySeeder   — bergantung pada Garden
     * InsightConfigSeeder    — independen (konfigurasi rule engine)
     */
    public function run(): void
    {
        $this->truncateAll();

        $this->call([
            ResearchBudgetSeeder::class,   // Step 1 — Data keuangan penelitian (independen)
            UserSeeder::class,             // Step 2 — Akun admin & manajemen
            CoreStructureSeeder::class,    // Step 3 — Region, Garden, Afdeling, Block
            ProductionSeeder::class,       // Step 4 — Realisasi produksi bulanan
            StrategicSeeder::class,        // Step 5 — Program, Strategic Action, Target Kinerja
            VisitCommunitySeeder::class,   // Step 6 — Kunjungan & Pengabdian Masyarakat
            InsightConfigSeeder::class,    // Step 7 — Konfigurasi threshold rule engine
        ]);
    }

    /**
     * Truncate semua tabel dengan aman (disable FK checks sementara).
     */
    private function truncateAll(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $tables = [
            'insight_configs',
            'visit_photos', 'visits',
            'community_services',
            'insights',
            'strategic_actions', 'performance_targets', 'programs',
            'production_realizations',
            'blocks', 'afdelings', 'garden_photos', 'gardens',
            'region_photos', 'regions',
            'pages',
            'research_budget_balances', 'research_budgets',
            'sessions', 'password_reset_tokens',
            'cache_locks', 'cache',
            'jobs', 'job_batches', 'failed_jobs',
            'users',
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
