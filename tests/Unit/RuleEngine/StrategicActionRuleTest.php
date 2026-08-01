<?php

namespace Tests\Unit\RuleEngine;

use App\Services\RuleEngine\StrategicActionRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test semua 7 tipe aksi pada StrategicActionRule:
 * fertilizer_root, fertilizer_leaf, weed_control, cultivator, picking, machine, opt.
 * Default action_type yang tidak dikenal → null.
 */
class StrategicActionRuleTest extends TestCase
{
    use RefreshDatabase;

    private StrategicActionRule $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\InsightConfigSeeder::class);
        $this->rule = new StrategicActionRule();
    }

    // ----------------------------------------------------------------
    // Default action_type tidak dikenal → null
    // ----------------------------------------------------------------

    public function test_unknown_action_type_returns_null(): void
    {
        $result = $this->rule->evaluate(['action_type' => 'unknown_type']);

        $this->assertNull($result);
    }

    public function test_empty_action_type_returns_null(): void
    {
        $result = $this->rule->evaluate(['action_type' => '']);

        $this->assertNull($result);
    }

    // ================================================================
    // 1. FERTILIZER ROOT — threshold: low=70, medium=85
    // ================================================================

    public function test_fertilizer_root_below_low_threshold_returns_high(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'fertilizer_root',
            'realization_percent' => 60, // < 70
            'garden_name'         => 'Kebun Test',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertEquals('strategic_fertilizer_root', $result->insightType);
    }

    public function test_fertilizer_root_between_thresholds_returns_medium(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'fertilizer_root',
            'realization_percent' => 78, // 70 ≤ x < 85
            'garden_name'         => 'Kebun Test',
        ]);

        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_fertilizer_root_above_medium_threshold_returns_low(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'fertilizer_root',
            'realization_percent' => 90, // ≥ 85
            'garden_name'         => 'Kebun Test',
        ]);

        $this->assertEquals('low', $result->alertLevel);
    }

    public function test_fertilizer_root_high_includes_dosis_deficit_note_when_applicable(): void
    {
        $result = $this->rule->evaluate([
            'action_type'            => 'fertilizer_root',
            'realization_percent'    => 50,
            'dosis_n_kg_ha'          => 100,
            'realized_dosis_n_kg_ha' => 60, // 60 < 100 * (1 - 20%) = 80 → deficit
            'garden_name'            => 'Kebun Test',
        ]);

        $this->assertEquals('high', $result->alertLevel);
        // Pastikan rekomendasi dosis deficit ada
        $deficitNote = collect($result->recommendations)
            ->first(fn($r) => str_contains($r, 'Dosis realisasi'));
        $this->assertNotNull($deficitNote, 'Expected dosis deficit recommendation');
    }

    // ================================================================
    // 2. FERTILIZER LEAF — threshold: low=60, medium=80
    // ================================================================

    public function test_fertilizer_leaf_below_low_threshold_returns_high(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'fertilizer_leaf',
            'realization_percent' => 40,
        ]);

        $this->assertEquals('high', $result->alertLevel);
        $this->assertEquals('strategic_fertilizer_leaf', $result->insightType);
    }

    public function test_fertilizer_leaf_in_medium_range_returns_medium(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'fertilizer_leaf',
            'realization_percent' => 70,
        ]);

        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_fertilizer_leaf_zero_realization_returns_null(): void
    {
        // evaluateCoverage: realization_percent <= 0 → null
        $result = $this->rule->evaluate([
            'action_type'         => 'fertilizer_leaf',
            'realization_percent' => 0,
        ]);

        $this->assertNull($result);
    }

    // ================================================================
    // 3. WEED CONTROL — sama dengan coverage
    // ================================================================

    public function test_weed_control_high_alert(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'weed_control',
            'realization_percent' => 30,
        ]);

        $this->assertEquals('high', $result->alertLevel);
        $this->assertEquals('strategic_weed_control', $result->insightType);
    }

    // ================================================================
    // 4. CULTIVATOR — sama dengan coverage
    // ================================================================

    public function test_cultivator_low_alert_when_on_track(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'cultivator',
            'realization_percent' => 90,
        ]);

        $this->assertEquals('low', $result->alertLevel);
        $this->assertEquals('strategic_cultivator', $result->insightType);
    }

    // ================================================================
    // 5. PICKING — kandas_risk → high; realization < 80 → medium
    // ================================================================

    public function test_picking_kandas_risk_true_returns_high(): void
    {
        $result = $this->rule->evaluate([
            'action_type'  => 'picking',
            'kandas_risk'  => true,
            'garden_name'  => 'Kebun Test',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Kandas', $result->title);
    }

    public function test_picking_low_realization_without_kandas_returns_medium(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'picking',
            'kandas_risk'         => false,
            'realization_percent' => 50, // > 0 dan < 80
        ]);

        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_picking_normal_returns_low(): void
    {
        $result = $this->rule->evaluate([
            'action_type'         => 'picking',
            'kandas_risk'         => false,
            'realization_percent' => 85, // ≥ 80
        ]);

        $this->assertEquals('low', $result->alertLevel);
    }

    // ================================================================
    // 6. MACHINE — avg_machine_age: ≥8 → high; ≥5 → medium; else → low
    // ================================================================

    public function test_machine_old_age_returns_high(): void
    {
        $result = $this->rule->evaluate([
            'action_type'    => 'machine',
            'avg_machine_age'=> 9, // ≥ 8
            'garden_name'    => 'Kebun Test',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Mendesak', $result->title);
    }

    public function test_machine_moderate_age_returns_medium(): void
    {
        $result = $this->rule->evaluate([
            'action_type'    => 'machine',
            'avg_machine_age'=> 6, // ≥ 5 dan < 8
        ]);

        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_machine_young_age_returns_low(): void
    {
        $result = $this->rule->evaluate([
            'action_type'    => 'machine',
            'avg_machine_age'=> 3, // < 5
        ]);

        $this->assertEquals('low', $result->alertLevel);
    }

    public function test_machine_zero_age_returns_null(): void
    {
        $result = $this->rule->evaluate([
            'action_type'    => 'machine',
            'avg_machine_age'=> 0,
        ]);

        $this->assertNull($result);
    }

    // ================================================================
    // 7. OPT — keyword matching & tp_normalization
    // ================================================================

    public function test_opt_uncontrolled_status_returns_high(): void
    {
        $result = $this->rule->evaluate([
            'action_type' => 'opt',
            'opt_status'  => 'Tidak Terkendali',
            'tp_normalization' => true,
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertEquals('strategic_opt', $result->insightType);
    }

    public function test_opt_heavy_status_returns_high(): void
    {
        $result = $this->rule->evaluate([
            'action_type' => 'opt',
            'opt_status'  => 'Serangan Berat',
            'tp_normalization' => true,
        ]);

        $this->assertEquals('high', $result->alertLevel);
    }

    public function test_opt_moderate_status_returns_medium(): void
    {
        $result = $this->rule->evaluate([
            'action_type' => 'opt',
            'opt_status'  => 'Serangan Sedang',
            'tp_normalization' => true,
        ]);

        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_opt_tp_not_normalized_returns_medium(): void
    {
        // Walaupun status kosong, tp_normalization = false → medium
        $result = $this->rule->evaluate([
            'action_type'      => 'opt',
            'opt_status'       => '',
            'tp_normalization' => false,
        ]);

        $this->assertEquals('medium', $result->alertLevel);
    }

    public function test_opt_controlled_returns_low(): void
    {
        $result = $this->rule->evaluate([
            'action_type'      => 'opt',
            'opt_status'       => 'Terkendali',
            'tp_normalization' => true,
        ]);

        $this->assertEquals('low', $result->alertLevel);
    }

    // ----------------------------------------------------------------
    // getName
    // ----------------------------------------------------------------

    public function test_getName_returns_strategic_action(): void
    {
        $this->assertEquals('strategic_action', $this->rule->getName());
    }
}
