<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\ProductionRealization;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed data realisasi produksi bulanan untuk setiap kebun.
     *
     * - Tahun: 2024 dan 2025
     * - Faktor musiman: produksi lebih tinggi di awal/akhir tahun (musim hujan)
     * - Skip bulan yang belum terjadi (masa depan)
     */
    public function run(): void
    {
        // Faktor musiman produksi teh Indonesia
        // Lebih tinggi Jan-Mar (musim hujan) dan Nov-Des
        $seasonalFactors = [
            1  => 1.20, 2  => 1.15, 3  => 1.05,
            4  => 0.90, 5  => 0.85, 6  => 0.80,
            7  => 0.85, 8  => 0.90, 9  => 1.00,
            10 => 1.10, 11 => 1.15, 12 => 1.25,
        ];

        $now     = Carbon::now();
        $gardens = Garden::all();
        $years   = [2024, 2025];
        $count   = 0;

        foreach ($gardens as $garden) {
            $activeArea  = round($garden->luas_total_ha * 0.92, 2); // 92% area TM (Tanaman Menghasilkan)
            $baseProdHa  = 110; // kg/ha rata-rata nasional

            foreach ($years as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    // Skip bulan yang belum terjadi
                    if ($year === $now->year && $month > $now->month) {
                        continue;
                    }

                    $seasonal      = $seasonalFactors[$month];
                    $random        = 0.90 + (mt_rand() / mt_getrandmax()) * 0.20; // ±10%
                    $wetProduction = $baseProdHa * $activeArea * $seasonal * $random;
                    $qualityScore  = round(7.0 + (mt_rand() / mt_getrandmax()) * 2.5, 2);

                    ProductionRealization::create([
                        'kebun_id'               => $garden->id,
                        'month'                  => $month,
                        'year'                   => $year,
                        'active_picking_area_ha' => $activeArea,
                        'wet_production_kg'      => round($wetProduction, 2),
                        'capacity_per_ha'        => round(28 + (mt_rand() / mt_getrandmax()) * 18, 2),
                        'avg_capacity'           => round(30 + (mt_rand() / mt_getrandmax()) * 15, 2),
                        'estimated_production'   => round($wetProduction * 1.05, 2),
                        'quality_score'          => $qualityScore,
                        'assumption_note'        => sprintf(
                            'Produksi %s %d. Curah hujan %s. Faktor musiman %.2f.',
                            Carbon::create($year, $month)->translatedFormat('F'),
                            $year,
                            ($month <= 3 || $month >= 10) ? 'tinggi' : 'sedang',
                            $seasonal
                        ),
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("ProductionSeeder: {$count} record realisasi produksi berhasil di-seed.");
    }
}
