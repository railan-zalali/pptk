<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\StrategicAction;
use App\Services\InsightService;
use App\Services\ResearchAnalysisService;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $insightService;
    protected $researchAnalysisService;
    protected $statsService;

    public function __construct(
        InsightService $insightService,
        ResearchAnalysisService $researchAnalysisService,
        DashboardStatisticsService $statsService
    )
    {
        $this->insightService = $insightService;
        $this->researchAnalysisService = $researchAnalysisService;
        $this->statsService = $statsService;
    }

    /**
     * Redirect user ke dashboard sesuai role.
     */
    public function user()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role;

        if ($role === 'admin_ppkt') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'manajemen') {
            return redirect()->route('manajemen.dashboard');
        }

        // Fallback jika role tidak dikenal
        return view('dashboard');
    }

    public function garden(Request $request)
    {
        $currentYear = date('Y');
        $gardens = Garden::with(['region'])->get();
        $selectedGardenId = $request->get('garden_id') ? (int)$request->get('garden_id') : null;
        $selectedGarden = $selectedGardenId ? Garden::find($selectedGardenId) : null;

        // Basic Stats using Service
        $productionYtd = $this->statsService->getProductionYtd($currentYear, $selectedGardenId);
        $targetYtdProrated = $this->statsService->getTargetYtdProrated($currentYear, $selectedGardenId);
        $avgProductivity = $this->statsService->getAvgProductivity($currentYear, $selectedGardenId);
        $pickingCapacity = $this->statsService->getPickingCapacity($currentYear, $selectedGardenId);
        $qualityScore = $this->statsService->getQualityScore($currentYear, $selectedGardenId);

        // Strategic Progress
        $cultivatorData = $this->statsService->getStrategicProgress('cultivator', $currentYear, $selectedGardenId);
        $cultivatorProgress = $cultivatorData['progress_percent'];
        $cultivatorRealization = $cultivatorData['realized_area'];
        $cultivatorTarget = $cultivatorData['target_area'];

        $weedData = $this->statsService->getStrategicProgress('weed_control', $currentYear, $selectedGardenId);
        $weedControlProgress = $weedData['progress_percent'];

        // Fertilizer logic
        $fertilizerActions = StrategicAction::where('year', $currentYear)
            ->where('action_type', 'fertilizer_root');
        if ($selectedGardenId) {
            $fertilizerActions->where('kebun_id', $selectedGardenId);
        }
        $fertilizerActions = $fertilizerActions->get();

        $fertilizerProtas = $fertilizerActions->avg('n_protas_percent') ?? 0;
        $fertilizerRealization = $fertilizerActions->avg('realized_dosis_n_kg_ha') ?? 0;
        $fertilizerProgress = $fertilizerProtas;
        $protasProgress = $fertilizerProtas;

        // Charts
        $monthlyProduction = $this->statsService->getMonthlyProduction($currentYear, $selectedGardenId);
        $monthlyProductivity = $this->statsService->getMonthlyProductivity($currentYear, $selectedGardenId);

        // Performance Analysis (Table Details)
        $gardenDetails = $this->statsService->getGardenDetails($currentYear);

        // Regional Comparison
        $regionalComparison = $this->statsService->getRegionalComparison($gardenDetails);
        $regionalChartData = $regionalComparison;

        // Best/Under Performer
        $bestPerformer = $gardenDetails->first();
        $underPerformer = $gardenDetails->last();

        $machineChartData = collect([]);
        $fertilizerChartData = collect([]);

        return view('dashboard.garden', compact(
            'gardens', 'selectedGarden',
            'productionYtd', 'targetYtdProrated', 'avgProductivity', 'pickingCapacity', 'qualityScore',
            'cultivatorProgress', 'cultivatorRealization', 'cultivatorTarget',
            'fertilizerProgress', 'fertilizerRealization', 'fertilizerProtas',
            'weedControlProgress', 'protasProgress',
            'monthlyProduction', 'monthlyProductivity',
            'regionalChartData', 'machineChartData', 'fertilizerChartData',
            'gardenDetails', 'regionalComparison',
            'bestPerformer', 'underPerformer'
        ));
    }

    public function research(Request $request)
    {
        $data = $this->researchAnalysisService->getResearchPageData($request);
        return view('dashboard.research', $data);
    }
}
