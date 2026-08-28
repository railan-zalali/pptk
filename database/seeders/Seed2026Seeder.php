<?php

namespace Database\Seeders;

use App\Models\CommunityService;
use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Program;
use App\Models\ResearchBudget;
use App\Models\ResearchBudgetBalance;
use App\Models\StrategicAction;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Seed2026Seeder — Data realistis tahun 2026 untuk sistem PPTK Gambung.
 *
 * Mencakup:
 *   1. ProductionRealization  — Realisasi produksi bulanan Jan–Agt 2026
 *   2. PerformanceTarget      — Target protas 2026 (+5% dari target 2025)
 *   3. Program & StrategicAction — Program dan aksi strategis 2026
 *   4. Visit & CommunityService — Kunjungan dan pengabdian masyarakat 2026
 *   5. ResearchBudget         — Anggaran penelitian Jan–Agt 2026
 *
 * ASUMSI KONTEKSTUAL 2026:
 *   - Tahun ke-2 implementasi program mekanisasi PPTK
 *   - Produktivitas naik ~3-5% dari baseline 2025 akibat pemupukan berimbang
 *   - El Nino moderat menekan produksi pada Jun-Agt (kemarau lebih kering)
 *   - Mesin petik generasi baru beroperasi Feb 2026 di Rancabali & Malabar
 *   - Data Jan-Agt terisi; Sep-Des null (belum terjadi, sesuai tanggal: 28 Agt 2026)
 *   - Helopeltis aktif di 3 blok Rancabali; IPM intensif sejak Jul
 */
class Seed2026Seeder extends Seeder
{
    // Faktor musiman 2026: Jan-Mar hujan (tinggi), Apr-Mei transisi, Jun-Agt kemarau El Nino
    private array $seasonalFactors = [
        1 => 1.18,
        2 => 1.13,
        3 => 1.04,
        4 => 0.92,
        5 => 0.84,
        6 => 0.75,
        7 => 0.72,
        8 => 0.78,
    ];

    // Produktivitas basah 2026 per kebun (kg/ha/tahun) — naik 3-5% dari 2025
    private array $gardenProductivity = [
        'Kebun Teh Rancabali' => 2330,
        'Kebun Teh Malabar'   => 2080,
        'Kebun Teh Gambung'   => 1560,
        'Kebun Teh Kaligua'   => 1340,
        'Kebun Teh Sedep'     => 1450,
        'Kebun Teh Pagaralam' => 1236,
    ];

    public function run(): void
    {
        $this->seedProduction();
        $this->seedPerformanceTargets();
        $this->seedStrategicPrograms();
        $this->seedVisits();
        $this->seedCommunityServices();
        $this->seedResearchBudget();
    }

