<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\ProductionRealization;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $gardens = Garden::all();
        $years = [2024, 2025];
        
        // Seasonal factors for Indonesian tea production (higher at start/end of year)
        $seasonalFactors = [
            1 => 1.2,
            2 => 1.15,
            3 => 1.05,
            4 => 0.9,
            5 => 0.85,
            6 => 0.8,
            7 => 0.85,
            8 => 0.9,
            9 => 1.0,
            10 => 1.1,
            11 => 1.15,
            12 => 1.25,
        ];

        foreach ($gardens as $garden) {
            foreach ($years as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    // Skip future months for current year
                    if ($year == Carbon::now()->year && $month > Carbon::now()->month) {
                        continue;
                    }
                    
                    $baseProdPerHa = 110; // kg/ha
                    $seasonalFactor = $seasonalFactors[$month];
                    $randomFactor = 0.9 + (mt_rand() / mt_getrandmax()) * 0.2; // 0.9 - 1.1
                    $activeArea = $garden->luas_total_ha * 0.92;
                    $wetProduction = ($baseProdPerHa * $activeArea) * $seasonalFactor * $randomFactor;

                    ProductionRealization::create([
                        'kebun_id' => $garden->id,
                        'month' => $month,
                        'year' => $year,
                        'active_picking_area_ha' => round($activeArea, 2),
                        'wet_production_kg' => round($wetProduction, 2),
                        'capacity_per_ha' => round(30 + (mt_rand() / mt_getrandmax()) * 15, 2),
                        'avg_capacity' => round(32 + (mt_rand() / mt_getrandmax()) * 12, 2),
                        'estimated_production' => round($wetProduction * 1.05, 2),
                        'quality_score' => round(7.5 + (mt_rand() / mt_getrandmax()) * 2.0, 2),
                        'assumption_note' => 'Produksi sesuai dengan tren musiman tahun ini. Curah hujan ' . ($month <= 3 || $month >= 10 ? 'tinggi' : 'sedang') . '.',
                    ]);
                }
            }
        }
        
        $this->command->info('ProductionSeeder: Data realisasi produksi berhasil di-seed dengan tren musiman.');
    }
}
