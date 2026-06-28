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
            [
                'program_name' => 'Program Peningkatan Produktivitas Teh 2025',
                'description' => 'Program untuk meningkatkan produktivitas teh melalui perbaikan budidaya',
                'year' => 2025,
                'program_type' => 'Model',
                'status' => true,
            ],
            [
                'program_name' => 'Program Efisiensi Biaya Operasional',
                'description' => 'Program untuk mengoptimalkan biaya operasional kebun',
                'year' => 2025,
                'program_type' => 'Pengembangan',
                'status' => true,
            ],
            [
                'program_name' => 'Program Mekanisasi Pertanian',
                'description' => 'Program untuk meningkatkan penggunaan mesin dalam budidaya teh',
                'year' => 2025,
                'program_type' => 'Pengembangan',
                'status' => true,
            ],
        ];

        $programIds = [];
        foreach ($programs as $pData) {
            $prog = Program::create($pData);
            $programIds[] = $prog->id;
        }

        foreach ($gardens as $garden) {
            $year = 2025;

            // 1. Performance Target
            PerformanceTarget::create([
                'kebun_id' => $garden->id,
                'year' => $year,
                'target_protas_min' => 82.50,
                'target_protas_max' => 95.00,
                'note' => 'Target disesuaikan dengan kondisi iklim, lahan, dan varietas di kebun ' . $garden->kebun_name,
            ]);

            // 2. Strategic Actions with randomized realistic values
            $actions = [
                'fertilizer_root' => [
                    'dosis_n_kg_ha'         => 240 + (mt_rand() / mt_getrandmax()) * 20,
                    'realized_dosis_n_kg_ha'=> 200 + (mt_rand() / mt_getrandmax()) * 40,
                    'n_protas_percent'      => 85 + (mt_rand() / mt_getrandmax()) * 10,
                    'application_frequency' => 4,
                    'fertilizer_type'       => 'Urea + NPK Phonska',
                    'coverage_target_percent'  => 95,
                    'realization_percent'   => 80 + (mt_rand() / mt_getrandmax()) * 15,
                ],
                'fertilizer_leaf' => [
                    'coverage_target_percent' => 95,
                    'realization_percent'     => 75 + (mt_rand() / mt_getrandmax()) * 20,
                    'application_interval'   => '14 hari',
                ],
                'weed_control' => [
                    'rotation_per_year'    => 6,
                    'method'               => 'Manual + Herbisida Selektif',
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 70 + (mt_rand() / mt_getrandmax()) * 25,
                ],
                'cultivator' => [
                    'rotation_per_year'    => 4,
                    'focus_area'           => 'Blok dengan tanah kompak dan produktivitas rendah',
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 65 + (mt_rand() / mt_getrandmax()) * 25,
                ],
                'picking' => [
                    'picking_system'       => ['Mekanis', 'Manual', 'Campuran'][array_rand(['Mekanis', 'Manual', 'Campuran'])],
                    'cushion_consistency'  => ['Rendah', 'Sedang', 'Tinggi'][array_rand(['Rendah', 'Sedang', 'Tinggi'])],
                    'kandas_risk'          => (bool) rand(0,1),
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 85 + (mt_rand() / mt_getrandmax()) * 12,
                ],
                'machine' => [
                    'total_machine'        => 40 + (mt_rand() / mt_getrandmax()) * 30,
                    'avg_machine_age'      => 2.5 + (mt_rand() / mt_getrandmax()) * 4,
                    'renewal_status'       => 'Peremajaan bertahap setiap tahun',
                ],
                'opt' => [
                    'opt_status'           => 'Terkendali',
                    'tp_normalization'     => true,
                    'treatment_note'       => 'Fokus pengendalian hama utama: Helopeltis, ulat, dan tungau',
                    'coverage_target_percent' => 100,
                    'realization_percent'  => 75 + (mt_rand() / mt_getrandmax()) * 20,
                ],
            ];

            foreach ($actions as $type => $data) {
                StrategicAction::create(array_merge([
                    'kebun_id' => $garden->id,
                    'program_id' => $programIds[array_rand($programIds)],
                    'year' => $year,
                    'action_type' => $type,
                    'status' => 'completed',
                    'note' => 'Strategi optimalisasi ' . str_replace('_', ' ', $type) . ' di kebun ' . $garden->kebun_name,
                ], $data));
            }
        }
        
        $this->command->info('StrategicSeeder: Data program, target kinerja, dan aksi strategis berhasil di-seed.');
    }
}
