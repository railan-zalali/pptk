<?php

namespace Database\Seeders;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\Garden;
use App\Models\GardenPhoto;
use App\Models\Insight;
use App\Models\Page;
use App\Models\PerformanceTarget;
use App\Models\ProductionData;
use App\Models\ProductionRealization;
use App\Models\Program;
use App\Models\Region;
use App\Models\RegionPhoto;
use App\Models\StrategicAction;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('visit_photos')->delete();
        DB::table('visits')->delete();
        DB::table('insights')->delete();
        DB::table('production_data')->delete();
        DB::table('strategic_actions')->delete();
        DB::table('production_realizations')->delete();
        DB::table('performance_targets')->delete();
        DB::table('blocks')->delete();
        DB::table('afdelings')->delete();
        DB::table('garden_photos')->delete();
        DB::table('gardens')->delete();
        DB::table('region_photos')->delete();
        DB::table('regions')->delete();
        DB::table('pages')->delete();
        DB::table('programs')->delete();
        DB::table('sessions')->delete();
        DB::table('password_reset_tokens')->delete();
        DB::table('cache_locks')->delete();
        DB::table('cache')->delete();
        DB::table('jobs')->delete();
        DB::table('job_batches')->delete();
        DB::table('failed_jobs')->delete();
        DB::table('users')->delete();

        $now = now();
        $currentYear = (int) $now->format('Y');

        $users = collect([
            ['name' => 'Admin PPTK', 'email' => 'admin@pptk.test', 'role' => 'admin'],
            ['name' => 'Manajer Regional Barat', 'email' => 'manager.barat@pptk.test', 'role' => 'manager'],
            ['name' => 'Manajer Regional Timur', 'email' => 'manager.timur@pptk.test', 'role' => 'manager'],
            ['name' => 'Manajer Operasional Kebun', 'email' => 'manager.kebun@pptk.test', 'role' => 'manager'],
            ['name' => 'Viewer Eksekutif 01', 'email' => 'viewer01@pptk.test', 'role' => 'viewer'],
            ['name' => 'Viewer Eksekutif 02', 'email' => 'viewer02@pptk.test', 'role' => 'viewer'],
            ['name' => 'Viewer Eksekutif 03', 'email' => 'viewer03@pptk.test', 'role' => 'viewer'],
            ['name' => 'Viewer Eksekutif 04', 'email' => 'viewer04@pptk.test', 'role' => 'viewer'],
            ['name' => 'Viewer Eksekutif 05', 'email' => 'viewer05@pptk.test', 'role' => 'viewer'],
            ['name' => 'Petugas Kunjungan', 'email' => 'petugas.kunjungan@pptk.test', 'role' => 'viewer'],
        ])->map(function (array $u) use ($now) {
            return User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'role' => $u['role'],
                'email_verified_at' => $now,
            ]);
        });

        $tokenTime = $now->copy()->subDays(2);
        foreach ($users as $u) {
            DB::table('password_reset_tokens')->insert([
                'email' => $u->email,
                'token' => Str::random(60),
                'created_at' => $tokenTime,
            ]);
        }

        foreach ($users as $idx => $u) {
            DB::table('sessions')->insert([
                'id' => (string) Str::uuid(),
                'user_id' => $u->id,
                'ip_address' => '10.10.0.' . ($idx + 10),
                'user_agent' => 'SeededSession/1.0 (Laravel)',
                'payload' => base64_encode(json_encode(['user_id' => $u->id, 'email' => $u->email])),
                'last_activity' => $now->timestamp - ($idx * 600),
            ]);
        }

        $regionsPayload = [
            ['regional_name' => 'Bandung Raya', 'province' => 'Jawa Barat', 'coordinates' => '-6.914744, 107.609810'],
            ['regional_name' => 'Garut Selatan', 'province' => 'Jawa Barat', 'coordinates' => '-7.227906, 107.908699'],
            ['regional_name' => 'Tasik Priangan', 'province' => 'Jawa Barat', 'coordinates' => '-7.350580, 108.217163'],
            ['regional_name' => 'Cianjur Puncak', 'province' => 'Jawa Barat', 'coordinates' => '-6.816084, 107.142191'],
            ['regional_name' => 'Subang Utara', 'province' => 'Jawa Barat', 'coordinates' => '-6.562042, 107.760557'],
            ['regional_name' => 'Sukabumi Selatan', 'province' => 'Jawa Barat', 'coordinates' => '-6.918693, 106.927777'],
            ['regional_name' => 'Bogor Puncak', 'province' => 'Jawa Barat', 'coordinates' => '-6.595038, 106.816635'],
            ['regional_name' => 'Purwakarta Perbukitan', 'province' => 'Jawa Barat', 'coordinates' => '-6.556944, 107.443333'],
            ['regional_name' => 'Sumedang Timur', 'province' => 'Jawa Barat', 'coordinates' => '-6.858611, 107.916389'],
            ['regional_name' => 'Cirebon Selatan', 'province' => 'Jawa Barat', 'coordinates' => '-6.731944, 108.552500'],
        ];

        $regions = collect($regionsPayload)->map(function (array $r) use ($now) {
            $code = Str::upper(Str::slug($r['regional_name']));
            $r['regional_code'] = $code;
            $r['photo_path'] = 'region-photos/' . $code . '-hero.jpg';
            return Region::create($r);
        });

        foreach ($regions as $region) {
            RegionPhoto::create([
                'region_id' => $region->id,
                'path' => 'region-photos/' . $region->regional_code . '-gallery-01.jpg',
            ]);
        }

        $gardensPayload = [
            ['kebun_name' => 'Kebun Model Gambung', 'luas_total_ha' => 185.50, 'kebun_type' => 'Model', 'location' => 'Ciwidey, Bandung', 'agro_climate_note' => 'Dataran tinggi, curah hujan stabil'],
            ['kebun_name' => 'Kebun Teh Cikajang', 'luas_total_ha' => 240.00, 'kebun_type' => 'Pengembangan', 'location' => 'Cikajang, Garut', 'agro_climate_note' => 'Lereng, suhu sejuk'],
            ['kebun_name' => 'Kebun Teh Salawu', 'luas_total_ha' => 160.75, 'kebun_type' => 'Pengembangan', 'location' => 'Salawu, Tasikmalaya', 'agro_climate_note' => 'Gelombang, risiko erosi sedang'],
            ['kebun_name' => 'Kebun Puncak Riset', 'luas_total_ha' => 120.25, 'kebun_type' => 'Model', 'location' => 'Cipanas, Cianjur', 'agro_climate_note' => 'Kabut pagi, kelembaban tinggi'],
            ['kebun_name' => 'Kebun Subang Highland', 'luas_total_ha' => 210.10, 'kebun_type' => 'Pengembangan', 'location' => 'Ciater, Subang', 'agro_climate_note' => 'Variasi suhu harian besar'],
            ['kebun_name' => 'Kebun Sukabumi Agro', 'luas_total_ha' => 175.00, 'kebun_type' => 'Pengembangan', 'location' => 'Pelabuhanratu, Sukabumi', 'agro_climate_note' => 'Angin laut, curah hujan tinggi'],
            ['kebun_name' => 'Kebun Bogor Edu', 'luas_total_ha' => 98.40, 'kebun_type' => 'Model', 'location' => 'Cisarua, Bogor', 'agro_climate_note' => 'Akses mudah, cocok untuk edukasi'],
            ['kebun_name' => 'Kebun Purwakarta Lahan', 'luas_total_ha' => 142.60, 'kebun_type' => 'Pengembangan', 'location' => 'Wanayasa, Purwakarta', 'agro_climate_note' => 'Topografi beragam, drainase bervariasi'],
            ['kebun_name' => 'Kebun Sumedang Komoditas', 'luas_total_ha' => 130.90, 'kebun_type' => 'Pengembangan', 'location' => 'Tanjungsari, Sumedang', 'agro_climate_note' => 'Transisi dataran, intensitas hujan fluktuatif'],
            ['kebun_name' => 'Kebun Cirebon Perintis', 'luas_total_ha' => 110.20, 'kebun_type' => 'Model', 'location' => 'Kuningan, Cirebon', 'agro_climate_note' => 'Mikroklimat kering relatif'],
        ];

        $gardens = collect($gardensPayload)->values()->map(function (array $g, int $idx) use ($regions, $now) {
            $region = $regions->get($idx);
            return Garden::create([
                'kebun_name' => $g['kebun_name'],
                'regional_id' => $region->id,
                'luas_total_ha' => $g['luas_total_ha'],
                'kebun_type' => $g['kebun_type'],
                'agro_climate_note' => $g['agro_climate_note'],
                'location' => $g['location'],
                'photo_path' => 'garden-photos/' . Str::upper(Str::slug($g['kebun_name'])) . '-hero.jpg',
                'description' => 'Kebun percontohan untuk penguatan praktik budidaya, pemantauan mutu, dan peningkatan produktivitas.',
                'established_at' => $now->copy()->subYears(8 + $idx)->toDateString(),
            ]);
        });

        foreach ($gardens as $garden) {
            GardenPhoto::create([
                'garden_id' => $garden->id,
                'path' => 'garden-photos/' . $garden->id . '/gallery-01.jpg',
                'title' => 'Kondisi Lapang',
                'description' => 'Dokumentasi kondisi kebun untuk arsip operasional.',
            ]);
        }

        $afdelings = $gardens->map(function (Garden $garden, int $idx) {
            return Afdeling::create([
                'garden_id' => $garden->id,
                'name' => 'Afdeling ' . chr(65 + $idx),
                'total_area_ha' => max(10, (float) $garden->luas_total_ha),
                'tm_area_ha' => max(5, round(((float) $garden->luas_total_ha) * 0.75, 2)),
                'manager_name' => 'Mandor ' . $garden->region->regional_name,
            ]);
        });

        $plantTypes = ['seedling', 'klon_gmb', 'klon_tri'];
        $initialClasses = ['A', 'B', 'C', 'D', 'E'];
        $topographies = ['datar', 'gelombang', 'curam'];

        $blocks = $afdelings->map(function (Afdeling $afdeling, int $idx) use ($plantTypes, $initialClasses, $topographies, $currentYear) {
            return Block::create([
                'afdeling_id' => $afdeling->id,
                'name' => 'Blok ' . str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT),
                'code' => 'BLK-' . str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT),
                'plant_type' => $plantTypes[$idx % count($plantTypes)],
                'planting_year' => $currentYear - (3 + ($idx % 10)),
                'initial_class' => $initialClasses[$idx % count($initialClasses)],
                'topography' => $topographies[$idx % count($topographies)],
            ]);
        });

        foreach ($gardens as $idx => $garden) {
            $targetMin = 1800 + ($idx * 35);
            PerformanceTarget::create([
                'kebun_id' => $garden->id,
                'year' => $currentYear,
                'target_protas_min' => $targetMin,
                'target_protas_max' => $targetMin + 250,
                'note' => 'Target disusun berdasarkan historis produktivitas dan kapasitas pemetikan.',
            ]);
        }

        foreach ($gardens as $idx => $garden) {
            $month = ($idx % 10) + 1;
            $area = round(((float) $garden->luas_total_ha) * (0.55 + (($idx % 4) * 0.08)), 2);
            $wet = round($area * (1800 + ($idx * 30)) * (0.08 + (($idx % 3) * 0.01)), 2);
            ProductionRealization::create([
                'kebun_id' => $garden->id,
                'month' => $month,
                'year' => $currentYear,
                'active_picking_area_ha' => $area,
                'wet_production_kg' => $wet,
                'capacity_per_ha' => round(35 + ($idx % 6), 2),
                'avg_capacity' => round(32 + ($idx % 5), 2),
                'estimated_production' => round($wet * 1.05, 2),
                'assumption_note' => 'Estimasi mempertimbangkan cuaca dan ketersediaan tenaga petik.',
            ]);
        }

        $actionTemplates = [
            [
                'action_type' => 'cultivator',
                'coverage_target_percent' => 85,
                'rotation_per_year' => 3,
                'method' => 'mekanis',
                'focus_area' => 'jalur petik dan akses utama',
                'note' => 'Fokus perbaikan aerasi dan pengendalian gulma',
            ],
            [
                'action_type' => 'fertilizer_leaf',
                'coverage_target_percent' => 70,
                'application_interval' => '6 minggu',
                'technical_note' => 'Formulasi mikro-nutrien untuk pemulihan pucuk',
                'note' => 'Jadwal menyesuaikan curah hujan',
            ],
            [
                'action_type' => 'weed_control',
                'coverage_target_percent' => 90,
                'rotation_per_year' => 4,
                'method' => 'kombinasi manual dan mekanis',
                'note' => 'Prioritas area dengan gulma dominan',
            ],
            [
                'action_type' => 'fertilizer_root',
                'dosis_n_kg_ha' => 180,
                'n_protas_percent' => 2.25,
                'application_frequency' => 3,
                'fertilizer_type' => 'NPK 15-15-15',
                'technical_note' => 'Aplikasi bertahap untuk efisiensi serapan',
                'note' => 'Monitoring pH dan kelembaban tanah',
            ],
            [
                'action_type' => 'machine',
                'total_machine' => 6,
                'avg_machine_age' => 5.5,
                'renewal_status' => 'bertahap',
                'note' => 'Penambahan unit untuk puncak musim',
            ],
        ];

        for ($i = 0; $i < 10; $i++) {
            $garden = $gardens[$i];
            $tpl = $actionTemplates[$i % count($actionTemplates)];
            StrategicAction::create(array_merge([
                'kebun_id' => $garden->id,
                'year' => $currentYear,
            ], $tpl));
        }

        foreach ($gardens as $idx => $garden) {
            $recordDate = $now->copy()->subMonths(9 - ($idx % 10))->startOfMonth()->addDays(3 + ($idx % 10));
            ProductionData::create([
                'garden_id' => $garden->id,
                'record_date' => $recordDate->toDateString(),
                'production' => round(12000 + ($idx * 650), 2),
                'productivity' => round(1750 + ($idx * 40), 2),
                'productivity_kg_ha_year' => round(8200 + ($idx * 120), 2),
                'rkap_percentage' => round(85 + ($idx % 10), 2),
                'wet_production_kg' => round(52000 + ($idx * 1800), 2),
                'quality_score' => round(7.2 + (($idx % 5) * 0.3), 1),
                'weather_condition' => ['cerah', 'berawan', 'hujan ringan', 'hujan', 'kabut'][$idx % 5],
                'temperature_avg' => 18 + ($idx % 7),
                'rainfall_mm' => 60 + (($idx % 6) * 15),
                'humidity_percent' => 70 + (($idx % 8) * 2),
                'soil_moisture_percent' => 35 + (($idx % 10) * 2),
                'pest_incidence' => 2 + ($idx % 6),
                'disease_incidence' => 1 + ($idx % 5),
                'fertilizer_used' => 120 + ($idx % 10) * 8,
                'labor_hours' => 320 + ($idx % 10) * 25,
                'notes' => 'Pencatatan rutin untuk evaluasi produktivitas dan mutu pucuk.',
                'month' => $recordDate->format('F'),
                'year' => $recordDate->format('Y'),
            ]);
        }

        $insightTypes = ['quality_trend', 'pest_risk', 'climate_alert', 'productivity_gap', 'operational_note'];
        $alertLevels = ['low', 'medium', 'high'];

        foreach ($gardens as $idx => $garden) {
            Insight::create([
                'title' => 'Insight ' . $garden->kebun_name,
                'description' => 'Ringkasan temuan berbasis data produksi dan kondisi lapang.',
                'garden_id' => $garden->id,
                'insight_type' => $insightTypes[$idx % count($insightTypes)],
                'message' => 'Terpantau perubahan kinerja dan faktor lingkungan yang memengaruhi output. Lakukan tindak lanjut sesuai rekomendasi.',
                'alert_level' => $alertLevels[$idx % count($alertLevels)],
                'recommendations' => [
                    'Sinkronkan jadwal pemupukan dengan kondisi cuaca',
                    'Prioritaskan pengendalian gulma pada area dengan kelembaban tinggi',
                    'Pantau serangan OPT dan lakukan treatment terarah',
                ],
                'generated_at' => $now->copy()->subDays(12 - $idx),
            ]);
        }

        $visitStatuses = ['scheduled', 'completed', 'cancelled'];
        $visits = collect();
        foreach ($gardens as $idx => $garden) {
            $visitDate = $now->copy()->subDays(40 - ($idx * 3));
            $visits->push(Visit::create([
                'garden_id' => $garden->id,
                'title' => 'Kunjungan Dinas - ' . $garden->kebun_name,
                'visit_date' => $visitDate->toDateString(),
                'duration' => 4 + ($idx % 6),
                'participants_count' => 8 + ($idx % 12),
                'participants_list' => 'Tim PPTK, Manajemen Kebun, Pendamping Lapang',
                'description' => 'Kegiatan monitoring dan evaluasi pelaksanaan program kebun model.',
                'objectives' => 'Validasi data, inspeksi lapangan, dan penyelarasan rencana tindak lanjut.',
                'findings' => 'Ditemukan variasi produktivitas antar blok dan kebutuhan perbaikan SOP.',
                'recommendations' => 'Perkuat rotasi penyiangan dan konsistensi cushion pemetikan.',
                'rating' => 3 + ($idx % 3),
                'visitor_name' => $users->get(0)->name,
                'purpose' => 'Monitoring program dan data produksi',
                'status' => $visitStatuses[$idx % count($visitStatuses)],
            ]));
        }

        foreach ($visits as $visit) {
            VisitPhoto::create([
                'visit_id' => $visit->id,
                'path' => 'visit-photos/' . $visit->id . '/photo-01.jpg',
                'caption' => 'Dokumentasi kunjungan ' . $visit->visit_date->format('Y-m-d'),
            ]);
        }

        $pages = [
            [
                'slug' => 'about',
                'title' => 'Tentang Kebun Model',
                'subtitle' => 'Peran kebun model untuk peningkatan produktivitas dan mutu',
                'overview_html' => '<p>Kebun model menjadi pusat rujukan praktik budidaya dan pengukuran kinerja.</p>',
                'sejarah_html' => '<p>Inisiatif kebun model berangkat dari kebutuhan standardisasi praktik lapang.</p>',
                'tujuan_html' => '<ul><li>Produktivitas</li><li>Mutu</li><li>Keberlanjutan</li></ul>',
                'manfaat_html' => '<p>Memberikan pembelajaran cepat dan replikasi praktik baik.</p>',
                'lokasi_html' => '<p>Tersebar di berbagai wilayah operasional.</p>',
                'content_html' => '<p>Halaman ringkas kebun model.</p>',
                'hero_photo_path' => 'page-photos/about-hero.jpg',
            ],
            ['slug' => 'program', 'title' => 'Program', 'subtitle' => 'Rencana kerja tahunan', 'content_html' => '<p>Daftar program dan status pelaksanaan.</p>', 'hero_photo_path' => 'page-photos/program-hero.jpg'],
            ['slug' => 'kebun', 'title' => 'Kebun', 'subtitle' => 'Profil kebun per wilayah', 'content_html' => '<p>Profil kebun model dan pengembangan.</p>', 'hero_photo_path' => 'page-photos/kebun-hero.jpg'],
            ['slug' => 'produksi', 'title' => 'Produksi', 'subtitle' => 'Data produksi dan produktivitas', 'content_html' => '<p>Ringkasan produksi basah dan protas.</p>', 'hero_photo_path' => 'page-photos/produksi-hero.jpg'],
            ['slug' => 'insight', 'title' => 'Insight', 'subtitle' => 'Temuan dan rekomendasi', 'content_html' => '<p>Insight otomatis dan manual.</p>', 'hero_photo_path' => 'page-photos/insight-hero.jpg'],
            ['slug' => 'kunjungan', 'title' => 'Kunjungan', 'subtitle' => 'Kunjungan dinas', 'content_html' => '<p>Arsip kunjungan lapang dan dokumentasi.</p>', 'hero_photo_path' => 'page-photos/kunjungan-hero.jpg'],
            ['slug' => 'dashboard', 'title' => 'Dashboard', 'subtitle' => 'Ringkasan performa', 'content_html' => '<p>Visualisasi performa dan tren.</p>', 'hero_photo_path' => 'page-photos/dashboard-hero.jpg'],
            ['slug' => 'strategic-action', 'title' => 'Strategic Action', 'subtitle' => 'Rencana tindakan strategis', 'content_html' => '<p>Aksi strategis per kebun dan wilayah.</p>', 'hero_photo_path' => 'page-photos/strategic-hero.jpg'],
            ['slug' => 'penelitian', 'title' => 'Penelitian', 'subtitle' => 'Analisis dan pembelajaran', 'content_html' => '<p>Ringkasan penelitian dan publikasi internal.</p>', 'hero_photo_path' => 'page-photos/riset-hero.jpg'],
            ['slug' => 'kontak', 'title' => 'Kontak', 'subtitle' => 'Hubungi kami', 'content_html' => '<p>Kontak dan alamat institusi.</p>', 'hero_photo_path' => 'page-photos/kontak-hero.jpg'],
        ];

        foreach ($pages as $p) {
            Page::create(array_merge([
                'title' => null,
                'subtitle' => null,
                'content_html' => null,
                'hero_photo_path' => null,
                'overview_html' => null,
                'sejarah_html' => null,
                'tujuan_html' => null,
                'manfaat_html' => null,
                'lokasi_html' => null,
            ], $p));
        }

        $programs = [
            ['program_name' => 'Peningkatan Protas Basah', 'year' => $currentYear, 'program_type' => 'Model', 'status' => true],
            ['program_name' => 'Standardisasi SOP Pemetikan', 'year' => $currentYear, 'program_type' => 'Model', 'status' => true],
            ['program_name' => 'Optimalisasi Pemupukan Akar', 'year' => $currentYear, 'program_type' => 'Pengembangan', 'status' => true],
            ['program_name' => 'Penguatan Pengendalian OPT', 'year' => $currentYear, 'program_type' => 'Pengembangan', 'status' => true],
            ['program_name' => 'Pemetaan Mikroklimat Kebun', 'year' => $currentYear, 'program_type' => 'Model', 'status' => true],
            ['program_name' => 'Kalibrasi Mutu Pucuk', 'year' => $currentYear, 'program_type' => 'Model', 'status' => true],
            ['program_name' => 'Peremajaan Mesin Petik', 'year' => $currentYear, 'program_type' => 'Pengembangan', 'status' => true],
            ['program_name' => 'Digitalisasi Pencatatan Produksi', 'year' => $currentYear, 'program_type' => 'Model', 'status' => true],
            ['program_name' => 'Efisiensi Tenaga Kerja', 'year' => $currentYear - 1, 'program_type' => 'Pengembangan', 'status' => false],
            ['program_name' => 'Perbaikan Drainase Blok Prioritas', 'year' => $currentYear - 1, 'program_type' => 'Pengembangan', 'status' => false],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }

        // Add Research Page Seeding with Meta Data
        $researchMeta = [
            'summary' => [
                'total_activities' => 12,
                'total_budget' => 1250000000,
                'status' => 'Aktif'
            ],
            'info' => [
                'internal_research' => "1. Efektivitas Penggunaan Drone Sprayer untuk Aplikasi Pupuk Daun\n2. Pengendalian Hama Terpadu Helopeltis\n3. Analisa Global Warming Potensial (GWP) dengan Metode LCA\n4. Pengaruh Tinggi Bidang Petik Terhadap Komponen Hasil Pucuk\n5. Produksi dan Aplikasi Bio-Kompos Plus Agens Hayati",
                'external_research' => "1. Uji Adaptasi dan Stabilitas Klon Unggul Teh (Kerjasama dengan Riset Perkebunan Nusantara)",
                'incubation' => "1. Pupuk Daun Boostermax tahun ke 2\n2. Pengembangan Model Sistem Audit Energi Berbasis IoT",
                'rkap_notes' => "Realisasi anggaran hingga Triwulan III mencapai 85%. Fokus pencairan dana pada bulan Oktober untuk kegiatan inkubasi riset."
            ],
            'activities' => [
                ['title' => 'Efektivitas Penggunaan Drone Sprayer untuk Aplikasi', 'type' => 'Internal', 'year' => 2025],
                ['title' => 'Pengendalian Hama Terpadu Helopeltis', 'type' => 'Internal', 'year' => 2025],
                ['title' => 'Analisa Global Warming Potensial (GWP)', 'type' => 'Internal', 'year' => 2025],
                ['title' => 'Pengaruh Tinggi Bidang Petik', 'type' => 'Internal', 'year' => 2025],
                ['title' => 'Produksi dan Aplikasi Bio-Kompos', 'type' => 'Internal', 'year' => 2025],
                ['title' => 'Pupuk Daun Boostermax tahun ke 2', 'type' => 'Inkubasi', 'year' => 2026],
                ['title' => 'Pengembangan Model Sistem Audit Energi', 'type' => 'Internal', 'year' => 2026],
                ['title' => 'Suplemen Ekstrak Teh Hijau Rendah Kafein', 'type' => 'Internal', 'year' => 2026],
                ['title' => 'Kajian Pembaharuan Standar Fisik Budidaya', 'type' => 'Internal', 'year' => 2026],
                ['title' => 'Model Transformasi Budidaya Kerja Pekerja', 'type' => 'Internal', 'year' => 2026],
                ['title' => 'Batik Kina', 'type' => 'Internal', 'year' => 2026],
                ['title' => 'Uji Adaptasi dan Stabilitas Klon Unggul Teh', 'type' => 'Eksternal', 'year' => 2026],
            ]
        ];

        // Update or Create Research Page
        Page::updateOrCreate(
            ['slug' => 'research'],
            [
                'title' => 'Penelitian & Pengembangan',
                'subtitle' => 'Inovasi dan riset untuk keberlanjutan perkebunan teh',
                'meta' => $researchMeta,
                'files' => [],
                'hero_photo_path' => 'page-photos/riset-hero.jpg',
            ]
        );

        $cacheExpiration = $now->copy()->addHours(8)->timestamp;
        for ($i = 1; $i <= 10; $i++) {
            DB::table('cache')->insert([
                'key' => 'seed:cache:key:' . $i,
                'value' => json_encode(['type' => 'seed', 'i' => $i, 'ts' => $now->toIso8601String()]),
                'expiration' => $cacheExpiration,
            ]);
            DB::table('cache_locks')->insert([
                'key' => 'seed:lock:key:' . $i,
                'owner' => 'seeder',
                'expiration' => $cacheExpiration,
            ]);
        }

        $baseTime = $now->timestamp;
        for ($i = 1; $i <= 10; $i++) {
            DB::table('jobs')->insert([
                'queue' => 'default',
                'payload' => json_encode(['job' => 'SeededJob', 'data' => ['i' => $i]]),
                'attempts' => 0,
                'reserved_at' => null,
                'available_at' => $baseTime + ($i * 60),
                'created_at' => $baseTime - ($i * 60),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('job_batches')->insert([
                'id' => (string) Str::uuid(),
                'name' => 'Seed Batch ' . $i,
                'total_jobs' => 10,
                'pending_jobs' => max(0, 10 - $i),
                'failed_jobs' => 0,
                'failed_job_ids' => '[]',
                'options' => null,
                'cancelled_at' => null,
                'created_at' => $baseTime - ($i * 120),
                'finished_at' => null,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('failed_jobs')->insert([
                'uuid' => (string) Str::uuid(),
                'connection' => 'database',
                'queue' => 'default',
                'payload' => json_encode(['job' => 'SeededFail', 'data' => ['i' => $i]]),
                'exception' => 'Seeded exception ' . $i,
                'failed_at' => $now->copy()->subDays($i),
            ]);
        }
    }
}
