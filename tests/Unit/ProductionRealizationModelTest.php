<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ProductionRealization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductionRealizationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_productivity_wet_accessor(): void
    {
        $realization = new ProductionRealization([
            'wet_production_kg' => 10000.0,
            'active_picking_area_ha' => 50.0,
        ]);

        // wet_production_kg / active_picking_area_ha = 200.0
        $this->assertEquals(200.0, $realization->productivity_wet);

        // Division by zero safeguard
        $zeroArea = new ProductionRealization([
            'wet_production_kg' => 10000.0,
            'active_picking_area_ha' => 0.0,
        ]);
        $this->assertEquals(0.0, $zeroArea->productivity_wet);
    }

    public function test_productivity_dry_accessor(): void
    {
        $realization = new ProductionRealization([
            'wet_production_kg' => 10000.0,
            'active_picking_area_ha' => 50.0,
        ]);

        // wet = 200.0, dry = 200.0 * 22% = 44.0
        $this->assertEquals(44.0, $realization->productivity_dry);
    }

    public function test_rkap_percentage_accessor(): void
    {
        $realization = new ProductionRealization([
            'wet_production_kg' => 8000.0,
            'estimated_production' => 10000.0,
        ]);

        // 8000 / 10000 * 100 = 80.0%
        $this->assertEquals(80.0, $realization->rkap_percentage);

        // Division by zero safeguard
        $zeroEstimated = new ProductionRealization([
            'wet_production_kg' => 8000.0,
            'estimated_production' => 0.0,
        ]);
        $this->assertEquals(0.0, $zeroEstimated->rkap_percentage);
    }

    public function test_month_name_accessor(): void
    {
        $realization = new ProductionRealization(['month' => 5]);
        $this->assertEquals('Mei', $realization->month_name);

        $realizationInvalid = new ProductionRealization(['month' => 13]);
        $this->assertEquals('-', $realizationInvalid->month_name);
    }
}
