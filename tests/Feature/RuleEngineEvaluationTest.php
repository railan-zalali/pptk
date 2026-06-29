<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Services\InsightService;
use App\Services\RuleEngine\ProductivityRule;
use App\Services\RuleEngine\QualityRule;
use App\Services\RuleEngine\StrategicActionRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test evaluasi Rule Engine — ProductivityRule, QualityRule, StrategicActionRule.
 * Memverifikasi bahwa algoritma menghasilkan alert level yang tepat berdasarkan input.
 */
class RuleEngineEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected InsightService $insightService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CoreStructureSeeder::class);
        $this->insightService = new InsightService();
    }

    // ─── Productivity Rule ─────────────────────────────────────────────────

    /** @test */
    public function test_produktivitas_rendah_menghasilkan_alert_high(): void
    {
        $rule   = new ProductivityRule();
        $result = $rule->evaluate(800); // < 1000 kg/ha

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Produktivitas rendah terdeteksi', $result->message);
    }

    /** @test */
    public function test_produktivitas_menengah_menghasilkan_alert_medium(): void
    {
        $rule   = new ProductivityRule();
        $result = $rule->evaluate(1150); // 1000–1300 kg/ha

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    /** @test */
    public function test_produktivitas_optimal_menghasilkan_alert_low(): void
    {
        $rule   = new ProductivityRule();
        $result = $rule->evaluate(1500); // > 1300 kg/ha

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    // ─── Quality Rule ──────────────────────────────────────────────────────

    /** @test */
    public function test_mutu_rendah_menghasilkan_alert_high(): void
    {
        $rule   = new QualityRule();
        $result = $rule->evaluate(6.5); // < 7.0

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Mutu pucuk rendah terdeteksi', $result->message);
    }

    /** @test */
    public function test_mutu_standar_menghasilkan_alert_medium(): void
    {
        $rule   = new QualityRule();
        $result = $rule->evaluate(8.0); // 7.0–8.5

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    /** @test */
    public function test_mutu_sangat_baik_menghasilkan_alert_low(): void
    {
        $rule   = new QualityRule();
        $result = $rule->evaluate(9.2); // > 8.5

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    // ─── Strategic Action Rule — Fertilizer Root ──────────────────────────

    /** @test */
    public function test_realisasi_pemupukan_akar_sangat_rendah_menghasilkan_alert_high(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'            => 'fertilizer_root',
            'realization_percent'    => 65, // < 70%
            'dosis_n_kg_ha'          => 200,
            'realized_dosis_n_kg_ha' => 150,
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
    }

    /** @test */
    public function test_realisasi_pemupukan_akar_dengan_defisit_dosis_menghasilkan_alert_high(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'            => 'fertilizer_root',
            'realization_percent'    => 60,  // low realization
            'dosis_n_kg_ha'          => 200,
            'realized_dosis_n_kg_ha' => 120, // 40% deficit
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Dosis realisasi (120 kg/ha) jauh di bawah target (200 kg/ha)', $result->recommendations[3]);
    }

    /** @test */
    public function test_realisasi_pemupukan_akar_sedang_menghasilkan_alert_medium(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'            => 'fertilizer_root',
            'realization_percent'    => 80, // 70–85%
            'dosis_n_kg_ha'          => 200,
            'realized_dosis_n_kg_ha' => 190,
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    /** @test */
    public function test_realisasi_pemupukan_akar_on_track_menghasilkan_alert_low(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'            => 'fertilizer_root',
            'realization_percent'    => 90, // > 85%
            'dosis_n_kg_ha'          => 200,
            'realized_dosis_n_kg_ha' => 195,
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    // ─── Strategic Action Rule — Machine Age ──────────────────────────────

    /** @test */
    public function test_mesin_sangat_tua_menghasilkan_alert_high(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'     => 'machine',
            'avg_machine_age' => 9.5, // >= 8 tahun
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('high', $result->alertLevel);
        $this->assertStringContainsString('Rata-rata umur mesin petik sangat tua', $result->message);
    }

    /** @test */
    public function test_mesin_perlu_peremajaan_menghasilkan_alert_medium(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'     => 'machine',
            'avg_machine_age' => 6.2, // 5–8 tahun
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('medium', $result->alertLevel);
    }

    /** @test */
    public function test_mesin_kondisi_baik_menghasilkan_alert_low(): void
    {
        $rule   = new StrategicActionRule();
        $result = $rule->evaluate([
            'action_type'     => 'machine',
            'avg_machine_age' => 3.0, // < 5 tahun
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('low', $result->alertLevel);
    }

    // ─── Insight Service Integration ──────────────────────────────────────

    /** @test */
    public function test_insight_service_menyimpan_insight_produktivitas_ke_database(): void
    {
        $garden = Garden::first();
        $this->assertNotNull($garden);

        ProductionRealization::create([
            'kebun_id'               => $garden->id,
            'month'                  => 5,
            'year'                   => 2025,
            'active_picking_area_ha' => 50,
            'wet_production_kg'      => 40_000, // 800 kg/ha → low productivity
            'quality_score'          => 6.0,    // < 7.0 → low quality
        ]);

        $this->insightService->generateProductivityInsight($garden->id, 2025);
        $this->insightService->generateQualityInsight($garden->id, 2025);

        $this->assertDatabaseHas('insights', [
            'garden_id'    => $garden->id,
            'insight_type' => 'productivity',
            'alert_level'  => 'high',
        ]);

        $this->assertDatabaseHas('insights', [
            'garden_id'    => $garden->id,
            'insight_type' => 'quality',
            'alert_level'  => 'high',
        ]);
    }
}
