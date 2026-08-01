<?php

namespace Tests\Unit\RuleEngine;

use App\Services\RuleEngine\QualityRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test semua cabang logika QualityRule.
 * Default fallback: threshold_low=7.0, threshold_high=8.5.
 * Logic: < low → high; > high → low; else → medium.
 */
class QualityRuleTest extends TestCase
{
    use RefreshDatabase;

    private QualityRule $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\InsightConfigSeeder::class);
        $this->rule = new QualityRule();
    }

    // ----------------------------------------------------------------
    // Input tidak valid → null
    // ----------------------------------------------------------------

    public function test_evaluate_returns_null_for_zero_score(): void
    {
        $result = $this->rule->evaluate(0);

        $this->assertNull($result);
    }

    public function test_evaluate_returns_null_for_negative_score(): void
    {
        $result = $this->rule->evaluate(-1.0);

        $this->assertNull($result);
    }

    // ----------------------------------------------------------------
    // Mutu rendah (< threshold_low = 7.0) → HIGH
    // ----------------------------------------------------------------

    public function test_evaluate_below_low_threshold_returns_high_alert(): void
    {
        $result = $this->rule->evaluate(6.0);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Peringatan', $result->title);
        $this->assertNotEmpty($result->recommendations);
    }

    public function test_evaluate_exactly_below_low_threshold_is_high(): void
    {
        // 6.99 → < 7.0 → high
        $result = $this->rule->evaluate(6.99);

        $this->assertEquals('high', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // Mutu standar (threshold_low ≤ x ≤ threshold_high) → MEDIUM
    // ----------------------------------------------------------------

    public function test_evaluate_at_low_threshold_is_medium(): void
    {
        // tepat 7.0 → tidak < 7 dan tidak > 8.5 → medium
        $result = $this->rule->evaluate(7.0);

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_evaluate_in_standard_range_is_medium(): void
    {
        $result = $this->rule->evaluate(7.5);

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
        $this->assertNotEmpty($result->recommendations);
    }

    public function test_evaluate_at_high_threshold_is_medium(): void
    {
        // tepat 8.5 → tidak > 8.5 → medium
        $result = $this->rule->evaluate(8.5);

        $this->assertEquals('medium', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // Mutu sangat baik (> threshold_high = 8.5) → LOW
    // ----------------------------------------------------------------

    public function test_evaluate_above_high_threshold_returns_low_alert(): void
    {
        $result = $this->rule->evaluate(9.0);

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
        $this->assertStringContainsString('Baik', $result->title);
    }

    public function test_evaluate_just_above_high_threshold_is_low(): void
    {
        // 8.51 → > 8.5 → low
        $result = $this->rule->evaluate(8.51);

        $this->assertEquals('low', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // Struktur InsightResult
    // ----------------------------------------------------------------

    public function test_result_has_correct_insight_type(): void
    {
        $result = $this->rule->evaluate(6.0);

        $this->assertEquals('quality', $result->insightType);
    }

    public function test_getName_returns_quality(): void
    {
        $this->assertEquals('quality', $this->rule->getName());
    }
}
