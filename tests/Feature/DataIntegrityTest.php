<?php

namespace Tests\Feature;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\CommunityService;
use App\Models\Garden;
use App\Models\InsightConfig;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Region;
use App\Models\ResearchBudget;
use App\Models\ResearchBudgetActivity;
use App\Models\StrategicAction;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test integritas data setelah seeder dijalankan.
 * Memverifikasi jumlah record, relasi antar model, dan constraint DB.
 */
class DataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    // ----------------------------------------------------------------
    // Jumlah record struktur inti
    // ----------------------------------------------------------------

    public function test_region_count_is_3(): void
    {
        $this->assertEquals(3, Region::count());
    }

    public function test_garden_count_is_6(): void
    {
        $this->assertEquals(6, Garden::count());
    }

    public function test_afdeling_count_is_18(): void
    {
        $this->assertEquals(18, Afdeling::count());
    }

    public function test_block_count_is_72(): void
    {
        $this->assertEquals(72, Block::count());
    }

    // ----------------------------------------------------------------
    // Relasi: setiap entitas punya parent yang valid
    // ----------------------------------------------------------------

    public function test_every_garden_belongs_to_a_region(): void
    {
        Garden::with('region')->get()->each(function ($garden) {
            $this->assertNotNull(
                $garden->region,
                "Garden '{$garden->kebun_name}' tidak punya region"
            );
        });
    }

    public function test_every_afdeling_belongs_to_a_garden(): void
    {
        Afdeling::with('garden')->get()->each(function ($afdeling) {
            $this->assertNotNull(
                $afdeling->garden,
                "Afdeling '{$afdeling->name}' tidak punya garden"
            );
        });
    }

    public function test_every_block_belongs_to_an_afdeling(): void
    {
        Block::with('afdeling')->get()->each(function ($block) {
            $this->assertNotNull(
                $block->afdeling,
                "Block '{$block->name}' tidak punya afdeling"
            );
        });
    }

    // ----------------------------------------------------------------
    // Production Realizations
    // ----------------------------------------------------------------

    public function test_production_realizations_exist(): void
    {
        $this->assertGreaterThan(0, ProductionRealization::count());
    }

    public function test_every_production_realization_belongs_to_a_garden(): void
    {
        ProductionRealization::with('garden')->get()->each(function ($r) {
            $this->assertNotNull(
                $r->garden,
                "ProductionRealization ID {$r->id} tidak punya garden"
            );
        });
    }

    public function test_production_realizations_have_valid_month_range(): void
    {
        $invalid = ProductionRealization::where('month', '<', 1)
            ->orWhere('month', '>', 12)
            ->count();

        $this->assertEquals(0, $invalid, 'Terdapat realisasi produksi dengan bulan tidak valid (1-12)');
    }

    public function test_no_duplicate_production_realization_per_kebun_month_year(): void
    {
        // Unique constraint harus ada — duplikat tidak boleh ada
        $duplicates = ProductionRealization::selectRaw('kebun_id, month, year, COUNT(*) as cnt')
            ->groupBy('kebun_id', 'month', 'year')
            ->having('cnt', '>', 1)
            ->count();

        $this->assertEquals(0, $duplicates, 'Terdapat duplikat data produksi per kebun/bulan/tahun');
    }

    // ----------------------------------------------------------------
    // Strategic Actions
    // ----------------------------------------------------------------

    public function test_strategic_actions_exist(): void
    {
        $this->assertGreaterThan(0, StrategicAction::count());
    }

    public function test_strategic_actions_have_valid_action_types(): void
    {
        $validTypes = [
            'fertilizer_root', 'fertilizer_leaf', 'weed_control',
            'cultivator', 'picking', 'machine', 'opt',
        ];

        $invalid = StrategicAction::whereNotIn('action_type', $validTypes)->count();

        $this->assertEquals(0, $invalid, 'Terdapat strategic action dengan action_type tidak valid');
    }

    public function test_some_strategic_actions_have_realization_percent(): void
    {
        $count = StrategicAction::whereNotNull('realization_percent')->count();

        $this->assertGreaterThan(0, $count);
    }

    // ----------------------------------------------------------------
    // Performance Targets
    // ----------------------------------------------------------------

    public function test_performance_targets_exist(): void
    {
        $this->assertGreaterThan(0, PerformanceTarget::count());
    }

    // ----------------------------------------------------------------
    // Visits & Community Services
    // ----------------------------------------------------------------

    public function test_visits_exist(): void
    {
        $this->assertGreaterThan(0, Visit::count());
    }

    public function test_community_services_exist(): void
    {
        $this->assertGreaterThan(0, CommunityService::count());
    }

    // ----------------------------------------------------------------
    // InsightConfig — threshold tersedia untuk rule engine
    // ----------------------------------------------------------------

    public function test_insight_configs_exist(): void
    {
        $this->assertGreaterThan(0, InsightConfig::count());
    }

    public function test_insight_config_has_productivity_rules(): void
    {
        $count = InsightConfig::where('insight_type', 'productivity')->count();

        $this->assertGreaterThanOrEqual(2, $count);
    }

    public function test_insight_config_has_quality_rules(): void
    {
        $count = InsightConfig::where('insight_type', 'quality')->count();

        $this->assertGreaterThanOrEqual(2, $count);
    }

    public function test_insight_config_has_machine_rules(): void
    {
        $count = InsightConfig::where('insight_type', 'strategic_machine')->count();

        $this->assertGreaterThanOrEqual(2, $count);
    }

    // ----------------------------------------------------------------
    // ResearchBudget Activities — data tidak kosong
    // ----------------------------------------------------------------

    public function test_research_budget_activities_seeded(): void
    {
        $this->assertGreaterThan(0, ResearchBudgetActivity::count());
    }

    public function test_research_budgets_exist(): void
    {
        $this->assertGreaterThan(0, ResearchBudget::count());
    }

    // ----------------------------------------------------------------
    // Gardens punya koordinat (seeder mengisi semua)
    // ----------------------------------------------------------------

    public function test_all_gardens_have_coordinates(): void
    {
        $without = Garden::whereNull('coordinates')->count();

        $this->assertEquals(0, $without, 'Terdapat kebun yang belum punya koordinat');
    }
}