    // ── 1. Realisasi Produksi Jan–Agt 2026 ────────────────────────────────
    private function seedProduction(): void
    {
        $gardens = Garden::all();
        $count   = 0;

        foreach ($gardens as $garden) {
            $activeArea   = round($garden->luas_total_ha * 0.92, 2);
            $annualProdHa = $this->gardenProductivity[$garden->kebun_name] ?? 1400;
            $monthlyBase  = $annualProdHa / 12;

            foreach ($this->seasonalFactors as $month => $seasonal) {
                // Variasi acak +/-8% (monitoring lebih ketat dibanding 2025)
                $random       = 0.92 + (mt_rand() / mt_getrandmax()) * 0.16;
                $productivity = $monthlyBase * $seasonal * $random;
                $wetProd      = round($productivity * $activeArea, 2);
                $dryProd      = round($wetProd * 0.22, 2);

                // Kualitas lebih rendah di kemarau (pucuk lebih kasar, rotasi lebih pendek)
                $qualityBase  = ($month >= 6 && $month <= 8) ? 7.2 : 7.8;
                $qualityScore = round($qualityBase + (mt_rand() / mt_getrandmax()) * 1.8, 2);

                // Mesin baru: kapasitas per ha lebih stabil dan lebih tinggi
                $capPerHa    = round(32 + (mt_rand() / mt_getrandmax()) * 14, 2);
                $avgCapacity = round(34 + (mt_rand() / mt_getrandmax()) * 12, 2);

                // Estimasi produksi lebih konservatif saat El Nino
                $estimFactor = ($month >= 6 && $month <= 8) ? 1.03 : 1.06;

                $curahHujan = match (true) {
                    $month <= 3 => 'tinggi (musim hujan)',
                    $month <= 5 => 'sedang (peralihan)',
                    default     => 'rendah (kemarau El Nino)',
                };
                $monthName = Carbon::create(2026, $month)->translatedFormat('F');
                $note = "Produksi {$monthName} 2026 — {$garden->kebun_name}. Curah hujan: {$curahHujan}. Faktor musiman: {$seasonal}.";
                if ($month >= 6 && $month <= 8) {
                    $note .= ' Dampak El Nino: tekanan pucuk lebih cepat, rotasi petik dipersingkat.';
                }

                ProductionRealization::updateOrCreate(
                    ['kebun_id' => $garden->id, 'month' => $month, 'year' => 2026],
                    [
                        'active_picking_area_ha' => $activeArea,
                        'wet_production_kg'      => $wetProd,
                        'dry_production_kg'      => $dryProd,
                        'capacity_per_ha'        => $capPerHa,
                        'avg_capacity'           => $avgCapacity,
                        'estimated_production'   => round($wetProd * $estimFactor, 2),
                        'quality_score'          => $qualityScore,
                        'assumption_note'        => $note,
                    ]
                );
                $count++;
            }
        }

        $this->command->info("Seed2026 Production: {$count} record realisasi produksi Jan-Agt 2026 berhasil di-seed.");
    }

    // ── 2. Target Kinerja 2026 ─────────────────────────────────────────────
    private function seedPerformanceTargets(): void
    {
        // Target 2026 = target 2025 x 1.05 (naik 5%, program intensifikasi tahun ke-2)
        $gardenTargets = [
            'Kebun Teh Rancabali' => 1890,
            'Kebun Teh Malabar'   => 1680,
            'Kebun Teh Gambung'   => 1260,
            'Kebun Teh Kaligua'   => 1092,
            'Kebun Teh Sedep'     => 1176,
            'Kebun Teh Pagaralam' => 1008,
        ];

        $gardens = Garden::all();
        $count   = 0;

        foreach ($gardens as $garden) {
            $target = $gardenTargets[$garden->kebun_name] ?? 1260;

            PerformanceTarget::updateOrCreate(
                ['kebun_id' => $garden->id, 'year' => 2026],
                [
                    'target_protas_min' => $target,
                    // Ceiling +12% (lebih ketat dari 2025 yg +15%; target makin presisi)
                    'target_protas_max' => round($target * 1.12, 2),
                    'note'              => "Target 2026 naik 5% dari 2025. Mempertimbangkan dampak El Nino pada semester II. {$garden->kebun_name}.",
                ]
            );
            $count++;
        }

        $this->command->info("Seed2026 PerformanceTarget: {$count} target kinerja 2026 berhasil di-seed.");
    }

