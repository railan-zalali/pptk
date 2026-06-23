<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\Program;
use App\Models\StrategicAction;
use App\Models\PerformanceTarget;
use Illuminate\Database\Seeder;

class StrategicSeeder extends Seeder
{
    public function run(): void
    {
        $gardens = Garden::all();
        $programs = [
            'Program Peningkatan Produktivitas',
            'Program Efisiensi Biaya',
            'Program Mekanisasi Pertanian',
        ];

        $programIds = [];
        foreach ($programs as $pName) {
            $prog = Program::create([
                'program_name' => $pName,
                'year' => 2026,
                'program_type' => 'Model',
            ]);
            $programIds[] = $prog->id;
        }

        foreach ($gardens as $garden) {
            $year = 2026;

            // 1. Performance Target
            PerformanceTarget::create([
                'kebun_id' => $garden->id,
                'year' => $year,
                'target_protas_min' => 85.00,
                'target_protas_max' => 95.00,
                'note' => 'Target disesuaikan dengan kondisi iklim tahun ini.',
            ]);

            // 2. Strategic Actions
            $actions = [
                'fertilizer_root' => [
                    'dosis_n_kg_ha'         => 250,
                    'realized_dosis_n_kg_ha'=> 220,
                    'n_protas_percent'      => 90,
                    'application_frequency' => 4,
                    'fertilizer_type'       => 'Urea & NPK',
                    'coverage_target_percent'  => 95,
                    'realization_percent'   => 85,
                ],
                'fertilizer_leaf' => [
                    'coverage_target_percent' => 95,
                    'realization_percent'     => 88,
                    'application_interval'   => '14 hari',
                ],
                'weed_control' => [
                    'rotation_per_year'    => 6,
                    'method'               => 'Manual & Kimia Terbatas',
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 75,
                ],
                'cultivator' => [
                    'rotation_per_year'    => 4,
                    'focus_area'           => 'Blok Tanaman Menghasilkan',
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 70,
                ],
                'picking' => [
                    'picking_system'       => 'Mekanisasi',
                    'cushion_consistency'  => 'Medium',
                    'kandas_risk'          => false,
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 92,
                ],
                'machine' => [
                    'total_machine'        => 50,
                    'avg_machine_age'      => 3.5,
                    'renewal_status'       => 'Peremajaan Bertahap',
                ],
                'opt' => [
                    'opt_status'           => 'Terkendali',
                    'tp_normalization'     => true,
                    'treatment_note'       => 'Fokus pada Helopeltis',
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 80,
                ],
            ];

            foreach ($actions as $type => $data) {
                StrategicAction::create(array_merge([
                    'kebun_id' => $garden->id,
                    'program_id' => $programIds[array_rand($programIds)],
                    'year' => $year,
                    'action_type' => $type,
                    'note' => 'Strategi optimalisasi ' . str_replace('_', ' ', $type),
                ], $data));
            }
        }
    }
}
