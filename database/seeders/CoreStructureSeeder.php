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
     * Data real 6 kebun PPTK Gambung:
     *   Region Jawa Barat  : Gambung, Malabar, Rancabali, Sedep
     *   Region Jawa Tengah : Kaligua
     *   Region Sumatera    : Pagaralam
     *
     * Hasil: 3 Region, 6 Garden, 18 Afdeling, 72 Block.
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
                        'kebun_name'     => 'Kebun Teh Gambung',
                        'luas_total_ha'  => 600.00,
                        'location'       => 'Desa Mekarsari, Kec. Pasirjambu, Kab. Bandung',
                        'kebun_type'     => 'Model',
                        'established_at' => '1873-01-01',
                        'coordinates'    => '-6.9022, 107.619',
                        'description'    => 'Pusat Penelitian Teh dan Kina (PPTK) Gambung. Didirikan tahun 1873 oleh Rudolf Eduard Kerkhoven. Pusat riset teh terbesar di Asia Tenggara. Mengembangkan seri klon GMB (Gambung). Memiliki 3 pabrik pengolahan: teh hitam, teh hijau, dan teh putih.',
                        'agro_climate_note' => 'Tanah andisol vulkanik, elevasi 1.200-1.450 mdpl, suhu 13-33°C, kelembaban >70%. Tipe iklim B (Schmidt & Ferguson). Berdekatan dengan Cagar Alam Gunung Tilu.',
                    ],
                    [
                        'kebun_name'     => 'Kebun Teh Malabar',
                        'luas_total_ha'  => 2022.00,
                        'location'       => 'Desa Banjarsari, Kec. Pangalengan, Kab. Bandung',
                        'kebun_type'     => 'Pengembangan',
                        'established_at' => '1890-01-01',
                        'coordinates'    => '-7.25, 107.45',
                        'description'    => 'Kebun teh terbesar ketiga di dunia. Didirikan oleh Rudolf Eduard Kerkhoven, dikelola oleh K.A.R. Bosscha ("Raja Teh Priangan") selama 32 tahun. Kapasitas produksi 50.000-60.000 kg pucuk basah per hari. 90% ekspor internasional.',
                        'agro_climate_note' => 'Tanah vulkanik, elevasi ~1.550 mdpl, suhu 16-26°C. Dikelilingi hutan pinus. Jarak ~45 km dari Bandung. Memiliki pabrik Teh Malabar dan Teh Tanara.',
                    ],
                    [
                        'kebun_name'     => 'Kebun Teh Rancabali',
                        'luas_total_ha'  => 1530.25,
                        'location'       => 'Desa Patengan, Kec. Rancabali, Kab. Bandung',
                        'kebun_type'     => 'Pengembangan',
                        'established_at' => '1870-01-01',
                        'coordinates'    => '-7.154, 107.371',
                        'description'    => 'Salah satu perkebunan teh terluas di Jawa Barat. Menghasilkan merek "Walini". Memiliki 2 pabrik: pabrik ortodoks (merek Sperata) dan pabrik CTC (merek Walini). 90% produksi diekspor terutama ke Eropa. Sertifikasi Rainforest Alliance dan UTZ.',
                        'agro_climate_note' => 'Tanah vulkanik, elevasi 1.626-1.650 mdpl, suhu rata-rata 16-26°C. Berdekatan dengan Kawah Putih dan Situ Patenggang. Produktivitas rata-rata ~2.261 kg/ha.',
                    ],
                    [
                        'kebun_name'     => 'Kebun Teh Sedep',
                        'luas_total_ha'  => 800.00,
                        'location'       => 'Desa Neglawangi, Kec. Kertasari, Kab. Bandung',
                        'kebun_type'     => 'Pengembangan',
                        'established_at' => '1880-01-01',
                        'coordinates'    => '-7.25, 107.75',
                        'description'    => 'Kebun teh warisan kolonial Belanda. Memiliki bangunan-bangunan bersejarah termasuk Rumah Administratur, bekas ruang biliar, dan prasasti peninggalan kolonial. Berdekatan dengan Kawah Papandayan.',
                        'agro_climate_note' => 'Tanah vulkanik dataran tinggi, elevasi ~1.200-1.500 mdpl. Berdekatan dengan fitur vulkanik aktif Kawah Papandayan.',
                    ],
                ],
            ],
            [
                'regional_name' => 'Wilayah Jawa Tengah',
                'regional_code' => 'REG-JATENG',
                'province'      => 'Jawa Tengah',
                'gardens'       => [
                    [
                        'kebun_name'     => 'Kebun Teh Kaligua',
                        'luas_total_ha'  => 640.00,
                        'location'       => 'Desa Pandansari, Kec. Paguyangan, Kab. Brebes',
                        'kebun_type'     => 'Model',
                        'established_at' => '1889-01-01',
                        'coordinates'    => '-7.15, 109.05',
                        'description'    => 'Kebun teh di lereng barat Gunung Slamet. Dikenal sebagai salah satu kebun teh tertinggi di Jawa. Memiliki Gua Jepang dari masa Perang Dunia II (1942). Dikembangkan sebagai agrowisata "Agrowisata Kaligua" dengan konsep "The Healing Power of Tea".',
                        'agro_climate_note' => 'Tanah vulkanik, elevasi 1.200-2.050 mdpl. Suhu 8-22°C saat musim hujan, bisa turun hingga 4°C saat kemarau. Membutuhkan jaket sepanjang tahun.',
                    ],
                ],
            ],
            [
                'regional_name' => 'Wilayah Sumatera',
                'regional_code' => 'REG-SUMATERA',
                'province'      => 'Sumatera Selatan',
                'gardens'       => [
                    [
                        'kebun_name'     => 'Kebun Teh Pagaralam',
                        'luas_total_ha'  => 1523.00,
                        'location'       => 'Lereng Gunung Dempo, Kota Pagar Alam',
                        'kebun_type'     => 'Pengembangan',
                        'established_at' => '1920-01-01',
                        'coordinates'    => '-4.02, 103.25',
                        'description'    => 'Kebun teh di lereng timur Gunung Dempo (3.142 mdpl), puncak tertinggi Sumatera Selatan. Panen teh basah tahun 2020: 15.381 ton. Separuh produksi untuk domestik, separuh ekspor. Berjarak ~300 km dari Palembang.',
                        'agro_climate_note' => 'Tanah vulkanik, elevasi ~1.520 mdpl. Iklim dataran tinggi yang sejuk, sering berkabut. Di sekitar juga menghasilkan kopi robusta.',
                    ],
                ],
            ],
        ];

        // Referensi data afdeling & blok
        $afdelingConfigs = [
            [
                'name'          => 'Afdeling Utara',
                'manager_name'  => 'Pak Slamet Riyadi',
                'area_ratio'    => 0.35,
            ],
            [
                'name'          => 'Afdeling Selatan',
                'manager_name'  => 'Pak Supriyadi',
                'area_ratio'    => 0.35,
            ],
            [
                'name'          => 'Afdeling Timur',
                'manager_name'  => 'Bu Siti Nurhaliza',
                'area_ratio'    => 0.30,
            ],
        ];

        $blockLetters    = ['A', 'B', 'C', 'D'];
        $plantTypes      = ['seedling', 'klon_gmb', 'klon_tri'];
        $topographies    = ['datar', 'gelombang', 'curam'];
        $initialClasses  = ['A', 'B', 'C', 'D', 'E'];

        foreach ($regions as $rData) {
            $gardens = $rData['gardens'];
            unset($rData['gardens']);
            $region = Region::create($rData);

            foreach ($gardens as $gData) {
                $gData['regional_id'] = $region->id;
                $garden = Garden::create($gData);

                foreach ($afdelingConfigs as $afConfig) {
                    $areaAfdeling = round($garden->luas_total_ha * $afConfig['area_ratio'], 2);

                    $afdeling = Afdeling::create([
                        'kebun_id'      => $garden->id,
                        'name'          => $afConfig['name'],
                        'manager_name'  => $afConfig['manager_name'],
                        'total_area_ha' => $areaAfdeling,
                        'tm_area_ha'    => round($areaAfdeling * 0.92, 2),
                    ]);

                    $areaPerBlock = round($areaAfdeling / count($blockLetters), 2);

                    foreach ($blockLetters as $bLetter) {
                        Block::create([
                            'afdeling_id'   => $afdeling->id,
                            'name'          => "Blok {$afConfig['name']} - {$bLetter}",
                            'code'          => "BLK-{$region->regional_code}-G{$garden->id}-A{$afdeling->id}-{$bLetter}",
                            'area_ha'       => $areaPerBlock,
                            'population'    => rand(1200, 2400),
                            'planting_year' => rand(1973, 2015),
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
