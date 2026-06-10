<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Region;
use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use App\Services\DashboardStatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class DashboardStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardStatisticsService $service;
    protected Region $region;
    protected Garden $garden;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DashboardStatisticsService();

        // Seed basic database structure
        $this->region = Region::factory()->create([
            'regional_name' => 'Wilayah Barat',
            'regional_code' => 'REG-BARAT',
            'province'      => 'Jawa Barat'
        ]);

        $this->garden = Garden::factory()->create([
            'regional_id' => $this->region->id,
            'kebun_name'  => 'Kebun Rancabali',
            'luas_total_ha' => 100.0,
        ]);
    }

    public function test_get_production_ytd(): void
    {
        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 1,
            'wet_production_kg' => 5000,
        ]);

        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 2,
            'wet_production_kg' => 7000,
        ]);

        // Different year, should not be summed
        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2025,
            'month' => 1,
            'wet_production_kg' => 3000,
        ]);

        $this->assertEquals(12000, $this->service->getProductionYtd(2026));
        $this->assertEquals(12000, $this->service->getProductionYtd(2026, $this->garden->id));
    }

    public function test_get_target_ytd_prorated(): void
    {
        PerformanceTarget::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'target_protas_min' => 10, // 10 kg/ha
        ]);

        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 1,
            'active_picking_area_ha' => 80.0,
        ]);

        // Monthly progress fraction: current month / 12
        $expectedFraction = now()->month / 12;
        $expectedTarget = 10 * 80.0 * $expectedFraction;

        $this->assertEquals($expectedTarget, $this->service->getTargetYtdProrated(2026));
    }

    public function test_get_avg_productivity(): void
    {
        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 1,
            'wet_production_kg' => 5000,
            'active_picking_area_ha' => 100.0,
        ]);

        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 2,
            'wet_production_kg' => 7000,
            'active_picking_area_ha' => 100.0,
        ]);

        // Total = 12000, Area = 100.0. Productivity = 120
        $this->assertEquals(120, $this->service->getAvgProductivity(2026));
    }

    public function test_get_picking_capacity_and_quality_score(): void
    {
        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 1,
            'avg_capacity' => 25.5,
            'quality_score' => 8.2,
        ]);

        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 2,
            'avg_capacity' => 24.5,
            'quality_score' => 7.8,
        ]);

        $this->assertEquals(25.0, $this->service->getPickingCapacity(2026));
        $this->assertEquals(8.0, $this->service->getQualityScore(2026));
    }

    public function test_get_strategic_progress(): void
    {
        StrategicAction::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'action_type' => 'weed_control',
            'coverage_target_percent' => 80.0,
            'realization_percent' => 60.0,
        ]);

        $progress = $this->service->getStrategicProgress('weed_control', 2026);

        // Garden total area is 100.0
        // Target area = 100.0 * 80% = 80.0
        // Realized area = 100.0 * 60% = 60.0
        // Progress percent = (60 / 80) * 100 = 75.0%
        $this->assertEquals(80.0, $progress['target_area']);
        $this->assertEquals(60.0, $progress['realized_area']);
        $this->assertEquals(75.0, $progress['progress_percent']);
    }

    public function test_get_fertilizer_root_data(): void
    {
        StrategicAction::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'action_type' => 'fertilizer_root',
            'dosis_n_kg_ha' => 150.0,
            'realized_dosis_n_kg_ha' => 140.0,
            'n_protas_percent' => 85.0,
        ]);

        $fertData = $this->service->getFertilizerRootData(2026);

        $this->assertEquals(150.0, $fertData['avg_dosis']);
        $this->assertEquals(140.0, $fertData['avg_realized_dosis']);
        $this->assertEquals(85.0, $fertData['avg_n_protas_percent']);
    }

    public function test_get_monthly_production_and_productivity(): void
    {
        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 3,
            'wet_production_kg' => 8000,
            'active_picking_area_ha' => 80.0,
        ]);

        $monthlyProduction = $this->service->getMonthlyProduction(2026);
        $monthlyProductivity = $this->service->getMonthlyProductivity(2026);

        $this->assertEquals(8000, $monthlyProduction[3]);
        $this->assertEquals(100.0, $monthlyProductivity[3]);
    }

    public function test_invalidate_cache(): void
    {
        Cache::shouldReceive('flush')->once();
        $this->service->invalidateCache(2026);
    }
}
