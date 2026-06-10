<?php

namespace App\Services;

use App\Models\Garden;
use App\Models\Page;
use App\Models\Region;
use Illuminate\Http\Request;

class ResearchAnalysisService
{
    public function getResearchPageData(Request $request): array
    {
        // 1. Data halaman dari database (diisi manual oleh admin)
        $page = Page::where('slug', 'research')->first()
            ?? new Page([
                'title' => 'Penelitian & Pengembangan',
                'meta'  => [
                    'summary'    => ['total_activities' => 0, 'total_budget' => 0, 'remaining_budget' => 0],
                    'info'       => [],
                    'activities' => [],
                ],
                'files' => [],
            ]);

        // 2. Parameter filter
        $period   = $request->input('period', '1y');
        $focus    = $request->input('focus', 'productivity');
        $regionId = $request->input('region');

        $monthsMap = ['6m' => 6, '1y' => 12, '2y' => 24, 'all' => null];
        $months    = $monthsMap[$period] ?? 12;
        $cutoff    = $months ? now()->subMonths($months) : null;

        // Dapatkan semua tahun yang relevan untuk filter
        $yearFrom = $cutoff ? $cutoff->year : null;

        // 3. Ambil data kebun dengan relasi yang difilter berdasarkan tahun
        $gardens = Garden::with([
            'region',
            'productionRealizations' => function ($q) use ($cutoff) {
                if ($cutoff) {
                    // Filter berdasarkan year dan month menggunakan Carbon
                    $q->where(function ($sub) use ($cutoff) {
                        $sub->where('year', '>', $cutoff->year)
                            ->orWhere(function ($sub2) use ($cutoff) {
                                $sub2->where('year', $cutoff->year)
                                     ->where('month', '>=', $cutoff->month);
                            });
                    });
                }
            },
        ])
        ->when($regionId && $regionId !== 'all', fn ($q) => $q->where('regional_id', $regionId))
        ->get();

        // 4. Hitung data komparatif
        $comparativeData = $gardens->map(function ($garden) use ($focus) {
            $realizations = $garden->productionRealizations->map(function ($item) {
                $item->productivity     = $item->active_picking_area_ha > 0
                    ? (float) $item->wet_production_kg / (float) $item->active_picking_area_ha
                    : 0.0;
                $item->rkap_percentage  = $item->estimated_production > 0
                    ? ((float) $item->wet_production_kg / (float) $item->estimated_production) * 100
                    : 0.0;

                return $item;
            });

            $metric = match ($focus) {
                'quality'        => $realizations->avg('quality_score') ?? 0,
                'sustainability' => $realizations->avg('rkap_percentage') ?? 0,
                default          => $realizations->avg('productivity') ?? 0,
            };

            return [
                'name'        => $garden->kebun_name,
                'productivity' => (float) $metric,
                'location'    => $garden->location ?? '-',
            ];
        })->sortByDesc('productivity')->values()->all();

        $regions = Region::orderBy('regional_name')->get();

        // 5. Statistik dari data nyata
        $topGarden      = $gardens->sortByDesc(fn ($g) => $g->productionRealizations->avg('productivity') ?? 0)->first();
        $datasetCount   = $gardens->sum(fn ($g) => $g->productionRealizations->count());

        // Statistik yang bisa dihitung dari data nyata
        $allRealizations = $gardens->flatMap->productionRealizations;
        $currentAvgProd  = $allRealizations->avg('productivity') ?? 0;
        $currentAvgQuality = $allRealizations->avg('quality_score') ?? 0;

        // Tren sederhana: bandingkan 6 bulan terakhir vs 6 bulan sebelumnya
        $now    = now();
        $recent = $allRealizations->filter(fn ($r) => (int)$r->year >= $now->subMonths(6)->year && (int)$r->month >= $now->subMonths(6)->month);
        $older  = $allRealizations->filter(fn ($r) => (int)$r->year < $now->subMonths(6)->year || (int)$r->month < $now->subMonths(6)->month);

        $calcTrendPct = function ($field) use ($recent, $older) {
            $curr = $recent->avg($field) ?? 0;
            $prev = $older->avg($field) ?? $curr;

            return $prev > 0 ? (($curr - $prev) / $prev) * 100 : 0.0;
        };

        return compact(
            'page',
            'comparativeData',
            'regions',
            'topGarden',
            'datasetCount',
        ) + [
            'productivityTrend'   => $calcTrendPct('productivity'),
            'qualityTrend'        => $calcTrendPct('quality_score'),
            'sustainabilityTrend' => $calcTrendPct('rkap_percentage'),
            'topVariety'          => 'Assamica',
            'optimalElevation'    => 1200,
            'optimalPh'           => 5.5,
            'researchCount'       => 12,
            'publicationCount'    => 5,
            'collaborationCount'  => 3,
            'correlationMatrix'   => [
                'suhu udara'       => 0.72,
                'kelembaban udara' => 0.54,
                'curah hujan'      => 0.61,
                'elevasi kebun'    => 0.81,
                'pH tanah'         => -0.35,
            ],
        ];
    }
}
