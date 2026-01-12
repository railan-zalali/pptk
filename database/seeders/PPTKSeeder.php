<?php

namespace Database\Seeders;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\Garden;
use App\Models\GardenPhoto;
use App\Models\Insight;
use App\Models\Page;
use App\Models\ProductionData;
use App\Models\Region;
use App\Models\RegionPhoto;
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
            ['regional_name' => 'Jawa Barat', 'regional_code' => 'REG-JB', 'province' => 'Jawa Barat', 'coordinates' => '-6.9175,107.6191'],
            ['regional_name' => 'Sumatera Barat', 'regional_code' => 'REG-SB', 'province' => 'Sumatera Barat', 'coordinates' => '-0.7893,100.6500'],
            ['regional_name' => 'Daerah Istimewa Yogyakarta', 'regional_code' => 'REG-DIY', 'province' => 'DI Yogyakarta', 'coordinates' => '-7.7972,110.3688'],
        ];

        foreach ($regions as $regionData) {
            Region::firstOrCreate(['regional_code' => $regionData['regional_code']], $regionData);
        }
        $regionMap = Region::all()->keyBy('regional_name');

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
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // Create gardens with realistic data
        $gardens = [
            // Jawa Barat gardens
            [
                'regional_id' => $regionMap['Jawa Barat']->id,
                'kebun_name' => 'Kebun Cikawao',
                'location' => 'Bandung',
                'luas_total_ha' => 125.5,
                'kebun_type' => 'Model',
                'agro_climate_note' => 'Elevation 1200m, Rainfall 2800mm, Soil Andosol',
                'description' => 'Kebun teh model dengan produktivitas tinggi',
                'established_at' => '1980-01-01',
            ],
            [
                'regional_id' => $regionMap['Jawa Barat']->id,
                'kebun_name' => 'Kebun Malabar',
                'location' => 'Bandung',
                'luas_total_ha' => 98.7,
                'kebun_type' => 'Pengembangan',
                'agro_climate_note' => 'Elevation 1500m, Rainfall 3200mm, Soil Latosol',
                'description' => 'Kebun pengembangan varietas baru',
                'established_at' => '1990-01-01',
            ],
            // Sumatera Barat gardens
            [
                'regional_id' => $regionMap['Sumatera Barat']->id,
                'kebun_name' => 'Kebun Gunung Talang',
                'location' => 'Solok',
                'luas_total_ha' => 156.3,
                'kebun_type' => 'Model',
                'agro_climate_note' => 'Elevation 1100m, Rainfall 3500mm, Soil Podsolik',
                'description' => 'Kebun model di dataran tinggi Sumatera',
                'established_at' => '1985-01-01',
            ],
            // Yogyakarta gardens
            [
                'regional_id' => $regionMap['Daerah Istimewa Yogyakarta']->id,
                'kebun_name' => 'Kebun Pakem',
                'location' => 'Sleman',
                'luas_total_ha' => 87.4,
                'kebun_type' => 'Pengembangan',
                'agro_climate_note' => 'Elevation 800m, Rainfall 2400mm, Soil Regosol',
                'description' => 'Kebun penelitian klon',
                'established_at' => '2000-01-01',
            ],
        ];

        foreach ($gardens as $gardenData) {
            Garden::firstOrCreate(['kebun_name' => $gardenData['kebun_name']], $gardenData);
        }

        // Create visits
        $gardens = Garden::all();
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
                    'title' => $visitTitles[rand(0, count($visitTitles) - 1)],
                    'visit_date' => $visitDate,
                    'duration' => rand(1, 5),
                    'visitor_name' => 'Visitor ' . rand(1, 100),
                    'purpose' => 'Kunjungan rutin dan evaluasi',
                    'participants_count' => rand(2, 10),
                    'participants_list' => 'Peserta A, Peserta B, Peserta C',
                    'description' => 'Detail kunjungan dan observasi lapangan mengenai kondisi tanaman dan infrastruktur.',
                    'objectives' => 'Mengevaluasi kinerja kebun dan memberikan rekomendasi perbaikan.',
                    'findings' => 'Kondisi tanaman baik, namun perlu perbaikan sistem irigasi di blok A.',
                    'recommendations' => 'Lakukan perbaikan irigasi segera dan tingkatkan pemupukan.',
                    'rating' => rand(3, 5),
                    'status' => 'completed'
                ]);
            }

            // Create Afdeling and Blocks
            $afdelingCount = rand(2, 3);
            for ($a = 0; $a < $afdelingCount; $a++) {
                $afdeling = Afdeling::create([
                    'garden_id' => $garden->id,
                    'name' => 'Afdeling ' . ($a + 1),
                    'total_area_ha' => $garden->luas_total_ha / $afdelingCount,
                    'tm_area_ha' => ($garden->luas_total_ha / $afdelingCount) * 0.8,
                    'manager_name' => 'Manager Afdeling ' . ($a + 1),
                ]);

                // Create Blocks for each Afdeling
                $blockCount = rand(3, 5);
                for ($b = 0; $b < $blockCount; $b++) {
                    Block::create([
                        'afdeling_id' => $afdeling->id,
                        'name' => 'Blok ' . chr(65 + $b),
                        'code' => 'BL-' . $afdeling->id . '-' . chr(65 + $b),
                        'plant_type' => ['seedling', 'klon_gmb', 'klon_tri'][rand(0, 2)],
                        'planting_year' => rand(1990, 2020),
                        'initial_class' => ['A', 'B', 'C', 'D', 'E'][rand(0, 4)],
                        'topography' => ['datar', 'gelombang', 'curam'][rand(0, 2)],
                    ]);
                }
            }

            // Create Insights
            $insightTypes = ['productivity', 'weather', 'pest_disease', 'cost_efficiency'];
            $alertLevels = ['low', 'medium', 'high'];

            for ($j = 0; $j < rand(2, 4); $j++) {
                Insight::create([
                    'garden_id' => $garden->id,
                    'title' => 'Insight ' . ($j + 1) . ' for ' . $garden->kebun_name,
                    'description' => 'Detailed analysis and observation for this insight.',
                    'insight_type' => $insightTypes[rand(0, count($insightTypes) - 1)],
                    'message' => 'Insight message content regarding current conditions.',
                    'alert_level' => $alertLevels[rand(0, count($alertLevels) - 1)],
                    'recommendations' => ['Check soil moisture', 'Increase fertilizer dosage', 'Monitor pest activity'],
                    'generated_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Create Garden Photos
            for ($k = 0; $k < rand(2, 3); $k++) {
                GardenPhoto::create([
                    'garden_id' => $garden->id,
                    'path' => 'gardens/default_garden.jpg', // Placeholder path
                    'title' => 'Photo ' . ($k + 1),
                    'description' => 'View of the tea plantation block ' . chr(65 + $k),
                ]);
            }
        }

        // Create Region Photos
        foreach (Region::all() as $region) {
            for ($m = 0; $m < rand(1, 2); $m++) {
                RegionPhoto::create([
                    'region_id' => $region->id,
                    'path' => 'regions/default_region.jpg', // Placeholder path
                ]);
            }
        }

        // Create Pages
        $pages = [
            [
                'slug' => 'about',
                'title' => 'Tentang Kami',
                'subtitle' => 'Sejarah dan Visi Misi PPTK',
                'content_html' => '<p>Pusat Penelitian Teh dan Kina (PPTK) Gambung adalah lembaga penelitian...</p>',
                'hero_photo_path' => 'pages/about-hero.jpg',
            ],
            [
                'slug' => 'contact',
                'title' => 'Hubungi Kami',
                'subtitle' => 'Informasi Kontak dan Lokasi',
                'content_html' => '<p>Hubungi kami melalui email atau telepon...</p>',
                'hero_photo_path' => 'pages/contact-hero.jpg',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(['slug' => $pageData['slug']], $pageData);
        }
    }
}
