<?php

namespace App\Services;

use App\Models\Insight;

class InsightService
{
    public function generateProductivityInsight($gardenId, $currentProductivity)
    {
        $thresholdLow = 1000; // kg/ha/year
        $thresholdMedium = 1300; // kg/ha/year
        
        if ($currentProductivity < $thresholdLow) {
            $insight = [
                'alert_level' => 'high',
                'message' => 'Produktivitas rendah terdeteksi',
                'recommendations' => [
                    'Tingkatkan dosis pemupukan nitrogen',
                    'Evaluasi sistem drainase',
                    'Lakukan pemangkasan pohon yang tepat'
                ]
            ];
        } elseif ($currentProductivity < $thresholdMedium) {
            $insight = [
                'alert_level' => 'medium',
                'message' => 'Produktivitas menengah, perlu perhatian',
                'recommendations' => [
                    'Pertahankan dosis pemupukan saat ini',
                    'Monitor kondisi cuaca',
                    'Lakukan pemupukan daun rutin'
                ]
            ];
        } else {
            $insight = [
                'alert_level' => 'low',
                'message' => 'Produktivitas optimal',
                'recommendations' => [
                    'Pertahankan praktik baik saat ini',
                    'Dokumentasikan best practices',
                    'Bagikan pengalaman dengan kebun lain'
                ]
            ];
        }

        // Gunakan updateOrCreate agar tidak ada duplikat insight per kebun per tipe
        Insight::updateOrCreate(
            [
                'garden_id'    => $gardenId,
                'insight_type' => 'productivity',
            ],
            [
                'title'       => 'Analisis Produktivitas',
                'description' => $insight['message'],
                'message'     => $insight['message'],
                'alert_level' => $insight['alert_level'],
                'recommendations' => $insight['recommendations'],
                'generated_at' => now(),
            ]
        );

        return $insight;
    }

    public function generateQualityInsight($gardenId, $qualityScore)
    {
        $thresholdLow = 7.0;
        $thresholdHigh = 8.5;
        
        if ($qualityScore < $thresholdLow) {
            $insight = [
                'alert_level' => 'high',
                'message' => 'Mutu pucuk rendah',
                'recommendations' => [
                    'Evaluasi waktu panen',
                    'Perbaiki teknik pemangkasan',
                    'Tingkatkan pengendalian hama'
                ]
            ];
        } elseif ($qualityScore > $thresholdHigh) {
            $insight = [
                'alert_level' => 'low',
                'message' => 'Mutu pucuk sangat baik',
                'recommendations' => [
                    'Pertahankan standar panen',
                    'Dokumentasikan kondisi optimal',
                    'Bagikan praktik terbaik'
                ]
            ];
        } else {
            $insight = [
                'alert_level' => 'medium',
                'message' => 'Mutu pucuk standar',
                'recommendations' => [
                    'Monitor kualitas secara rutin',
                    'Pertahankan praktik saat ini',
                    'Evaluasi faktor lingkungan'
                ]
            ];
        }

        // Gunakan updateOrCreate agar tidak ada duplikat insight per kebun per tipe
        Insight::updateOrCreate(
            [
                'garden_id'    => $gardenId,
                'insight_type' => 'quality',
            ],
            [
                'title'       => 'Analisis Kualitas Pucuk',
                'description' => $insight['message'],
                'message'     => $insight['message'],
                'alert_level' => $insight['alert_level'],
                'recommendations' => $insight['recommendations'],
                'generated_at' => now(),
            ]
        );

        return $insight;
    }
}