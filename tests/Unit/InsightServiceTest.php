<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Region;
use App\Models\Garden;
use App\Models\Insight;
use App\Services\InsightService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InsightServiceTest extends TestCase
{
    use RefreshDatabase;

    protected InsightService $service;
    protected Garden $garden;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InsightService();

        $region = Region::factory()->create(['province' => 'Jawa Barat']);
        $this->garden = Garden::factory()->create(['regional_id' => $region->id]);
    }

    public function test_generate_productivity_insight_low(): void
    {
        // Productivity < 1000.0 is 'high' alert (low productivity)
        $insightData = $this->service->generateProductivityInsight($this->garden->id, 800.0);

        $this->assertEquals('high', $insightData['alert_level']);
        $this->assertEquals('Produktivitas Rendah', $insightData['title']);
        $this->assertContains('Tingkatkan dosis pemupukan nitrogen', $insightData['recommendations']);

        $this->assertDatabaseHas('insights', [
            'garden_id'    => $this->garden->id,
            'title'        => 'Produktivitas Rendah',
            'alert_level'  => 'high',
            'insight_type' => 'productivity',
        ]);
    }

    public function test_generate_productivity_insight_medium(): void
    {
        // Productivity between 1000.0 and 1300.0 is 'medium' alert
        $insightData = $this->service->generateProductivityInsight($this->garden->id, 1150.0);

        $this->assertEquals('medium', $insightData['alert_level']);
        $this->assertEquals('Produktivitas Perlu Perhatian', $insightData['title']);

        $this->assertDatabaseHas('insights', [
            'garden_id'   => $this->garden->id,
            'alert_level' => 'medium',
        ]);
    }

    public function test_generate_productivity_insight_optimal(): void
    {
        // Productivity >= 1300.0 is 'low' alert (high/optimal productivity)
        $insightData = $this->service->generateProductivityInsight($this->garden->id, 1500.0);

        $this->assertEquals('low', $insightData['alert_level']);
        $this->assertEquals('Produktivitas Optimal', $insightData['title']);

        $this->assertDatabaseHas('insights', [
            'garden_id'   => $this->garden->id,
            'alert_level' => 'low',
        ]);
    }

    public function test_generate_quality_insight_low(): void
    {
        // Quality < 7.0 is 'high' alert
        $insightData = $this->service->generateQualityInsight($this->garden->id, 6.5);

        $this->assertEquals('high', $insightData['alert_level']);
        $this->assertEquals('Mutu Pucuk Rendah', $insightData['title']);

        $this->assertDatabaseHas('insights', [
            'garden_id'    => $this->garden->id,
            'alert_level'  => 'high',
            'insight_type' => 'quality',
        ]);
    }

    public function test_generate_quality_insight_high(): void
    {
        // Quality > 8.5 is 'low' alert (high quality)
        $insightData = $this->service->generateQualityInsight($this->garden->id, 9.0);

        $this->assertEquals('low', $insightData['alert_level']);
        $this->assertEquals('Mutu Pucuk Sangat Baik', $insightData['title']);

        $this->assertDatabaseHas('insights', [
            'garden_id'    => $this->garden->id,
            'alert_level'  => 'low',
            'insight_type' => 'quality',
        ]);
    }

    public function test_get_insights_for_dashboard(): void
    {
        Insight::factory()->count(10)->create(['garden_id' => $this->garden->id]);

        $dashboardInsights = $this->service->getInsightsForDashboard(5);

        $this->assertCount(5, $dashboardInsights);
    }
}
