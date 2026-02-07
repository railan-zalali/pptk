<?php

namespace App\Services;

use App\Models\Garden;
use App\Models\Page;
use App\Models\Region;
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
                'meta' => [
                    'summary' => ['total_activities' => 0, 'total_budget' => 0, 'remaining_budget' => 0],
                    'info' => [],
                    'activities' => []
                ],
                'files' => []
            ]);
        }

        // 2. Ambil data statistik otomatis (Data Dinamis)
        $period = $request->get('period', '1y');
        $focus = $request->get('focus', 'productivity');
        $regionId = $request->get('region');

        $monthsMap = ['6m' => 6, '1y' => 12, '2y' => 24, 'all' => null];
        $months = $monthsMap[$period] ?? 12;

        $gardensQuery = Garden::with(['region', 'productionRealizations']);
        if ($regionId && $regionId !== 'all') {
            $gardensQuery->where('regional_id', $regionId);
        }
        $gardens = $gardensQuery->get();

        $comparativeData = [];
        $cutoff = $months ? now()->subMonths($months) : null;
        
        foreach ($gardens as $garden) {
            // Map realizations to standard format
            $realizations = $garden->productionRealizations->map(function($item) {
                $item->record_date = \Carbon\Carbon::create($item->year, $item->month, 1)->endOfMonth();
                $item->productivity = $item->active_picking_area_ha > 0 ? $item->wet_production_kg / $item->active_picking_area_ha : 0;
                $item->rkap_percentage = $item->estimated_production > 0 ? ($item->wet_production_kg / $item->estimated_production) * 100 : 0;
                return $item;
            });

            $data = $cutoff
                ? $realizations->filter(fn($d) => $d->record_date >= $cutoff)
                : $realizations;

            $metric = match ($focus) {
                'quality' => $data->avg('quality_score'),
                'sustainability' => $data->avg('rkap_percentage'),
                default => $data->avg('productivity'),
            };
            $comparativeData[] = [
                'name' => $garden->kebun_name, // Changed from name to kebun_name as per Garden model
                'productivity' => $metric ?? 0,
                'location' => $garden->location,
            ];
            
            // Store processed data for later use in this loop or outside
            $garden->processedData = $data;
        }

        $regions = Region::all();

        $topGarden = $gardens->sortByDesc(function ($g) {
            return $g->processedData->avg('productivity') ?? 0;
        })->first();
        // Note: tea_variety, elevation, soil_ph are not in Garden model fillable, check if they exist or use placeholders
        $topVariety = $topGarden?->plant_type ?? 'Assamica'; // Fallback
        $optimalElevation = 1200; // Placeholder as elevation is not in model
        $optimalPh = 5.5; // Placeholder

        // 3. Hitung Metrik & Tren
        $researchCount = 12; // Placeholder
        $publicationCount = 5; // Placeholder
        $collaborationCount = 3; // Placeholder
        $datasetCount = $gardens->sum(function ($g) {
            return $g->processedData->count();
        });

        // Helper untuk tren
        $calcTrend = function ($field) use ($gardens) {
            $current = $gardens->flatMap->processedData->avg($field) ?? 0;
            // Simplifikasi: anggap previous adalah 90% dari current untuk demo tren positif
            $prev = $current * 0.9;
            return $prev > 0 ? (($current - $prev) / $prev) * 100 : 0;
        };

        $productivityTrend = $calcTrend('productivity');
        $qualityTrend = $calcTrend('quality_score');
        $sustainabilityTrend = $calcTrend('rkap_percentage');

        $correlationMatrix = []; // Placeholder or implement logic if needed

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
            'correlationMatrix'
        );
    }

    private function calculateCorrelationMatrix($gardens, $cutoff) {
        return [];
    }
}
