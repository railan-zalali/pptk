<?php

namespace App\Services\RuleEngine;

use App\Models\InsightConfig;

/**
 * Rule untuk mengevaluasi mutu pucuk teh (skor 0–10).
 *
 * Threshold dikonfigurasi via tabel insight_configs:
 *   - quality.threshold_low  (default: 7.0)
 *   - quality.threshold_high (default: 8.5)
 */
class QualityRule implements InsightRuleContract
{
    public function getName(): string
    {
        return 'quality';
    }

    /**
     * @param  float  $data  Skor mutu pucuk (0–10)
     */
    public function evaluate(mixed $data): ?InsightResult
    {
        $qualityScore = (float) $data;

        if ($qualityScore <= 0) {
            return null; // Tidak ada data mutu
        }

        $thresholdLow  = InsightConfig::getValue('quality', 'threshold_low',  7.0);
        $thresholdHigh = InsightConfig::getValue('quality', 'threshold_high', 8.5);

        if ($qualityScore < $thresholdLow) {
            return InsightResult::high(
                message: 'Mutu pucuk rendah terdeteksi',
                recommendations: [
                    'Evaluasi waktu panen — pastikan pemetik mengambil daun muda (peko + 2)',
                    'Perbaiki teknik pemangkasan untuk merangsang tunas berkualitas',
                    'Tingkatkan program pengendalian hama & penyakit (Helopeltis, cacar daun)',
                    'Tinjau standar sortasi pucuk di lapangan',
                ],
                title: 'Peringatan Mutu Pucuk Rendah',
                insightType: 'quality',
            );
        }

        if ($qualityScore > $thresholdHigh) {
            return InsightResult::low(
                message: 'Mutu pucuk sangat baik',
                recommendations: [
                    'Pertahankan standar panen dan teknik pemetikan yang sudah diterapkan',
                    'Dokumentasikan kondisi iklim dan perlakuan yang menghasilkan mutu optimal ini',
                    'Bagikan praktik terbaik dengan afdeling atau kebun lain',
                ],
                title: 'Mutu Pucuk Sangat Baik',
                insightType: 'quality',
            );
        }

        return InsightResult::medium(
            message: 'Mutu pucuk standar — masih dapat ditingkatkan',
            recommendations: [
                'Monitor kualitas pucuk secara berkala setiap periode pemetikan',
                'Pertahankan standar pemetikan 2+1 (peko + 2 daun muda)',
                'Evaluasi faktor lingkungan: curah hujan, suhu, ketinggian',
            ],
            title: 'Mutu Pucuk Standar',
            insightType: 'quality',
        );
    }
}
