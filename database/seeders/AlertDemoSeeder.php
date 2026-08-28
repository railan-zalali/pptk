<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Program;
use App\Models\StrategicAction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * AlertDemoSeeder — Data demonstrasi tiga level alert rule engine.
 *
 * Strategi: 6 kebun dipetakan ke 3 kelompok alert (2 kebun per level).
 * Setiap kelompok memiliki nilai produksi & aksi strategis yang
 * SECARA DETERMINISTIK meng-trigger level alert tertentu.
 *
 * ┌────────────────────┬────────────┬───────────────────────────────────┐
 * │ Kebun              │ Alert      │ Alasan                            │
 * ├────────────────────┼────────────┼───────────────────────────────────┤
 * │ Pagaralam          │ HIGH       │ Protas 750 kg/ha (< 1000)         │
 * │ Kaligua            │ HIGH       │ Protas 880 kg/ha (< 1000)         │
 * │ Sedep              │ MEDIUM     │ Protas 1150 kg/ha (1000–1300)     │
 * │ Gambung            │ MEDIUM     │ Protas 1250 kg/ha (1000–1300)     │
 * │ Malabar            │ LOW        │ Protas 1650 kg/ha (≥ 1300)        │
 * │ Rancabali          │ LOW        │ Protas 2100 kg/ha (≥ 1300)        │
 * └────────────────────┴────────────┴───────────────────────────────────┘
 *
 * Thresholds (sesuai InsightConfig defaults):
 *   Productivity   : HIGH < 1000 | MEDIUM 1000-1300 | LOW >= 1300 kg/ha
 *   Productivity Dry: HIGH < 220 | MEDIUM 220-286   | LOW >= 286  kg/ha
 *   Quality        : HIGH < 7.0  | MEDIUM 7.0-8.5   | LOW > 8.5  skor
 *   Fertilizer Root: HIGH < 70%  | MEDIUM 70-85%    | LOW >= 85%
 *   Coverage       : HIGH < 60%  | MEDIUM 60-80%    | LOW >= 80%
 *   Machine Age    : HIGH >= 8yr | MEDIUM 5-8yr     | LOW < 5yr
 *   OPT            : HIGH = 'tidak terkendali/berat/parah' | MEDIUM = 'sedang' | LOW = terkendali
 *   Picking        : HIGH = kandas_risk=true | MEDIUM = realisasi < 80% | LOW = normal
 *
 * CATATAN: Seeder ini MENGGANTIKAN data 2026 yang ada.
 * Jalankan SETELAH db:seed utama selesai, atau gunakan --class=AlertDemoSeeder.
 */
