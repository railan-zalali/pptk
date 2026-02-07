<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitsAndInsightsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_visits_index_is_accessible(): void
    {
        $response = $this->get(route('visits.index'));
        $response->assertStatus(200);
    }

    public function test_visits_exist_for_seeded_gardens(): void
    {
        $garden = Garden::first();
        $this->assertTrue($garden->visits()->exists());
    }

    public function test_insights_exist_and_have_required_fields(): void
    {
        $insight = Insight::first();
        $this->assertNotNull($insight);
        $this->assertNotEmpty($insight->alert_level);
        $this->assertNotEmpty($insight->title);
        $this->assertNotEmpty($insight->description);
    }
}
