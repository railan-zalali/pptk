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
        $years = [2025, 2026];

        foreach ($gardens as $garden) {
            foreach ($years as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    // Skip future months for current year
                    if ($year == 2026 && $month > 2) break;

                    $date = Carbon::create($year, $month, 1);
                    
                    // Base random factor for variety
                    $baseProd = ($garden->luas_total_ha * 100) * (rand(80, 120) / 100); // ~100kg/ha * area
                    
                    // Seed ProductionRealization (Detailed)
                    ProductionRealization::create([
                        'kebun_id' => $garden->id,
                        'month' => $month,
                        'year' => $year,
                        'active_picking_area_ha' => $garden->luas_total_ha * 0.9,
                        'wet_production_kg' => $baseProd,
                        'capacity_per_ha' => rand(30, 40),
                        'avg_capacity' => rand(30, 40),
                        'estimated_production' => $baseProd * 1.05, // Forecast slightly higher
                        'quality_score' => rand(70, 95) / 10, // 7.0 - 9.5
                        'assumption_note' => 'Cuaca mendukung, curah hujan cukup.',
                    ]);
                }
            }
        }
    }
}
