<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clean up first
        if (DB::connection()->getDriverName() === 'sqlite') {
             DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
             DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        $tables = [
            'visit_photos', 'visits', 'insights', 
            'strategic_actions', 'production_realizations', 'performance_targets',
            'blocks', 'afdelings', 'garden_photos', 'gardens', 
            'region_photos', 'regions', 'pages', 'programs', 
            'community_services', 'sessions', 'password_reset_tokens', 
            'cache_locks', 'cache', 'jobs', 'job_batches', 'failed_jobs', 'users'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Run Seeders in Order
        $this->call([
            UserSeeder::class,
            CoreStructureSeeder::class, // Regions, Gardens, Afdelings, Blocks
            ProductionSeeder::class,    // Production Data & Realizations
            StrategicSeeder::class,     // Strategic Actions, Programs, Targets
            ResearchSeeder::class,      // Visits, Community Services
        ]);
    }
}
