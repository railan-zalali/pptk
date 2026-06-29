<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\Program;
use App\Models\StrategicAction;
use Illuminate\Database\Seeder;

class StrategicSeeder extends Seeder
{
    /**
     * Seed data program strategis, target kinerja, dan aksi strategis.
     *
     * Setiap kebun mendapatkan:
     *   - 1 PerformanceTarget (tahun 2025)
     *   - 6 StrategicAction (satu per jenis aksi)
     * Program dibuat global (tidak per kebun), lalu di-assign ke aksi.
     */
    public function run(): void
    {
        // ─── 1. Programs (Global) ───────────────────────────────────────────
        $programsData = [
            [
                'program_name' => 'Program Peningkatan Produktivitas Teh 2025',
                'description'  => 'Meningkatkan produktivitas teh melalui perbaikan budidaya, pemupukan berimbang, dan optimasi sistem petik.',
                'year'         => 2025,
                'program_type' => 'Model',
                'status'       => true,
            ],
            [
                'program_name' => 'Program Efisiensi Biaya Operasional',
                'description'  => 'Mengoptimalkan biaya operasional kebun melalui mekanisasi selektif dan manajemen tenaga kerja efisien.',
                'year'         => 2025,
                'program_type' => 'Pengembangan',
                'status'       => true,
            ],
            [
                'program_name' => 'Program Mekanisasi dan Modernisasi Pertanian',
                'description'  => 'Meningkatkan penggunaan mesin petik mekanis dan teknologi digital dalam monitoring kebun.',
                'year'         => 2025,
                'program_type' => 'Pengembangan',
                'status'       => true,
            ],
        ];

        $programs = [];
        foreach ($programsData as $pData) {
            $programs[] = Program::create($pData);
        }
        $programIds = array_column($programs, 'id');

        // ─── 2. Per-Garden: Target Kinerja & Strategic Action ───────────────
        $actionTypes = [
            'fertilizer_root' => [
                'dosis_n_kg_ha'          => 240,
                'realized_dosis_n_kg_ha' => 210,
                'n_protas_percent'       => 88,
                'application_frequency'  => 4,
                'fertilizer_type'        => 'Urea + NPK Phonska',
                'coverage_target_percent'=> 95,
                'realization_percent'    => null, // diisi dinamis
            ],
            'fertilizer_leaf' => [
                'coverage_target_percent' => 95,
                'application_interval'    => '14 hari',
                'realization_percent'     => null,
            ],
            'weed_control' => [
                'rotation_per_year'       => 6,
                'method'                  => 'Manual + Herbisida Selektif',
                'coverage_target_percent' => 100,
                'realization_percent'     => null,
            ],
            'cultivator' => [
                'rotation_per_year'       => 4,
                'focus_area'              => 'Blok dengan tanah kompak dan produktivitas rendah',
                'coverage_target_percent' => 100,
                'realization_percent'     => null,
            ],
            'picking' => [
                'picking_system'          => 'Campuran',
                'cushion_consistency'     => 'Sedang',
                'kandas_risk'             => false,
                'coverage_target_percent' => 100,
                'realization_percent'     => null,
            ],
            'machine' => [
                'total_machine'   => 55,
                'avg_machine_age' => 4.5,
                'renewal_status'  => 'Peremajaan bertahap setiap tahun',
                'realization_percent' => null,
            ],
        ];

        // Rentang realisasi realistis per jenis aksi (min%, max%)
        $realizationRanges = [
            'fertilizer_root' => [75, 95],
            'fertilizer_leaf' => [70, 92],
            'weed_control'    => [68, 90],
            'cultivator'      => [65, 88],
            'picking'         => [82, 97],
            'machine'         => [80, 100],
        ];

        $gardens = Garden::all();

        foreach ($gardens as $garden) {
            // Target Kinerja
            PerformanceTarget::create([
                'kebun_id'          => $garden->id,
                'year'              => 2025,
                'target_protas_min' => 82.50,
                'target_protas_max' => 95.00,
                'note'              => "Target disesuaikan kondisi iklim dan varietas di {$garden->kebun_name}.",
            ]);

            // Strategic Actions
            foreach ($actionTypes as $type => $baseData) {
                [$min, $max] = $realizationRanges[$type];
                $realization = round($min + (mt_rand() / mt_getrandmax()) * ($max - $min), 2);

                $data = array_merge($baseData, [
                    'realization_percent' => $realization,
                ]);

                StrategicAction::create(array_merge([
                    'kebun_id'   => $garden->id,
                    'program_id' => $programIds[array_rand($programIds)],
                    'year'       => 2025,
                    'action_type'=> $type,
                    'status'     => $realization >= 85 ? 'completed' : 'in_progress',
                    'note'       => "Strategi " . str_replace('_', ' ', $type) . " di {$garden->kebun_name}.",
                ], $data));
            }
        }

        $this->command->info(sprintf(
            'StrategicSeeder: %d program, %d target kinerja, %d strategic action berhasil di-seed.',
            Program::count(), PerformanceTarget::count(), StrategicAction::count()
        ));
    }
}
