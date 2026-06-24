<?php

namespace App\Services\RuleEngine;

use App\Models\InsightConfig;

/**
 * Rule untuk mengevaluasi produktivitas perkebunan (kg/ha/tahun).
 *
 * Threshold dikonfigurasi via tabel insight_configs:
 *   - productivity.threshold_low    (default: 1000 kg/ha)
 *   - productivity.threshold_medium (default: 1300 kg/ha)
 */
class ProductivityRule implements InsightRuleContract
{
    public function getName(): string
    {
        return 'productivity';
    }

    /**
     * @param  float  $data  Nilai produktivitas dalam kg/ha
     */
    public function evaluate(mixed $data): ?InsightResult
    {
        $productivity = (float) $data;

        $thresholdLow    = InsightConfig::getValue('productivity', 'threshold_low',    1000);
        $thresholdMedium = InsightConfig::getValue('productivity', 'threshold_medium', 1300);

        if ($productivity <= 0) {
            return null; // Tidak ada data produksi valid
        }

        if ($productivity < $thresholdLow) {
            return InsightResult::high(
                message: 'Produktivitas rendah terdeteksi',
                recommendations: [
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
                message: 'Produktivitas menengah, perlu perhatian',
                recommendations: [
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
            message: 'Produktivitas optimal',
            recommendations: [
                'Pertahankan praktik agronomi yang sudah berjalan baik',
                'Dokumentasikan best practices untuk dijadikan benchmark kebun lain',
                'Bagikan pengalaman keberhasilan dengan kebun-kebun di regional',
            ],
            title: 'Produktivitas Optimal',
            insightType: 'productivity',
        );
    }
}