    // ── 3. Program & Strategic Actions 2026 ───────────────────────────────
    private function seedStrategicPrograms(): void
    {
        $programsData = [
            [
                'program_name' => 'Program Intensifikasi Budidaya Teh 2026',
                'description'  => 'Optimasi pemupukan berimbang dan pengelolaan kanopi untuk meningkatkan protas 5% dari baseline 2025.',
                'year'         => 2026,
                'program_type' => 'Model',
                'status'       => true,
            ],
            [
                'program_name' => 'Program Adaptasi Perubahan Iklim 2026',
                'description'  => 'Strategi mitigasi El Nino: irigasi tetes, mulsa organik, dan rotasi petik adaptif untuk meminimalkan penurunan produksi musim kemarau.',
                'year'         => 2026,
                'program_type' => 'Pengembangan',
                'status'       => true,
            ],
            [
                'program_name' => 'Program Mekanisasi Lanjutan 2026',
                'description'  => 'Fase ke-2 mekanisasi: penambahan mesin petik di Kaligua & Pagaralam, uji coba drone monitoring blok berbasis kecerdasan buatan.',
                'year'         => 2026,
                'program_type' => 'Pengembangan',
                'status'       => true,
            ],
        ];

        $programs = [];
        foreach ($programsData as $pData) {
            $programs[] = Program::create($pData);
        }
        $programIds = array_column($programs, 'id');

        $gardenTargets = [
            'Kebun Teh Rancabali' => 1890,
            'Kebun Teh Malabar'   => 1680,
            'Kebun Teh Gambung'   => 1260,
            'Kebun Teh Kaligua'   => 1092,
            'Kebun Teh Sedep'     => 1176,
            'Kebun Teh Pagaralam' => 1008,
        ];

        // Config dasar tiap jenis aksi (diperbarui dari 2025)
        $actionConfigs = [
            'fertilizer_root' => [
                'dosis_n_kg_ha'           => 250,
                'realized_dosis_n_kg_ha'  => 220,
                'n_protas_percent'        => 88,
                'application_frequency'   => 4,
                'fertilizer_type'         => 'Urea + NPK Phonska + Dolomit',
                'coverage_target_percent' => 95,
                'technical_note'          => 'Penambahan dolomit untuk koreksi pH tanah yang menurun akibat kemarau El Nino.',
            ],
            'fertilizer_leaf' => [
                'coverage_target_percent' => 95,
                'application_interval'    => '14 hari',
                'technical_note'          => 'Frekuensi ditingkatkan di musim kemarau untuk kompensasi stres kekeringan.',
            ],
            'weed_control' => [
                'rotation_per_year'       => 6,
                'method'                  => 'Manual + Herbisida Selektif',
                'coverage_target_percent' => 100,
                'technical_note'          => 'Rotasi ke-4 ditargetkan sebelum Sep; lebih mudah di kemarau.',
            ],
            'cultivator' => [
                'rotation_per_year'       => 4,
                'focus_area'              => 'Blok tanah kompak, prioritas di afdeling terdampak El Nino',
                'coverage_target_percent' => 100,
                'technical_note'          => 'Kultivasi pre-musim hujan ditargetkan Sep-Okt untuk maksimalkan serapan air.',
            ],
            'picking' => [
                'picking_system'          => 'Mekanis + Manual Selektif',
                'cushion_consistency'     => 'Baik',
                'kandas_risk'             => false,
                'coverage_target_percent' => 100,
                'technical_note'          => 'Mesin petik generasi baru (12 unit) beroperasi sejak Feb 2026 di Rancabali & Malabar.',
            ],
            'machine' => [
                'total_machine'   => 62,
                'avg_machine_age' => 3.8,
                'renewal_status'  => 'Peremajaan 12 unit selesai Feb 2026; 7 unit lama diretire',
                'technical_note'  => 'ROI mesin baru diestimasi 2.5 tahun berdasarkan kenaikan kapasitas pemetikan.',
            ],
            'opt' => [
                'opt_status'              => 'Aktif — Helopeltis terpantau di 3 blok Rancabali',
                'tp_normalization'        => true,
                'coverage_target_percent' => 100,
                'treatment_note'          => 'IPM: agens hayati Beauveria bassiana + monitoring mingguan sejak Jul 2026.',
            ],
        ];

        // Realisasi sd Agt 2026 (8/12 bulan = 67% tahun; semester I bagus, kemarau sedikit menekan)
        $realizationRanges = [
            'fertilizer_root' => [72, 92],
            'fertilizer_leaf' => [65, 88],
            'weed_control'    => [62, 85],
            'cultivator'      => [55, 75],
            'picking'         => [78, 95],
            'machine'         => [85, 100],
            'opt'             => [68, 90],
        ];

        $gardens = Garden::all();
        $saCount = 0;

        foreach ($gardens as $garden) {
            $lastRealizationDate = Carbon::create(2026, 8, rand(15, 28));

            foreach ($actionConfigs as $type => $baseData) {
                [$min, $max] = $realizationRanges[$type];
                $realization = round($min + (mt_rand() / mt_getrandmax()) * ($max - $min), 2);
                $status      = $realization >= 85 ? 'completed' : 'in_progress';

                StrategicAction::create(array_merge($baseData, [
                    'kebun_id'            => $garden->id,
                    'program_id'          => $programIds[array_rand($programIds)],
                    'year'                => 2026,
                    'action_type'         => $type,
                    'status'              => $status,
                    'realization_date'    => $lastRealizationDate->format('Y-m-d'),
                    'realization_percent' => $realization,
                    'note'                => "Realisasi sd Agt 2026: {$realization}%. Aksi " . str_replace('_', ' ', $type) . " di {$garden->kebun_name}.",
                ]));
                $saCount++;
            }
        }

        $this->command->info("Seed2026 Strategic: " . count($programsData) . " program dan {$saCount} strategic action 2026 berhasil di-seed.");
    }

