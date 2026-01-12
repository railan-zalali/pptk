<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Program;
use App\Models\StrategicAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Programs
        $programs = [
            [
                'program_name' => 'RKAP 2024',
                'year' => 2024,
                'program_type' => 'Model',
                'status' => false,
            ],
            [
                'program_name' => 'RKAP 2025',
                'year' => 2025,
                'program_type' => 'Pengembangan',
                'status' => true,
            ],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(
                ['year' => $prog['year'], 'program_name' => $prog['program_name']],
                $prog
            );
        }

        // 2. Get Gardens (created by PPTKSeeder)
        $gardens = Garden::all();

        if ($gardens->isEmpty()) {
            $this->command->info('No gardens found. Please run PPTKSeeder first.');
            return;
        }

        foreach ($gardens as $garden) {
            // 3. Create Performance Targets (2025)
            PerformanceTarget::updateOrCreate(
                ['kebun_id' => $garden->id, 'year' => 2025],
                [
                    'target_protas_min' => 2000,
                    'target_protas_max' => 2500,
                    'note' => 'Target peningkatan produktivitas 2025',
                ]
            );

            // 4. Create Strategic Actions (7 Types) for 2025
            $actionTypes = [
                'fertilizer_root' => [
                    'dosis_n_kg_ha' => 120,
                    'n_protas_percent' => 2.5,
                    'application_frequency' => 4,
                    'fertilizer_type' => 'Urea',
                    'technical_note' => 'Aplikasi saat tanah lembab',
                ],
                'fertilizer_leaf' => [
                    'coverage_target_percent' => 95,
                    'application_interval' => '2 Minggu',
                    'note' => 'Fokus pada pucuk peka',
                ],
                'weed_control' => [
                    'coverage_target_percent' => 100,
                    'rotation_per_year' => 6,
                    'method' => 'Manual & Kimia Terbatas',
                ],
                'cultivator' => [
                    'coverage_target_percent' => 20,
                    'focus_area' => 'Tanah Padat',
                    'method' => 'Garpu Tanah',
                ],
                'picking' => [
                    'picking_system' => 'Petik Murni',
                    'cushion_consistency' => 'Medium',
                    'kandas_risk' => false,
                    'note' => 'Jaga kualitas pucuk',
                ],
                'machine' => [
                    'total_machine' => 15,
                    'avg_machine_age' => 3.5,
                    'renewal_status' => 'Good',
                    'note' => 'Perawatan rutin tiap bulan',
                ],
                'opt' => [
                    'opt_status' => 'Terkendali',
                    'tp_normalization' => true,
                    'treatment_note' => 'Monitoring Helopeltis',
                ],
            ];

            foreach ($actionTypes as $type => $data) {
                StrategicAction::updateOrCreate(
                    [
                        'kebun_id' => $garden->id,
                        'year' => 2025,
                        'action_type' => $type
                    ],
                    $data
                );
            }

            // 5. Create Production Realization (Jan - Dec 2025)
            // Some past months with data, future months maybe empty or forecasted
            for ($month = 1; $month <= 12; $month++) {
                // Skip future months if needed, but for dummy data we can fill up to current month or all year
                // Let's fill Jan-Oct 2025 as realized, Nov-Dec as forecast only?
                // Or just fill all for demo purposes.

                $isRealized = $month <= 10;

                ProductionRealization::updateOrCreate(
                    [
                        'kebun_id' => $garden->id,
                        'year' => 2025,
                        'month' => $month
                    ],
                    [
                        // 6.1 Luasan Efektif
                        'active_picking_area_ha' => $garden->luas_total_ha ?? 100,

                        // 6.2 Produksi Basah (Random variation)
                        'wet_production_kg' => $isRealized ? rand(15000, 25000) : null,

                        // 6.3 Kapasitas Pemetikan
                        'capacity_per_ha' => $isRealized ? rand(15, 25) : null,
                        'avg_capacity' => $isRealized ? rand(30, 45) : null,

                        // 6.4 Forecast Produksi (Always present usually)
                        'estimated_production' => rand(18000, 22000),
                        'assumption_note' => 'Asumsi cuaca normal',
                    ]
                );
            }
        }
    }
}
