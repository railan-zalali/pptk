<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\ProductionData;
use App\Models\Region;
use App\Models\Insight;
use App\Models\Visit;
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
        $period = $request->get('period', '1y');
        $focus = $request->get('focus', 'productivity');
        $regionId = $request->get('region');

        $monthsMap = ['6m' => 6, '1y' => 12, '2y' => 24, 'all' => null];
        $months = $monthsMap[$period] ?? 12;

        $gardensQuery = Garden::with(['region', 'productionData']);
        if ($regionId && $regionId !== 'all') {
            $gardensQuery->where('region_id', $regionId);
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

        $researchCount = Insight::count();
        $publicationCount = Insight::count();
        $collaborationCount = Visit::count();
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

        $recordsQuery = ProductionData::with('garden');
        if ($regionId && $regionId !== 'all') {
            $recordsQuery->whereHas('garden', fn($g) => $g->where('region_id', $regionId));
        }
        if ($months) {
            $recordsQuery->where('record_date', '>=', now()->subMonths($months));
        }
        $records = $recordsQuery->get();

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
            if ($denX == 0.0 || $denY == 0.0) return 0.0;
            return max(-1.0, min(1.0, $num / (sqrt($denX) * sqrt($denY))));
        };

        $collectPairs = function ($getterX, $getterY) use ($records) {
            $xs = [];
            $ys = [];
            foreach ($records as $r) {
                $x = $getterX($r);
                $y = $getterY($r);
                if ($x !== null && $y !== null) {
                    $xs[] = (float)$x;
                    $ys[] = (float)$y;
                }
            }
            return [$xs, $ys];
        };

        [$prod_vs_quality_x, $prod_vs_quality_y] = $collectPairs(
            fn($r) => $r->productivity,
            fn($r) => $r->quality_score
        );
        [$prod_vs_ph_x, $prod_vs_ph_y] = $collectPairs(
            fn($r) => $r->productivity,
            fn($r) => optional($r->garden)->soil_ph
        );
        [$prod_vs_rain_x, $prod_vs_rain_y] = $collectPairs(
            fn($r) => $r->productivity,
            fn($r) => $r->rainfall_mm
        );
        [$prod_vs_elev_x, $prod_vs_elev_y] = $collectPairs(
            fn($r) => $r->productivity,
            fn($r) => optional($r->garden)->elevation
        );

        [$quality_vs_ph_x, $quality_vs_ph_y] = $collectPairs(
            fn($r) => $r->quality_score,
            fn($r) => optional($r->garden)->soil_ph
        );
        [$quality_vs_rain_x, $quality_vs_rain_y] = $collectPairs(
            fn($r) => $r->quality_score,
            fn($r) => $r->rainfall_mm
        );
        [$quality_vs_elev_x, $quality_vs_elev_y] = $collectPairs(
            fn($r) => $r->quality_score,
            fn($r) => optional($r->garden)->elevation
        );

        [$ph_vs_rain_x, $ph_vs_rain_y] = $collectPairs(
            fn($r) => optional($r->garden)->soil_ph,
            fn($r) => $r->rainfall_mm
        );
        [$ph_vs_elev_x, $ph_vs_elev_y] = $collectPairs(
            fn($r) => optional($r->garden)->soil_ph,
            fn($r) => optional($r->garden)->elevation
        );
        [$rain_vs_elev_x, $rain_vs_elev_y] = $collectPairs(
            fn($r) => $r->rainfall_mm,
            fn($r) => optional($r->garden)->elevation
        );

        $corr_prod_quality = $corr($prod_vs_quality_x, $prod_vs_quality_y);
        $corr_prod_ph = $corr($prod_vs_ph_x, $prod_vs_ph_y);
        $corr_prod_rain = $corr($prod_vs_rain_x, $prod_vs_rain_y);
        $corr_prod_elev = $corr($prod_vs_elev_x, $prod_vs_elev_y);

        $corr_quality_ph = $corr($quality_vs_ph_x, $quality_vs_ph_y);
        $corr_quality_rain = $corr($quality_vs_rain_x, $quality_vs_rain_y);
        $corr_quality_elev = $corr($quality_vs_elev_x, $quality_vs_elev_y);

        $corr_ph_rain = $corr($ph_vs_rain_x, $ph_vs_rain_y);
        $corr_ph_elev = $corr($ph_vs_elev_x, $ph_vs_elev_y);
        $corr_rain_elev = $corr($rain_vs_elev_x, $rain_vs_elev_y);

        $correlationMatrix = [
            'Produktivitas' => [
                'Kualitas' => $corr_prod_quality,
                'pH Tanah' => $corr_prod_ph,
                'Curah Hujan' => $corr_prod_rain,
                'Elevasi' => $corr_prod_elev,
            ],
            'Kualitas' => [
                'Produktivitas' => $corr_prod_quality,
                'pH Tanah' => $corr_quality_ph,
                'Curah Hujan' => $corr_quality_rain,
                'Elevasi' => $corr_quality_elev,
            ],
            'pH Tanah' => [
                'Produktivitas' => $corr_prod_ph,
                'Kualitas' => $corr_quality_ph,
                'Curah Hujan' => $corr_ph_rain,
                'Elevasi' => $corr_ph_elev,
            ],
            'Curah Hujan' => [
                'Produktivitas' => $corr_prod_rain,
                'Kualitas' => $corr_quality_rain,
                'pH Tanah' => $corr_ph_rain,
                'Elevasi' => $corr_rain_elev,
            ],
            'Elevasi' => [
                'Produktivitas' => $corr_prod_elev,
                'Kualitas' => $corr_quality_elev,
                'pH Tanah' => $corr_ph_elev,
                'Curah Hujan' => $corr_rain_elev,
            ],
        ];

        $recentPublications = Insight::with('garden')
            ->orderByDesc('generated_at')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($insight) {
                return (object) [
                    'title' => $insight->title ?? ('Insight: ' . ($insight->insight_type ?? 'Umum')),
                    'authors' => 'Tim Penelitian PPTK',
                    'journal' => 'Laporan Internal',
                    'keywords' => array_values(array_filter([
                        $insight->insight_type,
                        $insight->alert_level,
                        optional($insight->garden)->tea_variety,
                    ])),
                    'published_date' => $insight->generated_at ?? $insight->created_at,
                ];
            });

        $collaborationInstitutions = Region::with(['gardens.visits'])
            ->get()
            ->map(function ($region) {
                $projects = $region->gardens->sum(function ($g) {
                    return $g->visits->count();
                });
                return (object) [
                    'name' => $region->name,
                    'projects' => $projects,
                ];
            })
            ->sortByDesc('projects')
            ->take(5)
            ->values();

        $topResearchers = Visit::select('visitor_name', DB::raw('COUNT(*) as publications'))
            ->whereNotNull('visitor_name')
            ->groupBy('visitor_name')
            ->orderByDesc('publications')
            ->take(5)
            ->get()
            ->map(function ($row) {
                return (object) [
                    'name' => $row->visitor_name,
                    'publications' => (int) $row->publications,
                ];
            });

        $researchAreas = Insight::select('insight_type', DB::raw('COUNT(*) as studies'))
            ->groupBy('insight_type')
            ->orderByDesc('studies')
            ->take(5)
            ->get()
            ->map(function ($row) {
                $label = $row->insight_type ? ucwords(str_replace('_', ' ', $row->insight_type)) : 'Umum';
                return (object) [
                    'name' => $label,
                    'studies' => (int) $row->studies,
                ];
            });

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