    // ── 4. Kunjungan Dinas 2026 ────────────────────────────────────────────
    private function seedVisits(): void
    {
        $gardens = Garden::all();

        $purposes = [
            'Studi Banding Teknologi Mesin Petik Generasi Baru',
            'Monitoring Program Adaptasi El Nino 2026',
            'Penelitian Helopeltis dan Pengendalian IPM',
            'Evaluasi Penerapan Drone Monitoring Blok',
            'Kunjungan Benchmarking Ekspor Teh Organik',
            'Audit Mutu dan Sertifikasi Rainforest Alliance',
        ];

        $visitorNames = [
            'Dr. Wahyu Pratama (Badan Riset Inovasi Nasional)',
            'Prof. Dr. Endang Sulistyowati (IPB University)',
            'Ir. Bambang Prasetya, M.Sc. (Dinas Perkebunan Provinsi Jabar)',
            'Dr. Rina Kusumawati (Puslitbangbun Bogor)',
            'Tim Auditor Rainforest Alliance Indonesia',
            'Delegasi Kementerian Pertanian RI',
            'Dr. Farid Hakim (Universitas Jember — Lab Entomologi)',
            'Dr. Maya Safitri (PTPN I Regional 2)',
            'Tim Inovasi Digital Pertanian — Telkom Indonesia',
            'Prof. Dr. Setyo Widodo (Universitas Brawijaya)',
            'Ir. Soepandi, M.Agr. (Bappenas — Direktorat Pangan)',
        ];

        $count = 0;
        foreach ($gardens as $garden) {
            $numVisits = rand(4, 6);
            for ($i = 0; $i < $numVisits; $i++) {
                $month = rand(1, 8);
                $day   = rand(1, 28);
                $date  = Carbon::create(2026, $month, $day);

                Visit::create([
                    'garden_id'          => $garden->id,
                    'visit_date'         => $date,
                    'visitor_name'       => $visitorNames[array_rand($visitorNames)],
                    'purpose'            => $purposes[$i % count($purposes)],
                    'status'             => 'completed',
                    'title'              => "Kunjungan ke {$garden->kebun_name} — " . $date->translatedFormat('M Y'),
                    'participants_count' => rand(4, 25),
                    'participants_list'  => rand(4, 25) . ' peserta',
                    'description'        => "Kunjungan dalam rangka {$purposes[$i % count($purposes)]} di {$garden->kebun_name}, {$date->translatedFormat('F Y')}.",
                    'duration'           => rand(1, 3),
                ]);
                $count++;
            }
        }

        $this->command->info("Seed2026 Visit: {$count} kunjungan dinas 2026 berhasil di-seed.");
    }

