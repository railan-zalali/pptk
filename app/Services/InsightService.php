<?php

namespace App\Services;

use App\Models\Insight;
use App\Models\ProductionRealization;
use Illuminate\Support\Collection;

class InsightService
{
    private const THRESHOLD_PRODUCTIVITY_LOW    = 1000.0; // kg/ha/tahun
    private const THRESHOLD_PRODUCTIVITY_MEDIUM = 1300.0;
    private const THRESHOLD_QUALITY_LOW         = 7.0;
    private const THRESHOLD_QUALITY_HIGH        = 8.5;

    /**
     * Ambil insight terbaru untuk ditampilkan di dashboard.
     */
    public function getInsightsForDashboard(int $limit = 5): Collection
    {
        return Insight::with('garden')
            ->orderByDesc('generated_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Ambil insight dengan level alert tinggi — untuk notifikasi kritis.
     */
    public function getCriticalInsights(): Collection
    {
        return Insight::with('garden')
            ->where('alert_level', 'high')
            ->orderByDesc('generated_at')
            ->limit(10)
            ->get();
    }

    /**
     * Generate dan simpan insight produktivitas untuk sebuah kebun.
     */
    public function generateProductivityInsight(int $gardenId, float $currentProductivity): array
    {
        if ($currentProductivity < self::THRESHOLD_PRODUCTIVITY_LOW) {
            $insight = [
                'alert_level'     => 'high',
                'title'           => 'Produktivitas Rendah',
                'message'         => "Produktivitas kebun {$currentProductivity} kg/ha berada di bawah ambang batas minimum " . self::THRESHOLD_PRODUCTIVITY_LOW . " kg/ha.",
                'recommendations' => [
                    'Tingkatkan dosis pemupukan nitrogen',
                    'Evaluasi sistem drainase lahan',
                    'Lakukan pemangkasan pohon yang tepat waktu',
                ],
            ];
        } elseif ($currentProductivity < self::THRESHOLD_PRODUCTIVITY_MEDIUM) {
            $insight = [
                'alert_level'     => 'medium',
                'title'           => 'Produktivitas Perlu Perhatian',
                'message'         => "Produktivitas {$currentProductivity} kg/ha masih dalam kategori menengah, perlu peningkatan.",
                'recommendations' => [
                    'Pertahankan dosis pemupukan saat ini',
                    'Monitor kondisi cuaca dan dampaknya',
                    'Lakukan pemupukan daun rutin',
                ],
            ];
        } else {
            $insight = [
                'alert_level'     => 'low',
                'title'           => 'Produktivitas Optimal',
                'message'         => "Produktivitas {$currentProductivity} kg/ha sudah optimal. Pertahankan praktik baik!",
                'recommendations' => [
                    'Pertahankan praktik pemupukan yang sudah baik',
                    'Dokumentasikan best practices untuk replikasi',
                    'Bagikan pengalaman dengan kebun lain',
                ],
            ];
        }

        Insight::create([
            'garden_id'    => $gardenId,
            'title'        => $insight['title'],
            'insight_type' => 'productivity',
            'message'      => $insight['message'],
            'alert_level'  => $insight['alert_level'],
            'recommendations' => $insight['recommendations'],
            'generated_at' => now(),
        ]);

        return $insight;
    }

    /**
     * Generate dan simpan insight mutu pucuk untuk sebuah kebun.
     */
    public function generateQualityInsight(int $gardenId, float $qualityScore): array
    {
        if ($qualityScore < self::THRESHOLD_QUALITY_LOW) {
            $insight = [
                'alert_level'     => 'high',
                'title'           => 'Mutu Pucuk Rendah',
                'message'         => "Skor mutu pucuk {$qualityScore} berada di bawah standar minimum " . self::THRESHOLD_QUALITY_LOW . ".",
                'recommendations' => [
                    'Evaluasi waktu dan teknik panen',
                    'Perbaiki teknik pemangkasan',
                    'Tingkatkan pengendalian hama dan penyakit',
                ],
            ];
        } elseif ($qualityScore > self::THRESHOLD_QUALITY_HIGH) {
            $insight = [
                'alert_level'     => 'low',
                'title'           => 'Mutu Pucuk Sangat Baik',
                'message'         => "Skor mutu pucuk {$qualityScore} sangat baik melampaui target " . self::THRESHOLD_QUALITY_HIGH . "!",
                'recommendations' => [
                    'Pertahankan standar panen yang konsisten',
                    'Dokumentasikan kondisi optimal saat ini',
                    'Bagikan praktik terbaik ke kebun lain',
                ],
            ];
        } else {
            $insight = [
                'alert_level'     => 'medium',
                'title'           => 'Mutu Pucuk Standar',
                'message'         => "Skor mutu pucuk {$qualityScore} masih dalam kategori standar.",
                'recommendations' => [
                    'Monitor kualitas pucuk secara rutin',
                    'Pertahankan praktik panen saat ini',
                    'Evaluasi faktor lingkungan yang mempengaruhi',
                ],
            ];
        }

        Insight::create([
            'garden_id'    => $gardenId,
            'title'        => $insight['title'],
            'insight_type' => 'quality',
            'message'      => $insight['message'],
            'alert_level'  => $insight['alert_level'],
            'recommendations' => $insight['recommendations'],
            'generated_at' => now(),
        ]);

        return $insight;
    }
}