class AlertDemoSeeder extends Seeder
{
    // Konfigurasi alert per kebun — semua nilai dipilih agar PASTI trigger level yang tepat
    private array $alertConfig = [
        // ── HIGH ALERT ─────────────────────────────────────────────────────
        'Kebun Teh Pagaralam' => [
            'alert'              => 'HIGH',
            // Productivity: 750 kg/ha basah → jauh di bawah threshold 1000
            'annual_prod_ha'     => 750,
            // Quality: 5.8 → di bawah threshold 7.0 (HIGH)
            'quality_score_base' => 5.4,
            'quality_variance'   => 0.6,
            // Strategic actions
            'fertilizer_root' => ['realization' => 55.0, 'dosis_n' => 250, 'realized_dosis_n' => 130],  // < 70% → HIGH
            'fertilizer_leaf' => ['realization' => 45.0],   // < 60% → HIGH
            'weed_control'    => ['realization' => 50.0],   // < 60% → HIGH
            'cultivator'      => ['realization' => 40.0],   // < 60% → HIGH
            'picking'         => ['realization' => 60.0, 'kandas_risk' => true],  // kandas_risk=true → HIGH
            'machine'         => ['avg_age' => 9.5],         // >= 8 → HIGH
            'opt'             => ['status' => 'Berat — Helopeltis tidak terkendali di 7 blok, serangan parah',
                                  'tp_norm' => false],       // 'berat/parah' → HIGH
        ],
        'Kebun Teh Kaligua' => [
            'alert'              => 'HIGH',
            'annual_prod_ha'     => 880,
            'quality_score_base' => 5.8,
            'quality_variance'   => 0.5,
            'fertilizer_root' => ['realization' => 62.0, 'dosis_n' => 240, 'realized_dosis_n' => 140],
            'fertilizer_leaf' => ['realization' => 55.0],
            'weed_control'    => ['realization' => 48.0],
            'cultivator'      => ['realization' => 52.0],
            'picking'         => ['realization' => 55.0, 'kandas_risk' => true],
            'machine'         => ['avg_age' => 8.3],
            'opt'             => ['status' => 'Tidak terkendali — serangan Empoasca sp. parah di afdeling timur',
                                  'tp_norm' => false],
        ],

        // ── MEDIUM ALERT ───────────────────────────────────────────────────
        'Kebun Teh Sedep' => [
            'alert'              => 'MEDIUM',
            'annual_prod_ha'     => 1150,  // 1000 < 1150 < 1300 → MEDIUM
            'quality_score_base' => 7.4,   // 7.0 < 7.4 < 8.5 → MEDIUM
            'quality_variance'   => 0.6,
            'fertilizer_root' => ['realization' => 78.0, 'dosis_n' => 240, 'realized_dosis_n' => 195],  // 70-85% → MEDIUM
            'fertilizer_leaf' => ['realization' => 68.0],   // 60-80% → MEDIUM
            'weed_control'    => ['realization' => 72.0],   // 60-80% → MEDIUM
            'cultivator'      => ['realization' => 65.0],   // 60-80% → MEDIUM
            'picking'         => ['realization' => 72.0, 'kandas_risk' => false],  // realisasi < 80% → MEDIUM
            'machine'         => ['avg_age' => 6.2],        // 5-8 → MEDIUM
            'opt'             => ['status' => 'Sedang — Helopeltis mulai terpantau di 2 blok utara',
                                  'tp_norm' => true],       // 'sedang' → MEDIUM
        ],
        'Kebun Teh Gambung' => [
            'alert'              => 'MEDIUM',
            'annual_prod_ha'     => 1250,
            'quality_score_base' => 7.8,
            'quality_variance'   => 0.5,
            'fertilizer_root' => ['realization' => 82.0, 'dosis_n' => 240, 'realized_dosis_n' => 200],
            'fertilizer_leaf' => ['realization' => 75.0],
            'weed_control'    => ['realization' => 76.0],
            'cultivator'      => ['realization' => 70.0],
            'picking'         => ['realization' => 75.0, 'kandas_risk' => false],
            'machine'         => ['avg_age' => 5.8],
            'opt'             => ['status' => 'Mulai terdeteksi — monitoring ketat di blok B dan C',
                                  'tp_norm' => false],      // tp_norm=false → MEDIUM
        ],

        // ── LOW ALERT (Optimal) ────────────────────────────────────────────
        'Kebun Teh Malabar' => [
            'alert'              => 'LOW',
            'annual_prod_ha'     => 1650,  // >= 1300 → LOW (optimal)
            'quality_score_base' => 8.7,   // > 8.5 → LOW (sangat baik)
            'quality_variance'   => 0.3,
            'fertilizer_root' => ['realization' => 91.0, 'dosis_n' => 250, 'realized_dosis_n' => 238],  // >= 85% → LOW
            'fertilizer_leaf' => ['realization' => 88.0],   // >= 80% → LOW
            'weed_control'    => ['realization' => 85.0],   // >= 80% → LOW
            'cultivator'      => ['realization' => 82.0],   // >= 80% → LOW
            'picking'         => ['realization' => 90.0, 'kandas_risk' => false],  // >= 80%, no kandas → LOW
            'machine'         => ['avg_age' => 3.5],        // < 5 → LOW
            'opt'             => ['status' => 'Terkendali — monitoring rutin, tidak ada serangan signifikan',
                                  'tp_norm' => true],
        ],
        'Kebun Teh Rancabali' => [
            'alert'              => 'LOW',
            'annual_prod_ha'     => 2100,
            'quality_score_base' => 9.0,
            'quality_variance'   => 0.2,
            'fertilizer_root' => ['realization' => 95.0, 'dosis_n' => 250, 'realized_dosis_n' => 245],
            'fertilizer_leaf' => ['realization' => 92.0],
            'weed_control'    => ['realization' => 90.0],
            'cultivator'      => ['realization' => 86.0],
            'picking'         => ['realization' => 95.0, 'kandas_risk' => false],
            'machine'         => ['avg_age' => 2.8],
            'opt'             => ['status' => 'Terkendali baik — Beauveria bassiana berhasil menekan Helopeltis',
                                  'tp_norm' => true],
        ],
    ];

