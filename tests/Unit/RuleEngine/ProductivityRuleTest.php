<?php

namespace Tests\Unit\RuleEngine;

use App\Services\RuleEngine\ProductivityRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test semua cabang logika ProductivityRule.
 * InsightConfig menggunakan DB (cache) — pakai RefreshDatabase + seed InsightConfig.
 * Default fallback: threshold_low=1000, threshold_medium=1300.
 */
class ProductivityRuleTest extends TestCase
{
    use RefreshDatabase;

    private ProductivityRule $rule;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed hanya InsightConfig agar threshold terdefinisi dari DB
        $this->seed(\Database\Seeders\InsightConfigSeeder::class);
        $this->rule = new ProductivityRule();
    }

    // ----------------------------------------------------------------
    // Input tidak valid → null
    // ----------------------------------------------------------------

    public function test_evaluate_returns_null_for_zero_productivity(): void
    {
        $result = $this->rule->evaluate(0);

        $this->assertNull($result);
    }

    public function test_evaluate_returns_null_for_negative_productivity(): void
    {
        $result = $this->rule->evaluate(-100);

        $this->assertNull($result);
    }

    // ----------------------------------------------------------------
    // Produktivitas rendah (< threshold_low = 1000) → HIGH
    // ----------------------------------------------------------------

    public function test_evaluate_below_low_threshold_returns_high_alert(): void
    {
        $result = $this->rule->evaluate(800);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertNotEmpty($result->recommendations);
        $this->assertStringContainsString('Peringatan', $result->title);
    }

    public function test_evaluate_exactly_at_low_threshold_boundary_returns_high_alert(): void
    {
        // < 1000 → high; tepat 999 → high
        $result = $this->rule->evaluate(999);

        $this->assertEquals('high', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // Produktivitas menengah (threshold_low ≤ x < threshold_medium) → MEDIUM
    // ----------------------------------------------------------------

    public function test_evaluate_between_thresholds_returns_medium_alert(): void
    {
        $result = $this->rule->evaluate(1100);

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
        $this->assertNotEmpty($result->recommendations);
    }

    public function test_evaluate_exactly_at_low_threshold_returns_medium_alert(): void
    {
        // tepat 1000 → medium (≥ low, < medium)
        $result = $this->rule->evaluate(1000);

        $this->assertEquals('medium', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // Produktivitas optimal (≥ threshold_medium = 1300) → LOW
    // ----------------------------------------------------------------

    public function test_evaluate_above_medium_threshold_returns_low_alert(): void
    {
        $result = $this->rule->evaluate(1500);

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
        $this->assertNotEmpty($result->recommendations);
        $this->assertStringContainsString('Optimal', $result->title);
    }

    public function test_evaluate_exactly_at_medium_threshold_returns_low_alert(): void
    {
        // tepat 1300 → low
        $result = $this->rule->evaluate(1300);

        $this->assertEquals('low', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // Struktur InsightResult
    // ----------------------------------------------------------------

    public function test_result_has_required_fields(): void
    {
        $result = $this->rule->evaluate(800);

        $this->assertNotNull($result->alertLevel);
        $this->assertNotEmpty($result->message);
        $this->assertIsArray($result->recommendations);
        $this->assertNotEmpty($result->title);
        $this->assertEquals('productivity', $result->insightType);
    }

    public function test_getName_returns_productivity(): void
    {
        $this->assertEquals('productivity', $this->rule->getName());
    }
}
