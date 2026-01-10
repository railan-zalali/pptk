<?php

namespace Database\Seeders;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Region;
use App\Models\StrategicAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrategicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Region Exists
        $region = Region::firstOrCreate(
            ['name' => 'Jawa Barat'],
            ['province' => 'Jawa Barat', 'coordinates' => '-7.123, 107.456']
        );

        // 2. Create Garden (Kebun Malabar)
        $garden = Garden::firstOrCreate(
            ['name' => 'Kebun Malabar'],
            [
                'region_id' => $region->id,
                'location' => 'Pangalengan',
                'area_hectares' => 500.00,
                'description' => 'Kebun teh historis di Pangalengan',
                'established_at' => '1900-01-01',
                'status' => 'active',
                'tea_variety' => 'Assamica',
                'elevation' => 1500,
                'soil_ph' => 5.5,
            ]
        );

        // 3. Create Afdelings
        $afdelingNames = ['Afdeling Tanara', 'Afdeling Kertasari', 'Afdeling Purbasari'];
        foreach ($afdelingNames as $index => $name) {
            $afdeling = Afdeling::create([
                'garden_id' => $garden->id,
                'name' => $name,
                'total_area_ha' => 150.00,
                'tm_area_ha' => 120.00, // 80% TM
                'manager_name' => 'Manager ' . ($index + 1),
            ]);

            // 4. Create Blocks
            $classes = ['A', 'B', 'C', 'D', 'E'];
            $topographies = ['datar', 'gelombang', 'curam'];
            
            for ($i = 1; $i <= 5; $i++) {
                $block = Block::create([
                    'afdeling_id' => $afdeling->id,
                    'name' => 'Blok ' . $afdeling->id . '-' . $i,
                    'code' => 'BLK-' . $afdeling->id . '-' . $i,
                    'plant_type' => ['seedling', 'klon_gmb', 'klon_tri'][rand(0, 2)],
                    'planting_year' => rand(1990, 2015),
                    'initial_class' => $classes[rand(0, 4)],
                    'topography' => $topographies[rand(0, 2)],
                ]);

                // 5. Create Strategic Actions (Randomly)
                if (rand(0, 1)) {
                    StrategicAction::create([
                        'block_id' => $block->id,
                        'period' => now()->subMonths(rand(1, 6)),
                        'action_type' => 'fertilizer_root',
                        'target_volume' => 500,
                        'realization_volume' => rand(400, 500),
                        'nitrogen_content' => 46, // Urea
                        'notes' => 'Pemupukan rutin semester 1',
                    ]);
                }
                
                if (rand(0, 1)) {
                     StrategicAction::create([
                        'block_id' => $block->id,
                        'period' => now()->subMonths(rand(1, 3)),
                        'action_type' => 'cultivator',
                        'target_volume' => 10, // Ha
                        'realization_volume' => rand(5, 10),
                        'notes' => 'Penggemburan tanah',
                    ]);
                }
            }

            // 6. Create Production Realization (Last 12 Months)
            for ($m = 0; $m < 12; $m++) {
                $date = now()->subMonths($m)->startOfMonth();
                $harvestedArea = 120; // Full TM area
                $productivity = rand(100, 200); // Kg/Ha/Month (approx)
                $wetYield = $harvestedArea * $productivity;
                $dryYield = $wetYield * 0.22; // 22% rendemen
                
                ProductionRealization::create([
                    'afdeling_id' => $afdeling->id,
                    'date' => $date,
                    'harvested_area_ha' => $harvestedArea,
                    'wet_yield_kg' => $wetYield,
                    'dry_yield_kg' => $dryYield,
                    'manpower_count' => rand(50, 80),
                    'effective_days' => 24,
                ]);
            }

            // 7. Create Performance Target
            PerformanceTarget::create([
                'year' => now()->year,
                'afdeling_id' => $afdeling->id,
                'target_protas_kg_ha' => 2500, // Annual
                'target_yield_kg' => 2500 * 120,
            ]);
        }
    }
}
