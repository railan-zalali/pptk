<?php

namespace App\Services\RuleEngine;

use App\Models\InsightConfig;

/**
 * Rule untuk mengevaluasi produktivitas PRODUKSI KERING (kg/ha/tahun).
 *
 * Algoritma Rule-Based IF–THEN:
 * ─────────────────────────────────────────────────────────────────────
 * Rule 1: IF protas_kering < 220 kg/ha  THEN status = High Alert
 * Rule 2: IF protas_kering >= 220 AND < 286 kg/ha THEN status = Medium Alert
 * Rule 3: IF protas_kering >= 286 kg/ha THEN status = Low Alert
 * ─────────────────────────────────────────────────────────────────────
 *
 * Threshold diturunkan proporsional dari threshold basah × 22%
 * (rasio konversi pucuk basah → teh kering standar agronomis).
 *
 *   threshold_low_dry    = 1000 × 0.22 = 220 kg/ha
 *   threshold_medium_dry = 1300 × 0.22 = 286 kg/ha
 *
 * Dapat di-override via tabel insight_configs:
 *   - productivity_dry.threshold_low    (default: 220 kg/ha)
 *   - productivity_dry.threshold_medium (default: 286 kg/ha)
 */
class ProductivityDryRule implements InsightRuleContract
{
    public function getName(): string
    {
        return 'productivity_dry';
    }

    /**
     * @param  float  $data  Nilai produktivitas kering dalam kg/ha
     */
    public function evaluate(mixed $data): ?InsightResult
    {
        $productivity = (float) $data;

        // Threshold proporsional 22% dari rule basah (1000/1300 kg/ha)
        $thresholdLow    = InsightConfig::getValue('productivity_dry', 'threshold_low',    220); // 1000 × 0.22
        $thresholdMedium = InsightConfig::getValue('productivity_dry', 'threshold_medium', 286); // 1300 × 0.22

        if ($productivity <= 0) {
            return null; // Tidak ada data produksi kering valid
        }

        // ── Rule 1: IF protas_kering < 220 THEN High Alert ──────────────
        if ($productivity < $thresholdLow) {
            return InsightResult::high(
                message: 'Produktivitas teh kering sangat rendah',
                recommendations: [
                    'Periksa rasio konversi basah-kering di pabrik pengolahan',
                    'Evaluasi kualitas pucuk: pemetikan dini meningkatkan rendemen kering',
                    'Cek kondisi mesin pelayuan (withering) dan pengeringan (drying)',
                    'Tingkatkan produksi basah sebagai basis utama rendemen kering',
                ],
                title: 'Peringatan Produktivitas Kering Rendah',
                insightType: 'productivity_dry',
            );
        }

        // ── Rule 2: IF 220 ≤ protas_kering < 286 THEN Medium Alert ──────
        if ($productivity < $thresholdMedium) {
            return InsightResult::medium(
                message: 'Produktivitas teh kering menengah, perlu optimasi',
                recommendations: [
                    'Monitor rendemen pengolahan (target rasio 1:4.5 basah:kering)',
                    'Pastikan prosedur withering dan rolling sesuai SOP',
                    'Evaluasi komposisi pucuk: proporsi peko muda mempengaruhi kualitas kering',
                ],
                title: 'Produktivitas Kering Perlu Perhatian',
                insightType: 'productivity_dry',
            );
        }

        // ── Rule 3: IF protas_kering ≥ 286 THEN Low Alert (Optimal) ─────
        return InsightResult::low(
            message: 'Produktivitas teh kering optimal',
            recommendations: [
                'Pertahankan standar pengolahan dan kualitas pucuk yang sudah baik',
                'Dokumentasikan rasio rendemen untuk benchmark kebun lain',
                'Pertimbangkan ekspansi kapasitas produksi kering jika permintaan meningkat',
            ],
            title: 'Produktivitas Kering Optimal',
            insightType: 'productivity_dry',
        );
    }
}
