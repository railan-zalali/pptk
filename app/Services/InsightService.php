<?php

namespace App\Services;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use App\Services\RuleEngine\InsightResult;
use App\Services\RuleEngine\ProductivityRule;
use App\Services\RuleEngine\ProductivityDryRule;
use App\Services\RuleEngine\QualityRule;
use App\Services\RuleEngine\StrategicActionRule;
use Illuminate\Support\Facades\Log;

/**
 * InsightService — Orchestrator utama Rule-Based Insight Engine.
 *
 * Bertanggung jawab untuk:
 * 1. Menghitung metrik dari data produksi dan aksi strategis
 * 2. Menjalankan rules yang sesuai
 * 3. Menyimpan/memperbarui hasil ke tabel insights
 */
class InsightService
{
    protected ProductivityRule $productivityRule;
    protected ProductivityDryRule $productivityDryRule;
    protected QualityRule $qualityRule;
    protected StrategicActionRule $strategicRule;

    public function __construct()
    {
        // ponytail: Rules hardcoded — ceiling: unit test sulit mock rule terisolasi. Upgrade path: inject via constructor jika test butuh isolasi.
        $this->productivityRule    = new ProductivityRule();
        $this->productivityDryRule = new ProductivityDryRule();
        $this->qualityRule         = new QualityRule();
        $this->strategicRule       = new StrategicActionRule();
    }

    // ============================================================
    // PUBLIC API — dipanggil oleh controller dan artisan command
    // ============================================================

    /**
     * Generate insight produktivitas untuk satu kebun berdasarkan tahun.
     * Menghitung total produksi & rata-rata luas area dari DB secara langsung.
     *
     * @param  int  $gardenId
     * @param  int  $year
     * @return InsightResult|null
     */
    public function generateProductivityInsight(int $gardenId, int $year): ?InsightResult
    {
        $realizations = ProductionRealization::where('kebun_id', $gardenId)
            ->where('year', $year)
            ->get();

        if ($realizations->isEmpty()) {
            return null;
        }

        $totalProduction = $realizations->sum('wet_production_kg');
        $avgArea         = $realizations->avg('active_picking_area_ha') ?? 0;
        $productivity    = $avgArea > 0 ? $totalProduction / $avgArea : 0;

        $result = $this->productivityRule->evaluate($productivity);

        if ($result) {
            $this->persistInsight($gardenId, $year, $result);
            Log::info("[InsightService] Productivity insight untuk kebun #{$gardenId}: {$result->alertLevel} ({$productivity} kg/ha)");
        }

        return $result;
    }

    /**
     * Generate insight produktivitas KERING untuk satu kebun berdasarkan tahun.
     *
     * Algoritma Rule-Based IF–THEN (Produksi Kering):
     *   Rule 1: IF protas_kering < 220 kg/ha  THEN High Alert
     *   Rule 2: IF 220 ≤ protas_kering < 286   THEN Medium Alert
     *   Rule 3: IF protas_kering ≥ 286 kg/ha   THEN Low Alert
     *
     * Threshold proporsional dari basah × 22% (rasio konversi teh).
     *
     * @param  int  $gardenId
     * @param  int  $year
     * @return InsightResult|null
     */
    public function generateDryProductivityInsight(int $gardenId, int $year): ?InsightResult
    {
        $realizations = ProductionRealization::where('kebun_id', $gardenId)
            ->where('year', $year)
            ->get();

        if ($realizations->isEmpty()) {
            return null;
        }

        // Gunakan dry_production_kg aktual jika ada; fallback estimasi 22% dari basah
        $totalDryProduction = $realizations->sum(function ($r) {
            if ($r->dry_production_kg !== null && $r->dry_production_kg > 0) {
                return $r->dry_production_kg;
            }
            // ponytail: fallback estimasi — ceiling: data kering aktual lebih akurat
            return $r->wet_production_kg * 0.22;
        });

        $avgArea      = $realizations->avg('active_picking_area_ha') ?? 0;
        $protasKering = $avgArea > 0 ? $totalDryProduction / $avgArea : 0;

        $result = $this->productivityDryRule->evaluate($protasKering);

        if ($result) {
            $this->persistInsight($gardenId, $year, $result);
            Log::info("[InsightService] Dry productivity insight kebun #{$gardenId}: {$result->alertLevel} ({$protasKering} kg/ha kering)");
        }

        return $result;
    }

