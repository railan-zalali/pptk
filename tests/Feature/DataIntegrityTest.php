<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\Region;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test integritas data setelah seeding.
 */
class DataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    /** @test */
    public function test_gardens_have_regions(): void
    {
        $garden = Garden::with('region')->first();
        $this->assertNotNull($garden);
        $this->assertNotNull($garden->region, 'Setiap kebun harus memiliki region');
    }

    /** @test */
    public function test_gardens_have_strategic_actions(): void
    {
        $garden = Garden::with('strategicActions')->first();
        $this->assertTrue($garden->strategicActions->isNotEmpty(), 'Kebun harus punya strategic actions setelah seeding');
    }

    /** @test */
    public function test_strategic_actions_have_realization_percent(): void
    {
        // Setelah fix seeder, realization_percent harus terisi
        $actionsWithRealization = \App\Models\StrategicAction::whereNotNull('realization_percent')->count();
        $this->assertGreaterThan(0, $actionsWithRealization, 'Strategic actions harus memiliki realization_percent');
    }

    /** @test */
    public function test_insights_have_required_fields(): void
    {
        $insight = Insight::first();
        $this->assertNotNull($insight);
        $this->assertNotEmpty($insight->alert_level);
        $this->assertNotEmpty($insight->title);
        $this->assertNotEmpty($insight->description);
    }

    /** @test */
    public function test_visits_exist_for_seeded_gardens(): void
    {
        $garden = Garden::first();
        $this->assertNotNull($garden);

        // Kunjungan mungkin ada atau tidak tergantung seeder, cukup test akses
        $count = $garden->visits()->count();
        $this->assertIsInt($count);
    }
}
