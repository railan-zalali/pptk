<?php

namespace App\Services\RuleEngine;

use App\Models\InsightConfig;

/**
 * Rule untuk mengevaluasi produktivitas perkebunan (kg/ha/tahun).
 *
 * Prioritas threshold:
 *   1. target_protas_min dari PerformanceTarget kebun (jika tersedia)
 *   2. InsightConfig global (fallback jika target belum diisi)
 *
 * @param  array  $data  Harus berisi:
 *   - productivity     (float) nilai produktivitas kg/ha
 *   - target_min       (float|null) target_protas_min per kebun dari PerformanceTarget
 *   - target_max       (float|null) target_protas_max per kebun dari PerformanceTarget
 */
class ProductivityRule implements InsightRuleContract
{
    public function getName(): string
    {
        return 'productivity';
    }

    public function evaluate(mixed $data): ?InsightResult
    {
        // Support array (dengan target) atau float langsung (backward compat)
        if (is_array($data)) {
            $productivity = (float)($data['productivity'] ?? 0);
            $targetMin    = isset($data['target_min']) ? (float)$data['target_min'] : null;
            $targetMax    = isset($data['target_max']) ? (float)$data['target_max'] : null;
        } else {
            $productivity = (float)$data;
            $targetMin    = null;
            $targetMax    = null;
        }

        if ($productivity <= 0) {
            return null;
        }

        // Gunakan target per kebun jika tersedia, fallback ke InsightConfig global
        $thresholdLow    = $targetMin ?? InsightConfig::getValue('productivity', 'threshold_low',    1000);
        $thresholdMedium = $targetMax ?? InsightConfig::getValue('productivity', 'threshold_medium', 1300);

        $vsTargetNote = $targetMin
            ? "Target kebun: {$targetMin} kg/ha"
            : 'Menggunakan threshold standar nasional';

        if ($productivity < $thresholdLow) {
            return InsightResult::high(
                message: "Produktivitas rendah ({$productivity} kg/ha) — di bawah target minimum ({$thresholdLow} kg/ha)",
                recommendations: [
                    $vsTargetNote,
                    'Tingkatkan dosis pemupukan nitrogen sesuai rekomendasi agronomis',
                    'Evaluasi sistem drainase lahan agar tidak tergenang',
                    'Lakukan pemangkasan pohon yang tepat untuk regenerasi tunas',
                    'Periksa kepadatan populasi tanaman menghasilkan (TM)',
                ],
                title: 'Peringatan Produktivitas Rendah',
                insightType: 'productivity',
            );
        }

        if ($productivity < $thresholdMedium) {
            return InsightResult::medium(
                message: "Produktivitas menengah ({$productivity} kg/ha) — mendekati target ({$thresholdMedium} kg/ha)",
                recommendations: [
                    $vsTargetNote,
                    'Pertahankan dosis pemupukan saat ini dan evaluasi efektivitasnya',
                    'Monitor kondisi cuaca dan dampaknya terhadap pemetikan',
                    'Lakukan pemupukan daun (foliar) rutin sebagai suplemen',
                    'Pastikan rotasi pemetikan sesuai standar',
                ],
                title: 'Produktivitas Perlu Perhatian',
                insightType: 'productivity',
            );
        }

        return InsightResult::low(
            message: "Produktivitas optimal ({$productivity} kg/ha) — memenuhi atau melampaui target ({$thresholdLow} kg/ha)",
            recommendations: [
                $vsTargetNote,
                'Pertahankan praktik agronomi yang sudah berjalan baik',
                'Dokumentasikan best practices untuk dijadikan benchmark kebun lain',
                'Bagikan pengalaman keberhasilan dengan kebun-kebun di regional',
            ],
            title: 'Produktivitas Optimal',
            insightType: 'productivity',
        );
    }
}
