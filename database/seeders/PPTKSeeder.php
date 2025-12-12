<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\ProductionData;
use App\Models\Region;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PPTKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create regions
        $regions = [
            ['name' => 'Jawa Barat', 'province' => 'Jawa Barat', 'coordinates' => '-6.9175,107.6191'],
            ['name' => 'Sumatera Barat', 'province' => 'Sumatera Barat', 'coordinates' => '-0.7893,100.6500'],
            ['name' => 'Daerah Istimewa Yogyakarta', 'province' => 'DI Yogyakarta', 'coordinates' => '-7.7972,110.3688'],
        ];

        foreach ($regions as $regionData) {
            Region::create($regionData);
        }
        $regionMap = Region::all()->keyBy('name');

        // Create users with different roles
        $users = [
            [
                'name' => 'Admin PPTK',
                'email' => 'admin@pptk-gambung.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manager Kebun',
                'email' => 'manager@pptk-gambung.id',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Peneliti',
                'email' => 'researcher@pptk-gambung.id',
                'password' => Hash::make('researcher123'),
                'role' => 'viewer',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Create gardens with realistic data
        $gardens = [
            // Jawa Barat gardens
            [
                'region_id' => $regionMap['Jawa Barat']->id,
                'name' => 'Kebun Cikawao',
                'location' => 'Bandung',
                'address' => 'Jl. Raya Cikawao KM 45, Kecamatan Pasirjambu',
                'area' => 125.5,
                'area_hectares' => 125.5,
                'elevation' => 1200,
                'rainfall' => 2800,
                'tea_variety' => 'Assamica',
                'garden_type' => 'perkebunan',
                'coordinates' => '-7.1234,107.5678',
                'latitude' => -7.1234,
                'longitude' => 107.5678,
                'soil_ph' => 5.8,
                'soil_type' => 'Andosol',
                'drainage' => 'Baik',
                'status' => 'active'
            ],
            [
                'region_id' => $regionMap['Jawa Barat']->id,
                'name' => 'Kebun Malabar',
                'location' => 'Bandung',
                'address' => 'Jl. Malabar No. 123, Kecamatan Pangalengan',
                'area' => 98.7,
                'area_hectares' => 98.7,
                'elevation' => 1500,
                'rainfall' => 3200,
                'tea_variety' => 'Sinensis',
                'garden_type' => 'perkebunan',
                'coordinates' => '-7.2345,107.6789',
                'latitude' => -7.2345,
                'longitude' => 107.6789,
                'soil_ph' => 6.2,
                'soil_type' => 'Latosol',
                'drainage' => 'Sedang',
                'status' => 'active'
            ],
            // Sumatera Barat gardens
            [
                'region_id' => $regionMap['Sumatera Barat']->id,
                'name' => 'Kebun Gunung Talang',
                'location' => 'Solok',
                'address' => 'Jl. Solok-Akabiluru KM 67, Kecamatan Lembah Gumanti',
                'area' => 156.3,
                'area_hectares' => 156.3,
                'elevation' => 1100,
                'rainfall' => 3500,
                'tea_variety' => 'Cambodiensis',
                'garden_type' => 'perkebunan',
                'coordinates' => '-0.8912,100.4567',
                'latitude' => -0.8912,
                'longitude' => 100.4567,
                'soil_ph' => 5.5,
                'soil_type' => 'Podsolik',
                'drainage' => 'Baik',
                'status' => 'active'
            ],
            // Yogyakarta gardens
            [
                'region_id' => $regionMap['Daerah Istimewa Yogyakarta']->id,
                'name' => 'Kebun Pakem',
                'location' => 'Sleman',
                'address' => 'Jl. Kaliurang KM 25, Kecamatan Pakem',
                'area' => 87.4,
                'area_hectares' => 87.4,
                'elevation' => 800,
                'rainfall' => 2400,
                'tea_variety' => 'Hybrid',
                'garden_type' => 'penelitian',
                'coordinates' => '-7.6512,110.4234',
                'latitude' => -7.6512,
                'longitude' => 110.4234,
                'soil_ph' => 6.0,
                'soil_type' => 'Regosol',
                'drainage' => 'Baik',
                'status' => 'active'
            ],
        ];

        foreach ($gardens as $gardenData) {
            Garden::create($gardenData);
        }

        // Create production data for each garden
        $gardens = Garden::all();
        foreach ($gardens as $garden) {
            // Create 12 months of production data
            for ($month = 1; $month <= 12; $month++) {
                $baseProductivity = rand(2000, 3500); // kg/ha/year
                $seasonalFactor = sin(($month - 1) * M_PI / 6) * 0.3 + 1; // Seasonal variation
                $productivity = $baseProductivity * $seasonalFactor;
                $production = $productivity * ($garden->area ?? 0) / 1000; // Convert to tons

                ProductionData::create([
                    'garden_id' => $garden->id,
                    'record_date' => now()->subMonths(12 - $month),
                    'month' => date('F', mktime(0, 0, 0, $month, 1)),
                    'year' => now()->subMonths(12 - $month)->format('Y'),
                    'production' => round($production, 2),
                    'wet_production_kg' => round($production, 2),
                    'productivity' => round($productivity, 2),
                    'productivity_kg_ha_year' => round($productivity, 2),
                    'rkap_percentage' => rand(90, 110),
                    'quality_score' => rand(75, 95),
                    'weather_condition' => ['Cerah', 'Berawan', 'Hujan Ringan'][rand(0, 2)],
                    'temperature_avg' => rand(20, 28),
                    'rainfall_mm' => rand(150, 400),
                    'humidity_percent' => rand(70, 90),
                    'soil_moisture_percent' => rand(40, 70),
                    'pest_incidence' => rand(0, 3),
                    'disease_incidence' => rand(0, 2),
                    'fertilizer_used' => rand(50, 150),
                    'labor_hours' => rand(200, 400),
                    'notes' => 'Data produksi bulan ' . date('F', mktime(0, 0, 0, $month, 1))
                ]);
            }
        }

        // Create visits
        $visitTitles = [
            'Monitoring Produktivitas',
            'Kunjungan Dinas Pemerintah',
            'Penelitian Kualitas Daun',
            'Evaluasi Sistem Irigasi',
            'Pemantauan Hama dan Penyakit',
            'Kunjungan Akademik',
            'Inspeksi Kebun Model',
            'Penilaian Keberlanjutan'
        ];

        foreach ($gardens as $garden) {
            // Create 3-5 visits per garden
            $visitCount = rand(3, 5);
            for ($i = 0; $i < $visitCount; $i++) {
                $daysAgo = rand(1, 90);
                $visitDate = now()->subDays($daysAgo);

                Visit::create([
                    'garden_id' => $garden->id,
                    'visit_date' => $visitDate,
                    'visitor_name' => 'Dinas Pertanian',
                    'purpose' => $visitTitles[array_rand($visitTitles)],
                    'status' => 'completed'
                ]);
            }
        }

        // Create insights
        $insightTitles = [
            'Produktivitas Tinggi Terdeteksi',
            'Kebutuhan Pemangkasan Mendesak',
            'Kualitas Daun Menurun',
            'Optimalisasi Pemupukan Diperlukan',
            'Sistem Drainase Perlu Perbaikan',
            'Hama Penggerek Daun Teridentifikasi',
            'Kelembaban Tanah Tidak Optimal',
            'Temperatur Melebihi Ambang Batas'
        ];

        $recommendations = [
            'Tingkatkan dosis pupuk nitrogen',
            'Lakukan pemangkasan secara teratur',
            'Evaluasi sistem irigasi',
            'Monitor kelembaban tanah harian',
            'Terapkan pengendalian hama terpadu',
            'Perbaiki sistem drainase',
            'Tambahkan pupuk organik',
            'Naikkan frekuensi monitoring'
        ];

        foreach ($gardens as $garden) {
            // Create 2-4 insights per garden
            $insightCount = rand(2, 4);
            for ($i = 0; $i < $insightCount; $i++) {
                $alertLevels = ['low', 'medium', 'high'];
                $alertLevel = $alertLevels[array_rand($alertLevels)];
                $title = $insightTitles[array_rand($insightTitles)];

                Insight::create([
                    'garden_id' => $garden->id,
                    'title' => $title,
                    'description' => 'Analisis data menunjukkan ' . strtolower($title) . ' di kebun ' . $garden->name,
                    'insight_type' => \Illuminate\Support\Str::slug($title, '_'),
                    'message' => 'Analisis: ' . $title . ' pada ' . $garden->name,
                    'alert_level' => $alertLevel,
                    'recommendations' => json_encode([$recommendations[array_rand($recommendations)]]),
                    'created_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }
    }
}