    // Faktor musiman 2026 (8 bulan aktual)
    private array $seasonalFactors = [
        1 => 1.18, 2 => 1.13, 3 => 1.04,
        4 => 0.92, 5 => 0.84, 6 => 0.75,
        7 => 0.72, 8 => 0.78,
    ];

    public function run(): void
    {
        $this->clearExisting2026Data();

        $gardens = Garden::all()->keyBy('kebun_name');
        $program = $this->ensureProgram();

        foreach ($this->alertConfig as $kebunName => $cfg) {
            $garden = $gardens->get($kebunName);
            if (!$garden) {
                $this->command->warn("Kebun '{$kebunName}' tidak ditemukan, skip.");
                continue;
            }

            $this->seedProduction($garden, $cfg);
            $this->seedPerformanceTarget($garden, $cfg);
            $this->seedStrategicActions($garden, $cfg, $program->id);
        }

        $this->command->newLine();
        $this->command->info('AlertDemoSeeder selesai. Distribusi alert per kebun:');
        $this->command->table(
            ['Kebun', 'Alert Level', 'Protas Target (kg/ha)', 'Quality Base'],
            collect($this->alertConfig)->map(fn($c, $k) => [
                $k, $c['alert'], $c['annual_prod_ha'], $c['quality_score_base'],
            ])->values()->toArray()
        );
        $this->command->newLine();
        $this->command->info('Jalankan InsightService untuk generate insight:');
        $this->command->info('  php artisan insight:generate --year=2026');
    }

    // ── Hapus data 2026 yang ada sebelum di-overwrite ─────────────────────
    private function clearExisting2026Data(): void
    {
        $gardenIds = Garden::pluck('id');

        // Hapus production_realizations 2026
        DB::table('production_realizations')
            ->whereIn('kebun_id', $gardenIds)
            ->where('year', 2026)
            ->delete();

        // Hapus strategic_actions 2026
        DB::table('strategic_actions')
            ->whereIn('kebun_id', $gardenIds)
            ->where('year', 2026)
            ->delete();

        // Hapus performance_targets 2026
        DB::table('performance_targets')
            ->whereIn('kebun_id', $gardenIds)
            ->where('year', 2026)
            ->delete();

        $this->command->info('Data 2026 sebelumnya (production/strategic/target) telah dihapus.');
    }

    // ── Buat / ambil program demo ──────────────────────────────────────────
    private function ensureProgram(): Program
    {
        return Program::firstOrCreate(
            ['program_name' => 'Program Demo Alert 2026'],
            [
                'description'  => 'Program dummy untuk demonstrasi tiga level alert rule engine.',
                'year'         => 2026,
                'program_type' => 'Model',
                'status'       => true,
            ]
        );
    }

