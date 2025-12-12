<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\ProductionData;
use App\Models\Region;
use App\Models\Insight;
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
        $gardens = Garden::with('region')->get();
        $selectedGarden = $request->get('garden_id') ? Garden::find($request->get('garden_id')) : null;

        // Calculate key metrics
        $query = $selectedGarden ? $selectedGarden->productionData() : ProductionData::query();

        $totalProduction = $query->sum('production');
        $avgProductivity = $query->avg('productivity');
        $totalArea = $selectedGarden ? $selectedGarden->area : $gardens->sum('area');
        $gardenCount = $selectedGarden ? 1 : $gardens->count();
        $monthlyVisits = $selectedGarden ? $selectedGarden->visits()->whereMonth('visit_date', now()->month)->count() : \App\Models\Visit::whereMonth('visit_date', now()->month)->count();

        // Calculate growth percentages
        $lastMonthProduction = $query->whereBetween('record_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('production');
        $productionGrowth = $lastMonthProduction > 0 ? (($totalProduction - $lastMonthProduction) / $lastMonthProduction) * 100 : 0;

        $lastMonthVisits = $selectedGarden ? $selectedGarden->visits()->whereMonth('visit_date', now()->subMonth()->month)->count() : \App\Models\Visit::whereMonth('visit_date', now()->subMonth()->month)->count();
        $visitGrowth = $lastMonthVisits > 0 ? (($monthlyVisits - $lastMonthVisits) / $lastMonthVisits) * 100 : 0;

        // Calculate productivity trend
        $recentProductivity = $query->where('record_date', '>=', now()->subMonths(3))->avg('productivity');
        $olderProductivity = $query->whereBetween('record_date', [now()->subMonths(6), now()->subMonths(3)])->avg('productivity');
        $productivityTrend = $olderProductivity > 0 ? (($recentProductivity - $olderProductivity) / $olderProductivity) * 100 : 0;

        // Prepare chart data
        $productionChartData = $this->getProductionChartData($selectedGarden);
        $productivityChartData = $this->getProductivityChartData($selectedGarden);
        $regionalChartData = $this->getRegionalChartData();

        $monthStart = now()->startOfMonth();
        $yearStart = now()->startOfYear();
        $rkapMonthly = $query->whereBetween('record_date', [$monthStart, now()])->avg('rkap_percentage') ?? 0;
        $rkapYtd = $query->whereBetween('record_date', [$yearStart, now()])->avg('rkap_percentage') ?? 0;
        $dryProductionYtd = $query->whereBetween('record_date', [$yearStart, now()])->sum('production') ?? 0;
        $wetMonthlyAvg = $query->whereBetween('record_date', [$monthStart, now()])->avg('wet_production_kg') ?? 0;
        $wetMonthlyTotal = $query->whereBetween('record_date', [$monthStart, now()])->sum('wet_production_kg') ?? 0;
        $qualityMonthlyAvg = $query->whereBetween('record_date', [$monthStart, now()])->avg('quality_score') ?? 0;

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
        $period = $request->get('period', '1y');
        $focus = $request->get('focus', 'productivity');
        $regionId = $request->get('region');

        $monthsMap = ['6m' => 6, '1y' => 12, '2y' => 24, 'all' => null];
        $months = $monthsMap[$period] ?? 12;

        $gardensQuery = Garden::with('region');
        if ($regionId && $regionId !== 'all') {
            $gardensQuery->where('region_id', $regionId);
        }
        $gardens = $gardensQuery->get();

        $comparativeData = [];
        foreach ($gardens as $garden) {
            $dataQuery = $garden->productionData();
            if ($months) {
                $dataQuery->where('record_date', '>=', now()->subMonths($months));
            }

            $metric = match ($focus) {
                'quality' => $dataQuery->avg('quality_score'),
                'sustainability' => $dataQuery->avg('rkap_percentage'),
                default => $dataQuery->avg('productivity'),
            };

            $comparativeData[] = [
                'name' => $garden->name,
                'productivity' => $metric,
                'location' => $garden->location,
            ];
        }

        $regions = Region::all();

        $topGarden = $gardens->sortByDesc(function ($g) use ($months) {
            $q = $g->productionData();
            if ($months) {
                $q->where('record_date', '>=', now()->subMonths($months));
            }
            return $q->avg('productivity') ?? 0;
        })->first();
        $topVariety = $topGarden?->tea_variety ?? 'Tidak tersedia';
        $optimalElevation = round($gardens->avg('elevation') ?? 0);
        $optimalPh = number_format($gardens->avg('soil_ph') ?? 0, 1);

        $researchCount = Insight::count();
        $publicationCount = 0;
        $collaborationCount = 0;
        $datasetCount = ProductionData::when($regionId && $regionId !== 'all', function ($q) use ($regionId) {
            $q->whereHas('garden', fn($g) => $g->where('region_id', $regionId));
        })->count();

        $recentBase = now()->subMonths(3);
        $olderBase = now()->subMonths(6);
        $baseFilter = function ($query) use ($regionId) {
            if ($regionId && $regionId !== 'all') {
                $query->whereHas('garden', fn($g) => $g->where('region_id', $regionId));
            }
            return $query;
        };

        $recentPeriodAvg = $baseFilter(ProductionData::query())
            ->where('record_date', '>=', $recentBase)
            ->avg('productivity') ?? 0;
        $olderPeriodAvg = $baseFilter(ProductionData::query())
            ->whereBetween('record_date', [$olderBase, $recentBase])
            ->avg('productivity') ?? 0;
        $productivityTrend = $olderPeriodAvg > 0 ? (($recentPeriodAvg - $olderPeriodAvg) / $olderPeriodAvg) * 100 : 0;
        $qualityRecent = $baseFilter(ProductionData::query())
            ->where('record_date', '>=', $recentBase)
            ->avg('quality_score') ?? 0;
        $qualityOlder = $baseFilter(ProductionData::query())
            ->whereBetween('record_date', [$olderBase, $recentBase])
            ->avg('quality_score') ?? 0;
        $qualityTrend = $qualityOlder > 0 ? (($qualityRecent - $qualityOlder) / $qualityOlder) * 100 : 0;

        $sustainRecent = $baseFilter(ProductionData::query())
            ->where('record_date', '>=', $recentBase)
            ->avg('rkap_percentage') ?? 0;
        $sustainOlder = $baseFilter(ProductionData::query())
            ->whereBetween('record_date', [$olderBase, $recentBase])
            ->avg('rkap_percentage') ?? 0;
        $sustainabilityTrend = $sustainOlder > 0 ? (($sustainRecent - $sustainOlder) / $sustainOlder) * 100 : 0;

        $correlationMatrix = [
            'Produktivitas' => [
                'Kualitas' => 0.45,
                'pH Tanah' => 0.30,
                'Curah Hujan' => 0.25,
                'Elevasi' => 0.15,
            ],
            'Kualitas' => [
                'Produktivitas' => 0.45,
                'pH Tanah' => 0.20,
                'Curah Hujan' => 0.10,
                'Elevasi' => -0.05,
            ],
            'pH Tanah' => [
                'Produktivitas' => 0.30,
                'Kualitas' => 0.20,
                'Curah Hujan' => -0.10,
                'Elevasi' => -0.20,
            ],
            'Curah Hujan' => [
                'Produktivitas' => 0.25,
                'Kualitas' => 0.10,
                'pH Tanah' => -0.10,
                'Elevasi' => 0.05,
            ],
            'Elevasi' => [
                'Produktivitas' => 0.15,
                'Kualitas' => -0.05,
                'pH Tanah' => -0.20,
                'Curah Hujan' => 0.05,
            ],
        ];

        $recentPublications = [];
        $collaborationInstitutions = [];
        $topResearchers = [];
        $researchAreas = [];

        $chartData = [
            'productivityComparison' => [
                'labels' => collect($comparativeData)->pluck('name'),
                'data' => collect($comparativeData)->pluck('productivity')->map(fn($v) => $v ?? 0),
            ],
            'environmentalFactors' => [
                'labels' => ['pH Tanah', 'Curah Hujan', 'Elevasi', 'Suhu', 'Kelembaban'],
                'optimal' => [70, 80, 75, 65, 85],
                'actual' => [65, 75, 70, 60, 80],
            ],
        ];

        return view('dashboard.research', compact(
            'gardens',
            'comparativeData',
            'regions',
            'period',
            'focus',
            'regionId',
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
            'recentPublications',
            'collaborationInstitutions',
            'topResearchers',
            'researchAreas',
            'chartData'
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
