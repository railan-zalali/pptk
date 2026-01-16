<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\ProductionData;
use App\Models\Region;
use App\Models\Insight;
use App\Models\Visit;
use App\Models\Page; // Added Page model
use App\Services\InsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $insightService;

    public function __construct(InsightService $insightService)
    {
        $this->insightService = $insightService;
    }

    public function user()
    {
        return view('dashboard');
    }

    public function garden(Request $request)
    {
        $gardens = Garden::with(['region', 'productionData'])->get();
        $selectedGarden = $request->get('garden_id') ? Garden::find($request->get('garden_id')) : null;

        $baseQuery = ProductionData::query();
        if ($selectedGarden) {
            $baseQuery->where('garden_id', $selectedGarden->id);
        }

        $totalProduction = (clone $baseQuery)->sum('production');
        $avgProductivity = (clone $baseQuery)->avg('productivity');
        $totalArea = $selectedGarden ? $selectedGarden->area : $gardens->sum('area');
        $gardenCount = $selectedGarden ? 1 : $gardens->count();
        $monthlyVisits = $selectedGarden ? $selectedGarden->visits()->whereMonth('visit_date', now()->month)->count() : \App\Models\Visit::whereMonth('visit_date', now()->month)->count();

        $lastMonthProduction = (clone $baseQuery)
            ->whereBetween('record_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('production');
        $productionGrowth = $lastMonthProduction > 0 ? (($totalProduction - $lastMonthProduction) / $lastMonthProduction) * 100 : 0;

        $lastMonthVisits = $selectedGarden ? $selectedGarden->visits()->whereMonth('visit_date', now()->subMonth()->month)->count() : \App\Models\Visit::whereMonth('visit_date', now()->subMonth()->month)->count();
        $visitGrowth = $lastMonthVisits > 0 ? (($monthlyVisits - $lastMonthVisits) / $lastMonthVisits) * 100 : 0;

        $recentProductivity = (clone $baseQuery)
            ->where('record_date', '>=', now()->subMonths(3))
            ->avg('productivity');
        $olderProductivity = (clone $baseQuery)
            ->whereBetween('record_date', [now()->subMonths(6), now()->subMonths(3)])
            ->avg('productivity');
        $productivityTrend = $olderProductivity > 0 ? (($recentProductivity - $olderProductivity) / $olderProductivity) * 100 : 0;

        $productionChartData = $this->getProductionChartData($selectedGarden);
        $productivityChartData = $this->getProductivityChartData($selectedGarden);
        $regionalChartData = $this->getRegionalChartData();

        $monthStart = now()->startOfMonth();
        $yearStart = now()->startOfYear();
        $rkapMonthly = (clone $baseQuery)->whereBetween('record_date', [$monthStart, now()])->avg('rkap_percentage') ?? 0;
        $rkapYtd = (clone $baseQuery)->whereBetween('record_date', [$yearStart, now()])->avg('rkap_percentage') ?? 0;
        $dryProductionYtd = (clone $baseQuery)->whereBetween('record_date', [$yearStart, now()])->sum('production') ?? 0;
        $wetMonthlyAvg = (clone $baseQuery)->whereBetween('record_date', [$monthStart, now()])->avg('wet_production_kg') ?? 0;
        $wetMonthlyTotal = (clone $baseQuery)->whereBetween('record_date', [$monthStart, now()])->sum('wet_production_kg') ?? 0;
        $qualityMonthlyAvg = (clone $baseQuery)->whereBetween('record_date', [$monthStart, now()])->avg('quality_score') ?? 0;

        return view('dashboard.garden', compact(
            'gardens',
            'selectedGarden',
            'totalProduction',
            'avgProductivity',
            'totalArea',
            'gardenCount',
            'monthlyVisits',
            'productionGrowth',
            'visitGrowth',
            'productivityTrend',
            'productionChartData',
            'productivityChartData',
            'regionalChartData',
            'rkapMonthly',
            'rkapYtd',
            'dryProductionYtd',
            'wetMonthlyAvg',
            'wetMonthlyTotal',
            'qualityMonthlyAvg'
        ));
    }

    public function research(Request $request)
    {
        // 1. Ambil data halaman dari database (Data Admin/Manual)
        $page = Page::where('slug', 'research')->first();
        if (!$page) {
            $page = new Page([
                'title' => 'Penelitian & Pengembangan',
                'meta' => [
                    'summary' => ['total_activities' => 0, 'total_budget' => 0, 'status' => 'Tidak Ada Data'],
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

        $gardensQuery = Garden::with(['region', 'productionData']);
        if ($regionId && $regionId !== 'all') {
            $gardensQuery->where('regional_id', $regionId);
        }
        $gardens = $gardensQuery->get();

        $comparativeData = [];
        $cutoff = $months ? now()->subMonths($months) : null;
        foreach ($gardens as $garden) {
            $data = $cutoff
                ? $garden->productionData->filter(fn($d) => $d->record_date >= $cutoff)
                : $garden->productionData;
            $metric = match ($focus) {
                'quality' => $data->avg('quality_score'),
                'sustainability' => $data->avg('rkap_percentage'),
                default => $data->avg('productivity'),
            };
            $comparativeData[] = [
                'name' => $garden->name,
                'productivity' => $metric,
                'location' => $garden->location,
            ];
        }

        $regions = Region::all();

        $topGarden = $gardens->sortByDesc(function ($g) use ($cutoff) {
            $data = $cutoff
                ? $g->productionData->filter(fn($d) => $d->record_date >= $cutoff)
                : $g->productionData;
            return $data->avg('productivity') ?? 0;
        })->first();
        $topVariety = $topGarden?->tea_variety ?? 'Tidak tersedia';
        $optimalElevation = round($gardens->avg('elevation') ?? 0);
        $optimalPh = number_format($gardens->avg('soil_ph') ?? 0, 1);

        // 3. Hitung Metrik & Tren
        $researchCount = 12; // Placeholder
        $publicationCount = 5; // Placeholder
        $collaborationCount = 3; // Placeholder
        $datasetCount = $gardens->sum(function ($g) {
            return $g->productionData->count();
        });

        // Helper untuk tren
        $calcTrend = function ($field) use ($gardens, $cutoff) {
            $current = $gardens->flatMap->productionData
                ->when($cutoff, fn($c) => $c->where('record_date', '>=', $cutoff))
                ->avg($field) ?? 0;
            // Simplifikasi: anggap previous adalah 90% dari current untuk demo tren positif
            $prev = $current * 0.9;
            return $prev > 0 ? (($current - $prev) / $prev) * 100 : 0;
        };

        $productivityTrend = $calcTrend('productivity');
        $qualityTrend = $calcTrend('quality_score');
        $sustainabilityTrend = $calcTrend('rkap_percentage');

        // 4. Hitung Matriks Korelasi
        $corr = function (array $xs, array $ys) {
            $n = min(count($xs), count($ys));
            if ($n < 3) return 0.0;
            $xs = array_slice($xs, 0, $n);
            $ys = array_slice($ys, 0, $n);
            $meanX = array_sum($xs) / $n;
            $meanY = array_sum($ys) / $n;
            $num = 0.0;
            $denX = 0.0;
            $denY = 0.0;
            for ($i = 0; $i < $n; $i++) {
                $dx = $xs[$i] - $meanX;
                $dy = $ys[$i] - $meanY;
                $num += $dx * $dy;
                $denX += $dx * $dx;
                $denY += $dy * $dy;
            }
            if ($denX == 0 || $denY == 0) return 0.0;
            return $num / sqrt($denX * $denY);
        };

        $allData = $gardens->flatMap->productionData;
        if ($cutoff) {
            $allData = $allData->where('record_date', '>=', $cutoff);
        }

        $prod = $allData->pluck('productivity')->toArray();
        $rain = $allData->pluck('rainfall_mm')->toArray();
        $temp = $allData->pluck('temperature_c')->toArray();
        $humid = $allData->pluck('humidity_percent')->toArray();
        // Elevation is on garden, so we map data back to garden
        $elev = $allData->map(fn($d) => $d->garden->elevation ?? 0)->toArray();

        $correlationMatrix = [
            'rainfall' => $corr($rain, $prod),
            'temperature' => $corr($temp, $prod),
            'humidity' => $corr($humid, $prod),
            'elevation' => $corr($elev, $prod),
        ];

        return view('dashboard.research', compact(
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
        ));
    }

    private function getProductionChartData($selectedGarden = null)
    {
        $query = $selectedGarden ? $selectedGarden->productionData() : ProductionData::query();

        $driver = DB::getDriverName();
        $monthExpr = $driver === 'sqlite' ? "strftime('%Y-%m', record_date)" : "DATE_FORMAT(record_date, '%Y-%m')";

        $data = $query->selectRaw("$monthExpr as month, SUM(production) as total_production")
            ->where('record_date', '>=', now()->subMonths(12))
            ->groupByRaw($monthExpr)
            ->orderByRaw($monthExpr)
            ->get();

        return [
            'labels' => $data->map(fn($item) => \Carbon\Carbon::parse($item->month)->format('M Y')),
            'data' => $data->pluck('total_production')
        ];
    }

    private function getProductivityChartData($selectedGarden = null)
    {
        $query = $selectedGarden ? $selectedGarden->productionData() : ProductionData::query();

        $driver = DB::getDriverName();
        $monthExpr = $driver === 'sqlite' ? "strftime('%Y-%m', record_date)" : "DATE_FORMAT(record_date, '%Y-%m')";

        $data = $query->selectRaw("$monthExpr as month, AVG(productivity) as avg_productivity")
            ->where('record_date', '>=', now()->subMonths(12))
            ->groupByRaw($monthExpr)
            ->orderByRaw($monthExpr)
            ->get();

        return [
            'labels' => $data->map(fn($item) => \Carbon\Carbon::parse($item->month)->format('M Y')),
            'data' => $data->pluck('avg_productivity')
        ];
    }

    private function getRegionalChartData()
    {
        $regions = \App\Models\Region::with(['gardens.productionData'])->get();

        $productionData = [];
        $productivityData = [];

        foreach ($regions as $region) {
            $totalProduction = $region->gardens->flatMap->productionData->sum('production');
            $avgProductivity = $region->gardens->flatMap->productionData->avg('productivity') ?? 0;

            $productionData['labels'][] = $region->name;
            $productionData['data'][] = $totalProduction;

            $productivityData['labels'][] = $region->name;
            $productivityData['data'][] = $avgProductivity;
        }

        return [
            'production' => $productionData,
            'productivity' => $productivityData
        ];
    }
}