    // ── Seed ProductionRealization — nilai dikontrol ketat ─────────────────
    private function seedProduction(object $garden, array $cfg): void
    {
        $activeArea   = round($garden->luas_total_ha * 0.92, 2);
        $monthlyBase  = $cfg['annual_prod_ha'] / 12;
        $alertLevel   = $cfg['alert'];

        foreach ($this->seasonalFactors as $month => $seasonal) {
            $wetProd = round($monthlyBase * $seasonal * $activeArea, 2);
            $dryProd = round($wetProd * 0.22, 2);

            // Quality score — dikontrol agar rata-rata setahun masuk zona alert
            $qualityScore = round(
                $cfg['quality_score_base'] + (mt_rand() / mt_getrandmax()) * $cfg['quality_variance'],
                2
            );

            $curahHujan = match (true) {
                $month <= 3 => 'tinggi',
                $month <= 5 => 'sedang',
                default     => 'rendah (El Nino)',
            };

            ProductionRealization::create([
                'kebun_id'               => $garden->id,
                'month'                  => $month,
                'year'                   => 2026,
                'active_picking_area_ha' => $activeArea,
                'wet_production_kg'      => $wetProd,
                'dry_production_kg'      => $dryProd,
                'capacity_per_ha'        => round(25 + (mt_rand() / mt_getrandmax()) * 20, 2),
                'avg_capacity'           => round(28 + (mt_rand() / mt_getrandmax()) * 18, 2),
                'estimated_production'   => round($wetProd * 1.05, 2),
                'quality_score'          => $qualityScore,
                'assumption_note'        => "[DEMO {$alertLevel}] {$garden->kebun_name} {$month}/2026. Curah hujan: {$curahHujan}. Target protas: {$cfg['annual_prod_ha']} kg/ha.",
            ]);
        }

        // Hitung protas aktual untuk verifikasi
        $totalWet = collect($this->seasonalFactors)->keys()->sum(
            fn($m) => ($cfg['annual_prod_ha'] / 12) * $this->seasonalFactors[$m] * $activeArea
        );
        $protasAktual = round($totalWet / $activeArea, 1);

        $this->command->line("  [{$alertLevel}] {$garden->kebun_name}: ~{$protasAktual} kg/ha/tahun (8 bln), quality_score ~{$cfg['quality_score_base']}");
    }

    // ── Seed PerformanceTarget ─────────────────────────────────────────────
    private function seedPerformanceTarget(object $garden, array $cfg): void
    {
        $target = $cfg['annual_prod_ha'];

        PerformanceTarget::create([
            'kebun_id'          => $garden->id,
            'year'              => 2026,
            'target_protas_min' => $target,
            'target_protas_max' => round($target * 1.15, 2),
            'note'              => "[DEMO {$cfg['alert']}] Target disesuaikan untuk demonstrasi alert {$cfg['alert']}.",
        ]);
    }

