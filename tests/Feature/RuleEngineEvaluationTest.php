<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\InsightConfig;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_productivity_rule_low_alert(): void
    {
        $rule = new ProductivityRule();

        // ProductivityRule expects a float directly, not an array
        $result = $rule->evaluate(800); // Below 1000 threshold

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
    }

    public function test_productivity_rule_medium_alert(): void
    {
        $rule = new ProductivityRule();

        $result = $rule->evaluate(1100); // Between 1000 and 1300

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_productivity_rule_optimal(): void
    {
        $rule = new ProductivityRule();

        $result = $rule->evaluate(1500); // Above 1300

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    public function test_quality_rule_low_alert(): void
    {
        $rule = new QualityRule();

        // QualityRule expects a float directly, not an array
        $result = $rule->evaluate(6.0); // Below 7.0

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
    }

    public function test_quality_rule_standard(): void
    {
        $rule = new QualityRule();

        $result = $rule->evaluate(7.5); // Between 7.0 and 8.5

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_quality_rule_excellent(): void
    {
        $rule = new QualityRule();

        $result = $rule->evaluate(9.0); // Above 8.5

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    public function test_strategic_action_fertilizer_root_low(): void
    {
        $rule = new StrategicActionRule();

        $result = $rule->evaluate([
            'action_type'        => 'fertilizer_root',
            'realization_percent'=> 60, // Below 70
            'garden_name'        => 'Test Garden',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
    }

    public function test_strategic_action_fertilizer_root_medium(): void
    {
        $rule = new StrategicActionRule();

        $result = $rule->evaluate([
            'action_type'        => 'fertilizer_root',
            'realization_percent'=> 78, // Between 70 and 85
            'garden_name'        => 'Test Garden',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_strategic_action_fertilizer_root_good(): void
    {
        $rule = new StrategicActionRule();

        $result = $rule->evaluate([
            'action_type'        => 'fertilizer_root',
            'realization_percent'=> 90, // Above 85
            'garden_name'        => 'Test Garden',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    public function test_strategic_action_machine_age_alert(): void
    {
        $rule = new StrategicActionRule();

        $result = $rule->evaluate([
            'action_type'    => 'machine',
            'avg_machine_age'=> 9, // Above 8
            'garden_name'    => 'Test Garden',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
    }

    public function test_insight_config_exists(): void
    {
        $this->assertGreaterThan(0, InsightConfig::count());
    }

    public function test_insight_config_has_productivity_rules(): void
    {
        $configs = InsightConfig::where('insight_type', 'productivity')->get();

        $this->assertGreaterThanOrEqual(2, $configs->count());
    }

    public function test_insight_config_has_quality_rules(): void
    {
        $configs = InsightConfig::where('insight_type', 'quality')->get();

        $this->assertGreaterThanOrEqual(2, $configs->count());
    }

    public function test_insight_config_has_machine_rules(): void
    {
        $configs = InsightConfig::where('insight_type', 'strategic_machine')->get();

        $this->assertGreaterThanOrEqual(2, $configs->count());
    }
}
