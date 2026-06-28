<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Garden;
use App\Models\Afdeling;
use App\Models\Block;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $regionsData = [
            [
                'regional_name' => 'Wilayah Jawa Barat',
                'regional_code' => 'REG-JABAR',
                'province' => 'Jawa Barat',
                'gardens' => [
                    [
                        'kebun_name' => 'Kebun Teh Rancabali',
                        'luas_total_ha' => 1250.50,
                        'location' => 'Ciwidey, Bandung',
                        'kebun_type' => 'Model',
                        'established_at' => '1985-03-15',
                        'description' => 'Kebun teh model dengan teknologi budidaya modern',
                    ],
                    [
                        'kebun_name' => 'Kebun Teh Malabar',
                        'luas_total_ha' => 1800.75,
                        'location' => 'Pangalengan, Bandung',
                        'kebun_type' => 'Produksi',
                        'established_at' => '1972-08-20',
                        'description' => 'Kebun teh produksi dengan luas terbesar di wilayah',
                    ],
                ],
            ],
            [
                'regional_name' => 'Wilayah Jawa Tengah',
                'regional_code' => 'REG-JATENG',
                'province' => 'Jawa Tengah',
                'gardens' => [
                    [
                        'kebun_name' => 'Kebun Teh Sedep',
                        'luas_total_ha' => 950.25,
                        'location' => 'Kertasari, Wonosobo',
                        'kebun_type' => 'Produksi',
                        'established_at' => '1990-11-05',
                        'description' => 'Kebun teh dengan varietas unggulan',
                    ],
                    [
                        'kebun_name' => 'Kebun Teh Pasir Malang',
                        'luas_total_ha' => 780.00,
                        'location' => 'Pangalengan, Bandung',
                        'kebun_type' => 'Riset',
                        'established_at' => '2005-01-10',
                        'description' => 'Kebun riset untuk pengembangan varietas baru',
                    ],
                ],
            ],
        ];

        foreach ($regionsData as $regionData) {
            $gardens = $regionData['gardens'];
            unset($regionData['gardens']);

            $region = Region::create($regionData);

            foreach ($gardens as $gardenData) {
                $gardenData['regional_id'] = $region->id;
                $garden = Garden::create($gardenData);

                $afdelings = [
                    ['name' => 'Afdeling Utara', 'manager_name' => 'Pak Slamet'],
                    ['name' => 'Afdeling Selatan', 'manager_name' => 'Pak Supriyadi'],
                    ['name' => 'Afdeling Timur', 'manager_name' => 'Bu Siti'],
                ];

                foreach ($afdelings as $idx => $afdelingData) {
                    $afdelingData['kebun_id'] = $garden->id;
                    $afdelingData['total_area_ha'] = round($garden->luas_total_ha / count($afdelings), 2);
                    $afdelingData['tm_area_ha'] = round($afdelingData['total_area_ha'] * 0.92, 2);
                    $afdeling = Afdeling::create($afdelingData);

                    $blockNames = ['A', 'B', 'C', 'D'];
                    $plantTypes = ['klon_gmb', 'klon_tri', 'klon_rh'];
                    $topographies = ['datar', 'gelombang', 'lereng'];
                    $initialClasses = ['A', 'B', 'C'];

                    foreach ($blockNames as $blockName) {
                        Block::create([
                            'afdeling_id' => $afdeling->id,
                            'name' => "Blok {$afdelingData['name']} - {$blockName}",
                            'code' => "{$garden->id}-{$afdeling->id}-{$blockName}",
                            'area_ha' => round($afdelingData['total_area_ha'] / count($blockNames), 2),
                            'population' => rand(1000, 2500),
                            'plant_type' => $plantTypes[array_rand($plantTypes)],
                            'planting_year' => rand(1985, 2020),
                            'initial_class' => $initialClasses[array_rand($initialClasses)],
                            'topography' => $topographies[array_rand($topographies)],
                        ]);
                    }
                }
            }
        }

        $this->command->info('MasterDataSeeder: Data master berhasil di-seed.');
    }
}
