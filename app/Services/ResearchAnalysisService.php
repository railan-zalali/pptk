<?php

namespace App\Services;

use App\Models\Garden;
use App\Models\Page;
use App\Models\Region;
use App\Models\ResearchBudget;
use Illuminate\Http\Request;

class ResearchAnalysisService
{
    public function getResearchPageData(Request $request)
    {
        // 1. Ambil data halaman dari database (Data Admin/Manual)
        $page = Page::where('slug', 'research')->first();
        if (!$page) {
            $page = new Page([
                'title' => 'Penelitian & Pengembangan',
                'meta'  => [
                    'summary'    => ['total_activities' => 0, 'total_budget' => 0, 'remaining_budget' => 0],
                    'info'       => [],
                    'activities' => [],
                ],
                'files' => [],
            ]);
        }

        // 2. Ambil parameter filter
        $period   = $request->get('period', '1y');
        $focus    = $request->get('focus', 'productivity');
        $regionId = $request->get('region');

        $monthsMap = ['6m' => 6, '1y' => 12, '2y' => 24, 'all' => null];
        $months    = $monthsMap[$period] ?? 12;

        // 3. Bangun query kebun
        $gardensQuery = Garden::with(['region', 'productionRealizations']);
        if ($regionId && $regionId !== 'all') {
            $gardensQuery->where('regional_id', $regionId);
        }
        $gardens = $gardensQuery->get();

        // 4. Tentukan rentang waktu untuk periode sekarang dan sebelumnya (tren)
        $cutoffCurrent  = $months ? now()->subMonths($months) : null;
        $cutoffPrevious = $months ? now()->subMonths($months * 2) : null;

        $comparativeData = [];

        foreach ($gardens as $garden) {
            // Map realizations ke format standar
            $realizations = $garden->productionRealizations->map(function ($item) {
                $item->record_date    = \Carbon\Carbon::create($item->year, $item->month, 1)->endOfMonth();
                $item->productivity   = $item->active_picking_area_ha > 0
                    ? $item->wet_production_kg / $item->active_picking_area_ha
                    : 0;
                $item->rkap_percentage = $item->estimated_production > 0
                    ? ($item->wet_production_kg / $item->estimated_production) * 100
                    : 0;
                return $item;
            });

            // Data periode saat ini
            $currentData = $cutoffCurrent
                ? $realizations->filter(fn($d) => $d->record_date >= $cutoffCurrent)
                : $realizations;

            $metric = match ($focus) {
                'quality'        => $currentData->avg('quality_score'),
                'sustainability' => $currentData->avg('rkap_percentage'),
                default          => $currentData->avg('productivity'),
            };

            $comparativeData[] = [
                'name'         => $garden->kebun_name,
                'productivity' => $metric ?? 0,
                'location'     => $garden->location,
            ];

            $garden->currentData  = $currentData;

            // Data periode sebelumnya (untuk tren nyata)
            $garden->previousData = ($cutoffCurrent && $cutoffPrevious)
                ? $realizations->filter(fn($d) => $d->record_date >= $cutoffPrevious && $d->record_date < $cutoffCurrent)
                : collect();
        }

        $regions = Region::all();

        // 5. Top Garden berdasarkan rata-rata produktivitas
        $topGarden        = $gardens->sortByDesc(fn($g) => $g->currentData->avg('productivity') ?? 0)->first();
        $topVariety       = $topGarden?->plant_type ?? 'Assamica';
        $optimalElevation = 1200; // agroklimat ideal (m dpl)
        $optimalPh        = 5.5;  // pH tanah ideal kebun teh

        // 6. Metrik dari Data Nyata (bukan placeholder)
        $researchPage       = Page::where('slug', 'research')->first();
        $activities         = $researchPage?->meta['activities'] ?? [];
        $researchCount      = count($activities);
        $datasetCount       = $gardens->sum(fn($g) => $g->currentData->count());
        $publicationCount   = (int)($researchPage?->meta['summary']['total_activities'] ?? 0);
        $collaborationCount = count(array_filter($activities, fn($a) => ($a['type'] ?? '') === 'external'));

        // 7. Hitung Tren Nyata: current vs previous period
        $calcTrend = function (string $field) use ($gardens): float {
            $allCurrent  = $gardens->flatMap->currentData;
            $allPrevious = $gardens->flatMap->previousData;

            $current  = $allCurrent->avg($field)  ?? 0;
            $previous = $allPrevious->avg($field) ?? 0;

            if ($previous <= 0) {
                return 0; // Tidak ada data pembanding
            }

            return (($current - $previous) / $previous) * 100;
        };

        $productivityTrend   = $calcTrend('productivity');
        $qualityTrend        = $calcTrend('quality_score');
        $sustainabilityTrend = $calcTrend('rkap_percentage');

        $correlationMatrix = $this->calculateCorrelationMatrix($gardens);

        // Get budget data for current year
        $budgetYear = date('Y');
        $budgetSummary = ResearchBudget::getAnnualSummary($budgetYear);
        $budgetGrandTotals = ResearchBudget::getGrandTotals($budgetYear);

        return compact(
            'page',
            'comparativeData',
            'regions',
            'topVariety',
            'optimalElevation',
            'optimalPh',
            'researchCount',
            'publicationCount',
            'collaborationCount',
            'datasetCount',
            'productivityTrend',
            'qualityTrend',
            'sustainabilityTrend',
            'correlationMatrix',
            'budgetSummary',
            'budgetGrandTotals',
            'budgetYear'
        );
    }

    /**
     * Hitung korelasi Pearson antara produktivitas dan quality_score per kebun.
     * Mengembalikan array dengan nilai korelasi dan interpretasinya.
     */
    private function calculateCorrelationMatrix($gardens): array
    {
        $prodValues    = [];
        $qualityValues = [];

        foreach ($gardens as $garden) {
            $data = $garden->currentData ?? collect();
            if ($data->count() < 2) {
                continue;
            }

            $avgProd    = $data->avg('productivity') ?? 0;
            $avgQuality = $data->avg('quality_score') ?? 0;

            if ($avgProd > 0 && $avgQuality > 0) {
                $prodValues[]    = $avgProd;
                $qualityValues[] = $avgQuality;
            }
        }

        if (count($prodValues) < 2) {
            return [];
        }

        // Pearson Correlation Coefficient
        $n     = count($prodValues);
        $sumX  = array_sum($prodValues);
        $sumY  = array_sum($qualityValues);
        $sumXY = 0;
        $sumX2 = 0;
        $sumY2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $prodValues[$i] * $qualityValues[$i];
            $sumX2 += $prodValues[$i] ** 2;
            $sumY2 += $qualityValues[$i] ** 2;
        }

        $numerator   = ($n * $sumXY) - ($sumX * $sumY);
        $denominator = sqrt((($n * $sumX2) - ($sumX ** 2)) * (($n * $sumY2) - ($sumY ** 2)));
        $correlation = $denominator > 0 ? $numerator / $denominator : 0;

        return [
            'productivity_vs_quality' => round($correlation, 4),
            'interpretation'          => $this->interpretCorrelation($correlation),
            'data_points'             => $n,
        ];
    }

    private function interpretCorrelation(float $r): string
    {
        $abs       = abs($r);
        $direction = $r >= 0 ? 'positif' : 'negatif';

        if ($abs >= 0.8) return "Korelasi kuat {$direction}";
        if ($abs >= 0.5) return "Korelasi sedang {$direction}";
        if ($abs >= 0.3) return "Korelasi lemah {$direction}";

        return 'Tidak ada korelasi signifikan';
    }
}
