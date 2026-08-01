<?php

namespace Tests\Unit;

use App\Models\ProductionRealization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionRealizationModelTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // productivity_wet accessor
    // ----------------------------------------------------------------

    public function test_productivity_wet_calculated_correctly(): void
    {
        $realization = new ProductionRealization([
            'active_picking_area_ha' => 100,
            'wet_production_kg'      => 20000,
        ]);

        $this->assertEquals(200.0, $realization->productivity_wet);
    }

    public function test_productivity_wet_returns_zero_when_no_area(): void
    {
        $realization = new ProductionRealization([
            'active_picking_area_ha' => 0,
            'wet_production_kg'      => 20000,
        ]);

        $this->assertEquals(0, $realization->productivity_wet);
    }

    public function test_productivity_wet_returns_zero_when_area_null(): void
    {
        $realization = new ProductionRealization([
            'active_picking_area_ha' => null,
            'wet_production_kg'      => 20000,
        ]);

        $this->assertEquals(0, $realization->productivity_wet);
    }

    // ----------------------------------------------------------------
    // productivity_dry accessor — BUG FIX: gunakan dry_production_kg aktual
    // ----------------------------------------------------------------

    public function test_productivity_dry_uses_actual_dry_column_when_available(): void
    {
        // Kolom dry_production_kg tersedia → harus digunakan, bukan estimasi 22%
        $realization = new ProductionRealization([
            'active_picking_area_ha' => 100,
            'wet_production_kg'      => 50000,
            'dry_production_kg'      => 12000,  // aktual, bukan 22% dari wet (11000)
        ]);

        $this->assertEquals(120.0, $realization->productivity_dry);
    }

    public function test_productivity_dry_falls_back_to_22pct_when_dry_null(): void
    {
        // dry_production_kg null → fallback ke estimasi 22%
        $realization = new ProductionRealization([
            'active_picking_area_ha' => 100,
            'wet_production_kg'      => 10000,
            'dry_production_kg'      => null,
        ]);

        // productivity_wet = 100, 22% dari 100 = 22
        $this->assertEqualsWithDelta(22.0, $realization->productivity_dry, 0.01);
    }

    public function test_productivity_dry_falls_back_to_22pct_when_dry_zero(): void
    {
        // dry_production_kg = 0 → dianggap tidak ada data → fallback ke estimasi
        $realization = new ProductionRealization([
            'active_picking_area_ha' => 100,
            'wet_production_kg'      => 10000,
            'dry_production_kg'      => 0,
        ]);

        $this->assertEqualsWithDelta(22.0, $realization->productivity_dry, 0.01);
    }

    public function test_productivity_dry_returns_zero_when_no_area(): void
    {
        $realization = new ProductionRealization([
            'active_picking_area_ha' => 0,
            'wet_production_kg'      => 10000,
            'dry_production_kg'      => 5000,
        ]);

        $this->assertEquals(0, $realization->productivity_dry);
    }

    // ----------------------------------------------------------------
    // Casts — memastikan nilai numeric dikembalikan dengan tipe benar
    // ----------------------------------------------------------------

    public function test_month_cast_to_integer(): void
    {
        $realization = new ProductionRealization(['month' => '3']);

        $this->assertIsInt($realization->month);
        $this->assertEquals(3, $realization->month);
    }

    public function test_year_cast_to_integer(): void
    {
        $realization = new ProductionRealization(['year' => '2025']);

        $this->assertIsInt($realization->year);
        $this->assertEquals(2025, $realization->year);
    }
}
