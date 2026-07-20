<?php

namespace Tests\Feature;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\CommunityService;
use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Region;
use App\Models\StrategicAction;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_region_count(): void
    {
        $this->assertEquals(3, Region::count());
    }

    public function test_garden_count(): void
    {
        $this->assertEquals(6, Garden::count());
    }

    public function test_afdeling_count(): void
    {
        $this->assertEquals(18, Afdeling::count());
    }

    public function test_block_count(): void
    {
        $this->assertEquals(72, Block::count());
    }

    public function test_gardens_belong_to_regions(): void
    {
        $gardens = Garden::with('region')->get();

        foreach ($gardens as $garden) {
            $this->assertNotNull($garden->region, "Garden {$garden->kebun_name} has no region");
        }
    }

    public function test_afdelings_belong_to_gardens(): void
    {
        $afdelings = Afdeling::with('garden')->get();

        foreach ($afdelings as $afdeling) {
            $this->assertNotNull($afdeling->garden, "Afdeling {$afdeling->name} has no garden");
        }
    }

    public function test_blocks_belong_to_afdelings(): void
    {
        $blocks = Block::with('afdeling')->get();

        foreach ($blocks as $block) {
            $this->assertNotNull($block->afdeling, "Block {$block->name} has no afdeling");
        }
    }

    public function test_production_realizations_exist(): void
    {
        $this->assertGreaterThan(0, ProductionRealization::count());
    }

    public function test_production_realizations_belong_to_gardens(): void
    {
        $realizations = ProductionRealization::with('garden')->get();

        foreach ($realizations as $realization) {
            $this->assertNotNull($realization->garden, "Realization has no garden");
        }
    }

    public function test_performance_targets_exist(): void
    {
        $this->assertGreaterThan(0, PerformanceTarget::count());
    }

    public function test_strategic_actions_exist(): void
    {
        $this->assertGreaterThan(0, StrategicAction::count());
    }

    public function test_strategic_actions_have_realization_percent(): void
    {
        $actions = StrategicAction::whereNotNull('realization_percent')->get();

        $this->assertGreaterThan(0, $actions->count());
    }

    public function test_visits_exist(): void
    {
        $this->assertGreaterThan(0, Visit::count());
    }

    public function test_community_services_exist(): void
    {
        $this->assertGreaterThan(0, CommunityService::count());
    }
}
