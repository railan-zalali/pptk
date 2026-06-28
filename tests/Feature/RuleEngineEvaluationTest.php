<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use App\Services\InsightService;
use App\Services\RuleEngine\ProductivityRule;
use App\Services\RuleEngine\QualityRule;
use App\Services\RuleEngine\StrategicActionRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuleEngineEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected InsightService $insightService;

    public function setUp(): void
    {
        parent::setUp();
        // Seed core structures needed (Region, Garden)
        $this->seed(\Database\Seeders\CoreStructureSeeder::class);
        $this->insightService = new InsightService();
    }

    /** @test */
    public function test_productivity_rule_evaluation()
    {
        $rule = new ProductivityRule();

        // 1. Low Productivity (below 1000 kg/ha) -> High Alert
        $result = $rule->evaluate(800);
        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Produktivitas rendah terdeteksi', $result->message);

        // 2. Medium Productivity (between 1000 and 1300 kg/ha) -> Medium Alert
        $result = $rule->evaluate(1150);
        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);

        // 3. Optimal Productivity (above 1300 kg/ha) -> Low Alert
        $result = $rule->evaluate(1500);
        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    /** @test */
    public function test_quality_rule_evaluation()
    {
        $rule = new QualityRule();

        // 1. Low Quality (below 7.0) -> High Alert
        $result = $rule->evaluate(6.5);
        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Mutu pucuk rendah terdeteksi', $result->message);

        // 2. Standard Quality (between 7.0 and 8.5) -> Medium Alert
        $result = $rule->evaluate(8.0);
        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);

        // 3. High Quality (above 8.5) -> Low Alert
        $result = $rule->evaluate(9.2);
        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    /** @test */
    public function test_strategic_action_rule_fertilizer_root_evaluation()
    {
        $rule = new StrategicActionRule();

        // High Alert due to low realization percent (<70)
        $data = [
            'action_type' => 'fertilizer_root',
            'realization_percent' => 65,
            'dosis_n_kg_ha' => 200,
            'realized_dosis_n_kg_ha' => 150,
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);

        // High Alert due to low realization percent (<70) and with dosage deficit
        $data = [
            'action_type' => 'fertilizer_root',
            'realization_percent' => 60, // low realization % -> high alert
            'dosis_n_kg_ha' => 200,
            'realized_dosis_n_kg_ha' => 120, // 60% of plan (more than 20% deficit)
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Dosis realisasi (120 kg/ha) jauh di bawah target (200 kg/ha)', $result->recommendations[3]);

        // Medium Alert (realization between 70 and 85)
        $data = [
            'action_type' => 'fertilizer_root',
            'realization_percent' => 80,
            'dosis_n_kg_ha' => 200,
            'realized_dosis_n_kg_ha' => 190,
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);

        // Low Alert (on track)
        $data = [
            'action_type' => 'fertilizer_root',
            'realization_percent' => 90,
            'dosis_n_kg_ha' => 200,
            'realized_dosis_n_kg_ha' => 195,
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    /** @test */
    public function test_strategic_action_rule_machine_age_evaluation()
    {
        $rule = new StrategicActionRule();

        // 1. Old Machines (>= 8 years) -> High Alert (mendesak)
        $data = [
            'action_type' => 'machine',
            'avg_machine_age' => 9.5
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Rata-rata umur mesin petik sangat tua', $result->message);

        // 2. Medium age (between 5 and 8) -> Medium Alert (direncanakan)
        $data = [
            'action_type' => 'machine',
            'avg_machine_age' => 6.2
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);

        // 3. Good condition (< 5 years) -> Low Alert
        $data = [
            'action_type' => 'machine',
            'avg_machine_age' => 3.0
        ];
        $result = $rule->evaluate($data);
        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    /** @test */
    public function test_insight_service_persists_generated_insights()
    {
        $garden = Garden::first();
        $this->assertNotNull($garden);

        // Create mock ProductionRealization
        ProductionRealization::create([
            'kebun_id' => $garden->id,
            'month' => 5,
            'year' => 2025,
            'active_picking_area_ha' => 50,
            'wet_production_kg' => 40000, // 800 kg/ha (low productivity)
            'quality_score' => 6.0,       // low quality
        ]);

        // Generate insights
        $this->insightService->generateProductivityInsight($garden->id, 2025);
        $this->insightService->generateQualityInsight($garden->id, 2025);

        // Assert they are persisted to the database
        $this->assertDatabaseHas('insights', [
            'garden_id' => $garden->id,
            'insight_type' => 'productivity',
            'alert_level' => 'high'
        ]);

        $this->assertDatabaseHas('insights', [
            'garden_id' => $garden->id,
            'insight_type' => 'quality',
            'alert_level' => 'high'
        ]);
    }
}