    /**
     * Generate insight mutu pucuk untuk satu kebun berdasarkan tahun.
     * Mengambil rata-rata quality_score dari data realisasi.
     *
     * @param  int  $gardenId
     * @param  int  $year
     * @return InsightResult|null
     */
    public function generateQualityInsight(int $gardenId, int $year): ?InsightResult
    {
        $avgQuality = ProductionRealization::where('kebun_id', $gardenId)
            ->where('year', $year)
            ->whereNotNull('quality_score')
            ->avg('quality_score');

        if ($avgQuality === null) {
            return null;
        }

        $result = $this->qualityRule->evaluate($avgQuality);

        if ($result) {
            $this->persistInsight($gardenId, $year, $result);
            Log::info("[InsightService] Quality insight untuk kebun #{$gardenId}: {$result->alertLevel} (score: {$avgQuality})");
        }

        return $result;
    }

    /**
     * Generate insight untuk satu StrategicAction.
     *
     * @param  StrategicAction  $action
     * @return InsightResult|null
     */
    public function generateStrategicInsight(StrategicAction $action): ?InsightResult
    {
        $data = [
            'action_type'              => $action->action_type,
            'realization_percent'      => $action->realization_percent,
            'coverage_target_percent'  => $action->coverage_target_percent,
            'dosis_n_kg_ha'            => $action->dosis_n_kg_ha,
            'realized_dosis_n_kg_ha'   => $action->realized_dosis_n_kg_ha,
            'avg_machine_age'          => $action->avg_machine_age,
            'kandas_risk'              => $action->kandas_risk,
            'opt_status'               => $action->opt_status,
            'tp_normalization'         => $action->tp_normalization,
        ];

        $result = $this->strategicRule->evaluate($data);

        if ($result) {
            $this->persistInsight($action->kebun_id, $action->year, $result);
            Log::info("[InsightService] Strategic insight [{$action->action_type}] untuk kebun #{$action->kebun_id}: {$result->alertLevel}");
        }

        return $result;
    }

    /**
     * Generate SEMUA insight untuk satu kebun pada tahun tertentu.
     * Digunakan oleh Artisan Command batch processor.
     *
     * @param  int  $gardenId
     * @param  int  $year
     * @return array  Array of InsightResult
     */
    public function generateAllInsightsForGarden(int $gardenId, int $year): array
    {
        $results = [];

        // 1a. Productivity Basah (IF protas_basah < 1000 → High, 1000-1300 → Medium, ≥ 1300 → Low)
        $prodResult = $this->generateProductivityInsight($gardenId, $year);
        if ($prodResult) {
            $results['productivity'] = $prodResult;
        }

        // 1b. Productivity Kering (IF protas_kering < 220 → High, 220-286 → Medium, ≥ 286 → Low)
        $dryResult = $this->generateDryProductivityInsight($gardenId, $year);
        if ($dryResult) {
            $results['productivity_dry'] = $dryResult;
        }

        // 2. Quality
        $qualResult = $this->generateQualityInsight($gardenId, $year);
        if ($qualResult) {
            $results['quality'] = $qualResult;
        }

        // 3. Strategic Actions
        $actions = StrategicAction::where('kebun_id', $gardenId)
            ->where('year', $year)
            ->get();

        foreach ($actions as $action) {
            $strategicResult = $this->generateStrategicInsight($action);
            if ($strategicResult) {
                $results["strategic_{$action->action_type}"] = $strategicResult;
            }
        }

        return $results;
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Simpan atau perbarui insight ke database.
     * Menggunakan updateOrCreate agar tidak duplikat per (kebun + tipe insight + tahun).
     */
    private function persistInsight(int $gardenId, int $year, InsightResult $result): void
    {
        Insight::updateOrCreate(
            [
                'garden_id'    => $gardenId,
                'insight_type' => $result->insightType,
                'year'         => $year,
            ],
            [
                'title'           => $result->title,
                'description'     => $result->message,
                'message'         => $result->message,
                'alert_level'     => $result->alertLevel,
                'recommendations' => $result->recommendations,
                'generated_at'    => now(),
            ]
        );
    }
}