    // ── 5. Pengabdian Masyarakat 2026 ─────────────────────────────────────
    private function seedCommunityServices(): void
    {
        $activities = [
            [
                'activity_name'    => 'Pelatihan Teknik Budidaya Teh Adaptif Iklim',
                'team_name'        => 'Tim PPTK Bidang Agroklimat',
                'description'      => 'Pelatihan bagi petani teh sekitar kebun mengenai teknik budidaya adaptif menghadapi El Nino 2026: mulsa organik, irigasi hemat air, dan panen adaptif.',
                'location'         => 'Desa Mekarsari, Pasirjambu, Bandung',
                'year'             => 2026,
                'total_budget'     => 90_000_000,
                'remaining_budget' => 31_500_000,
            ],
            [
                'activity_name'    => 'Sosialisasi Mekanisasi Pemetikan untuk Petani Kecil',
                'team_name'        => 'Tim PPTK Bidang Mekanisasi',
                'description'      => 'Demonstrasi dan sosialisasi mesin petik skala kecil yang terjangkau untuk petani teh rakyat di sekitar Gambung dan Rancabali.',
                'location'         => 'Desa Patengan, Rancabali, Bandung',
                'year'             => 2026,
                'total_budget'     => 110_000_000,
                'remaining_budget' => 42_000_000,
            ],
            [
                'activity_name'    => 'Workshop Pengolahan Teh Specialty untuk Pasar Ekspor',
                'team_name'        => 'Tim PPTK Bidang Teknologi Pengolahan',
                'description'      => 'Workshop pengolahan teh specialty (white tea, oolong) untuk meningkatkan nilai tambah dan membuka akses pasar ekspor premium bagi petani lokal.',
                'location'         => 'Desa Pandansari, Paguyangan, Brebes',
                'year'             => 2026,
                'total_budget'     => 135_000_000,
                'remaining_budget' => 55_000_000,
            ],
            [
                'activity_name'    => 'Program Kemitraan Kebun Plasma Petani Teh',
                'team_name'        => 'Tim PPTK Bidang Pengembangan Usaha',
                'description'      => 'Model kemitraan kebun plasma: PPTK menyediakan bibit klon GMB dan pendampingan teknis, petani menyediakan lahan dan tenaga kerja.',
                'location'         => 'Kec. Pangalengan, Kab. Bandung',
                'year'             => 2026,
                'total_budget'     => 200_000_000,
                'remaining_budget' => 98_500_000,
            ],
            [
                'activity_name'    => 'Demonstrasi Pemupukan Organik Berbasis Limbah Pabrik',
                'team_name'        => 'Tim PPTK Bidang Lingkungan',
                'description'      => 'Demonstrasi pembuatan pupuk organik dari limbah pengolahan teh (spent tea) dan biochar sebagai substitusi pupuk kimia yang hemat biaya.',
                'location'         => 'Desa Banjarsari, Pangalengan, Bandung',
                'year'             => 2026,
                'total_budget'     => 70_000_000,
                'remaining_budget' => 18_200_000,
            ],
            [
                'activity_name'    => 'Pelatihan Monitoring OPT dengan Smartphone dan Drone',
                'team_name'        => 'Tim PPTK Bidang Proteksi Tanaman',
                'description'      => 'Pelatihan bagi petani muda (usia 20-35 tahun) menggunakan aplikasi smartphone dan drone mini untuk monitoring hama Helopeltis dan penyakit cacar daun.',
                'location'         => 'Lereng Gunung Dempo, Kota Pagar Alam',
                'year'             => 2026,
                'total_budget'     => 85_000_000,
                'remaining_budget' => 28_750_000,
            ],
        ];

        foreach ($activities as $act) {
            CommunityService::create($act);
        }

        $this->command->info('Seed2026 CommunityService: ' . count($activities) . ' kegiatan pengabdian masyarakat 2026 berhasil di-seed.');
    }