    // ── Seed StrategicActions — setiap aksi dikontrol trigger alert-nya ───
    private function seedStrategicActions(object $garden, array $cfg, int $programId): void
    {
        $alert       = $cfg['alert'];
        $realizeDate = Carbon::create(2026, 8, 20)->format('Y-m-d');

        // 1. fertilizer_root
        $fr = $cfg['fertilizer_root'];
        StrategicAction::create([
            'kebun_id'               => $garden->id,
            'program_id'             => $programId,
            'year'                   => 2026,
            'action_type'            => 'fertilizer_root',
            'status'                 => $fr['realization'] >= 85 ? 'completed' : 'in_progress',
            'realization_date'       => $realizeDate,
            'realization_percent'    => $fr['realization'],
            'dosis_n_kg_ha'          => $fr['dosis_n'],
            'realized_dosis_n_kg_ha' => $fr['realized_dosis_n'],
            'n_protas_percent'       => 88,
            'application_frequency'  => 4,
            'fertilizer_type'        => 'Urea + NPK Phonska',
            'coverage_target_percent'=> 95,
            'note'                   => "[DEMO {$alert}] fertilizer_root realisasi {$fr['realization']}%.",
        ]);

        // 2. fertilizer_leaf
        $fl = $cfg['fertilizer_leaf'];
        StrategicAction::create([
            'kebun_id'               => $garden->id,
            'program_id'             => $programId,
            'year'                   => 2026,
            'action_type'            => 'fertilizer_leaf',
            'status'                 => $fl['realization'] >= 80 ? 'completed' : 'in_progress',
            'realization_date'       => $realizeDate,
            'realization_percent'    => $fl['realization'],
            'coverage_target_percent'=> 95,
            'application_interval'   => '14 hari',
            'note'                   => "[DEMO {$alert}] fertilizer_leaf realisasi {$fl['realization']}%.",
        ]);

        // 3. weed_control
        $wc = $cfg['weed_control'];
        StrategicAction::create([
            'kebun_id'               => $garden->id,
            'program_id'             => $programId,
            'year'                   => 2026,
            'action_type'            => 'weed_control',
            'status'                 => $wc['realization'] >= 80 ? 'completed' : 'in_progress',
            'realization_date'       => $realizeDate,
            'realization_percent'    => $wc['realization'],
            'rotation_per_year'      => 6,
            'method'                 => 'Manual + Herbisida Selektif',
            'coverage_target_percent'=> 100,
            'note'                   => "[DEMO {$alert}] weed_control realisasi {$wc['realization']}%.",
        ]);

        // 4. cultivator
        $cv = $cfg['cultivator'];
        StrategicAction::create([
            'kebun_id'               => $garden->id,
            'program_id'             => $programId,
            'year'                   => 2026,
            'action_type'            => 'cultivator',
            'status'                 => $cv['realization'] >= 80 ? 'completed' : 'in_progress',
            'realization_date'       => $realizeDate,
            'realization_percent'    => $cv['realization'],
            'rotation_per_year'      => 4,
            'focus_area'             => 'Blok dengan tanah kompak',
            'coverage_target_percent'=> 100,
            'note'                   => "[DEMO {$alert}] cultivator realisasi {$cv['realization']}%.",
        ]);

        // 5. picking — kandas_risk mengontrol HIGH/MEDIUM/LOW
        $pk = $cfg['picking'];
        StrategicAction::create([
            'kebun_id'               => $garden->id,
            'program_id'             => $programId,
            'year'                   => 2026,
            'action_type'            => 'picking',
            'status'                 => $pk['kandas_risk'] ? 'in_progress' : ($pk['realization'] >= 80 ? 'completed' : 'in_progress'),
            'realization_date'       => $realizeDate,
            'realization_percent'    => $pk['realization'],
            'picking_system'         => $pk['kandas_risk'] ? 'Manual Agresif' : 'Mekanis + Manual Selektif',
            'cushion_consistency'    => $pk['kandas_risk'] ? 'Tipis (berisiko)' : 'Baik',
            'kandas_risk'            => $pk['kandas_risk'],
            'coverage_target_percent'=> 100,
            'note'                   => "[DEMO {$alert}] picking, kandas_risk=" . ($pk['kandas_risk'] ? 'TRUE' : 'false') . ", realisasi {$pk['realization']}%.",
        ]);

        // 6. machine — avg_machine_age mengontrol HIGH/MEDIUM/LOW
        $mc = $cfg['machine'];
        StrategicAction::create([
            'kebun_id'          => $garden->id,
            'program_id'        => $programId,
            'year'              => 2026,
            'action_type'       => 'machine',
            'status'            => $mc['avg_age'] < 5 ? 'completed' : 'in_progress',
            'realization_date'  => $realizeDate,
            'total_machine'     => 50,
            'avg_machine_age'   => $mc['avg_age'],
            'renewal_status'    => match (true) {
                $mc['avg_age'] >= 8 => 'Kritis — peremajaan mendesak',
                $mc['avg_age'] >= 5 => 'Perlu direncanakan',
                default             => 'Baik — terjadwal berkala',
            },
            'note'              => "[DEMO {$alert}] machine, avg_age={$mc['avg_age']} tahun.",
        ]);

        // 7. opt — opt_status string & tp_normalization mengontrol alert
        $opt = $cfg['opt'];
        StrategicAction::create([
            'kebun_id'               => $garden->id,
            'program_id'             => $programId,
            'year'                   => 2026,
            'action_type'            => 'opt',
            'status'                 => str_contains(strtolower($opt['status']), 'terkendali') ? 'completed' : 'in_progress',
            'realization_date'       => $realizeDate,
            'opt_status'             => $opt['status'],
            'tp_normalization'       => $opt['tp_norm'],
            'coverage_target_percent'=> 100,
            'treatment_note'         => "[DEMO {$alert}] " . $opt['status'] . ". TP Normalisasi: " . ($opt['tp_norm'] ? 'Ya' : 'Tidak'),
            'note'                   => "[DEMO {$alert}] opt. Status: {$opt['status']}",
        ]);
    }
}
