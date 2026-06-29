<?php

namespace Database\Seeders;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\Garden;
use App\Models\Region;
use Illuminate\Database\Seeder;

class CoreStructureSeeder extends Seeder
{
    /**
     * Seed struktur inti kebun: Wilayah → Kebun → Afdeling → Blok.
     *
     * Hasil: 2 Region, 4 Garden, 12 Afdeling, 48 Block.
     */
    public function run(): void
    {
        $regions = [
            [
                'regional_name' => 'Wilayah Jawa Barat',
                'regional_code' => 'REG-JABAR',
                'province'      => 'Jawa Barat',
                'gardens'       => [
                    [
                        'kebun_name'    => 'Kebun Teh Rancabali',
                        'luas_total_ha' => 1250.50,
                        'location'      => 'Ciwidey, Bandung',
                        'kebun_type'    => 'Model',
                        'established_at'=> '1985-03-15',
                        'description'   => 'Kebun teh model dengan teknologi budidaya modern dan varietas unggulan.',
                    ],
                    [
                        'kebun_name'    => 'Kebun Teh Malabar',
                        'luas_total_ha' => 1800.75,
                        'location'      => 'Pangalengan, Bandung',
                        'kebun_type'    => 'Pengembangan',
                        'established_at'=> '1972-08-20',
                        'description'   => 'Kebun teh pengembangan dengan produktivitas tinggi dan lahan terluas.',
                    ],
                ],
            ],
            [
                'regional_name' => 'Wilayah Jawa Tengah',
                'regional_code' => 'REG-JATENG',
                'province'      => 'Jawa Tengah',
                'gardens'       => [
                    [
                        'kebun_name'    => 'Kebun Teh Sedep',
                        'luas_total_ha' => 1100.25,
                        'location'      => 'Kertasari, Wonosobo',
                        'kebun_type'    => 'Pengembangan',
                        'established_at'=> '1990-11-05',
                        'description'   => 'Kebun teh pengembangan dengan fokus produksi teh hijau berkualitas.',
                    ],
                    [
                        'kebun_name'    => 'Kebun Teh Pasir Malang',
                        'luas_total_ha' => 950.00,
                        'location'      => 'Temanggung, Jawa Tengah',
                        'kebun_type'    => 'Model',
                        'established_at'=> '2005-01-10',
                        'description'   => 'Kebun model untuk pengembangan varietas baru dan teknologi budidaya terkini.',
                    ],
                ],
            ],
        ];

        // Referensi data afdeling & blok
        $afdelingNames   = ['Afdeling Utara', 'Afdeling Selatan', 'Afdeling Timur'];
        $afdelingManagers= ['Pak Slamet', 'Pak Supriyadi', 'Bu Siti'];
        $blockLetters    = ['A', 'B', 'C', 'D'];
        $plantTypes      = ['seedling', 'klon_gmb', 'klon_tri']; // sesuai ENUM migration
        $topographies    = ['datar', 'gelombang', 'curam'];       // sesuai ENUM migration
        $initialClasses  = ['A', 'B', 'C', 'D', 'E'];

        foreach ($regions as $rData) {
            $gardens = $rData['gardens'];
            unset($rData['gardens']);
            $region = Region::create($rData);

            foreach ($gardens as $gData) {
                $gData['regional_id'] = $region->id;
                $garden = Garden::create($gData);

                $areaPerAfdeling = round($garden->luas_total_ha / count($afdelingNames), 2);

                foreach ($afdelingNames as $idx => $afName) {
                    $afdeling = Afdeling::create([
                        'kebun_id'      => $garden->id,
                        'name'          => $afName,
                        'manager_name'  => $afdelingManagers[$idx],
                        'total_area_ha' => $areaPerAfdeling,
                        'tm_area_ha'    => round($areaPerAfdeling * 0.92, 2),
                    ]);

                    $areaPerBlock = round($areaPerAfdeling / count($blockLetters), 2);

                    foreach ($blockLetters as $bLetter) {
                        Block::create([
                            'afdeling_id'   => $afdeling->id,
                            'name'          => "Blok {$afName} - {$bLetter}",
                            'code'          => "BLK-{$region->regional_code}-G{$garden->id}-A{$afdeling->id}-{$bLetter}",
                            'area_ha'       => $areaPerBlock,
                            'population'    => rand(1200, 2400),
                            'planting_year' => rand(1985, 2020),
                            'plant_type'    => $plantTypes[array_rand($plantTypes)],
                            'initial_class' => $initialClasses[array_rand($initialClasses)],
                            'topography'    => $topographies[array_rand($topographies)],
                        ]);
                    }
                }
            }
        }

        $this->command->info(sprintf(
            'CoreStructureSeeder: %d region, %d garden, %d afdeling, %d blok berhasil di-seed.',
            Region::count(), Garden::count(), Afdeling::count(), Block::count()
        ));
    }
}