    // ── 6. Anggaran Penelitian 2026 (Jan–Agt) ─────────────────────────────
    private function seedResearchBudget(): void
    {
        $year = 2026;

        // Saldo awal 2026 = sisa anggaran 2025 yang diluncurkan ke tahun berikutnya
        $openingBalances = [
            'Kina'             =>  4_600_000,
            'KUT'              => 12_450_000,
            'Malabar/Sedep'    =>  8_520_000,
            'Drone'            =>          0,  // proyek drone 2025 selesai; proyek baru dimulai
            'GWP'              =>  3_180_000,
            'IPM Helopeltis'   =>  5_200_000,  // dilanjut karena OPT masih aktif
            'Bio Kompos'       =>  2_400_000,
            'Daur Petik'       =>  4_350_000,
            'Inkubasi Riset'   => 18_750_000,
            'Inkubasi Booster' =>          0,
        ];

        foreach ($openingBalances as $activity => $balance) {
            ResearchBudgetBalance::updateOrCreate(
                ['activity_name' => $activity, 'year' => $year],
                ['opening_balance' => $balance]
            );
        }

        // Income Jan-Agt 2026 (pencairan per bulan)
        // Pola: pencairan semester I (Mar) dan semester II (Jun)
        $incomes = [
            //                         Jan  Feb          Mar          Apr  May          Jun          Jul          Agt
            'Kina'             => [    0,   0,           0,           0,   0,           0,           0,           0],
            'KUT'              => [    0,   0,  72_500_000,           0,   0,           0,           0,           0],
            'Malabar/Sedep'    => [    0,   0,           0,           0,   0,           0,           0,           0],
            'Drone'            => [    0,   0,           0,           0,   0,  38_500_000,           0,           0],
            'GWP'              => [    0,   0,           0,           0,   0,  28_750_000,           0,           0],
            'IPM Helopeltis'   => [    0,   0,           0,           0,   0,  55_000_000,           0,           0],
            'Bio Kompos'       => [    0,   0,           0,           0,   0,  18_200_000,           0,           0],
            'Daur Petik'       => [    0,   0,           0,           0,   0,  32_000_000,           0,           0],
            'Inkubasi Riset'   => [    0,  95_000_000,  0,           0,   0,  91_500_000,           0,           0],
            'Inkubasi Booster' => [    0,   0,           0,           0,   0,           0,  62_000_000,           0],
        ];

        // Pengeluaran Jan-Agt 2026 (aktual)
        $expenditures = [
            //                         Jan          Feb          Mar          Apr          May          Jun          Jul          Agt
            'Kina'             => [ 3_200_000,  1_400_000,           0,           0,           0,           0,           0,           0],
            'KUT'              => [ 3_850_000,  5_120_000,           0,  22_500_000,  28_700_000,  11_200_000,   8_400_000,   4_100_000],
            'Malabar/Sedep'    => [ 9_250_000,  4_100_000,     650_000,   2_300_000,   1_050_000,   1_800_000,   1_650_000,   2_100_000],
            'Drone'            => [         0,           0,           0,           0,           0,           0,   5_800_000,  12_300_000],
            'GWP'              => [         0,           0,           0,           0,           0,           0,  14_200_000,   8_500_000],
            'IPM Helopeltis'   => [         0,           0,           0,           0,           0,           0,  22_500_000,  15_800_000],
            'Bio Kompos'       => [         0,           0,           0,           0,           0,           0,   9_100_000,   3_200_000],
            'Daur Petik'       => [         0,           0,           0,           0,           0,           0,   7_800_000,   9_450_000],
            'Inkubasi Riset'   => [   620_000, 16_300_000,  11_500_000,  58_000_000,   7_200_000,   9_100_000,  31_000_000,  18_500_000],
            'Inkubasi Booster' => [         0,           0,           0,           0,           0,           0,           0,   8_500_000],
        ];

        $inserted = 0;
        foreach ($incomes as $activity => $monthlyIncomes) {
            for ($month = 1; $month <= 8; $month++) {
                $inc = $monthlyIncomes[$month - 1];
                $exp = $expenditures[$activity][$month - 1] ?? 0;

                if ($inc > 0 || $exp > 0) {
                    ResearchBudget::updateOrCreate(
                        ['activity_name' => $activity, 'year' => $year, 'month' => $month],
                        ['income' => $inc, 'expenditure' => $exp]
                    );
                    $inserted++;
                }
            }
        }

        $this->command->info("Seed2026 ResearchBudget: {$inserted} record anggaran penelitian 2026 (Jan-Agt) berhasil di-seed.");
    }
}
