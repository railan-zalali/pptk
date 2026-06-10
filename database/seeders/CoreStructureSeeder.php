<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Garden;
use App\Models\Afdeling;
use App\Models\Block;
use Illuminate\Database\Seeder;

class CoreStructureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Regions
        $regions = [
            [
                'regional_name' => 'Wilayah I (Barat)',
                'regional_code' => 'REG-01',
                'gardens' => [
                    ['kebun_name' => 'Kebun Rancabali', 'luas_total_ha' => 1200.50, 'location' => 'Ciwidey, Bandung'],
                    ['kebun_name' => 'Kebun Malabar', 'luas_total_ha' => 1500.75, 'location' => 'Pangalengan, Bandung'],
                ]
            ],
            [
                'regional_name' => 'Wilayah II (Timur)',
                'regional_code' => 'REG-02',
                'gardens' => [
                    ['kebun_name' => 'Kebun Sedep', 'luas_total_ha' => 1100.25, 'location' => 'Kertasari, Bandung'],
                    ['kebun_name' => 'Kebun Pasir Malang', 'luas_total_ha' => 950.00, 'location' => 'Pangalengan, Bandung'],
                ]
            ],
        ];

        foreach ($regions as $rData) {
            $region = Region::create([
                'regional_name' => $rData['regional_name'],
                'regional_code' => $rData['regional_code'],
                'province'      => 'Jawa Barat',
            ]);

            foreach ($rData['gardens'] as $gData) {
                $garden = Garden::create([
                    'regional_id' => $region->id,
                    'kebun_name' => $gData['kebun_name'],
                    'luas_total_ha' => $gData['luas_total_ha'],
                    'location' => $gData['location'],
                    'kebun_type' => 'Model', // Default
                ]);

                // 2. Afdelings (2 per Garden)
                $afdelings = ['Afdeling A', 'Afdeling B'];
                foreach ($afdelings as $afName) {
                    $afdeling = Afdeling::create([
                        'kebun_id' => $garden->id,
                        'name' => $afName,
                        'total_area_ha' => $garden->luas_total_ha / 2, // Simplified
                        'tm_area_ha' => ($garden->luas_total_ha / 2) * 0.9,
                    ]);

                    // 3. Blocks (3 per Afdeling)
                    for ($i = 1; $i <= 3; $i++) {
                        Block::create([
                            'afdeling_id' => $afdeling->id,
                            'name' => "Blok {$afName}-{$i}",
                            'code' => "BLK-{$garden->id}-{$afdeling->id}-{$i}",
                            'planting_year' => rand(1990, 2015),
                            'plant_type' => ['klon_gmb', 'klon_tri'][rand(0, 1)],
                            'initial_class' => ['A', 'B', 'C'][rand(0, 2)],
                            'topography' => ['datar', 'gelombang'][rand(0, 1)],
                        ]);
                    }
                }
            }
        }
    }
}
