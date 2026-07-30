<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatisticsService;
use App\Models\StrategicAction;
use Illuminate\Http\Request;

class StrategicDashboardController extends Controller
{
    protected $statsService;

    public function __construct(DashboardStatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function index(Request $request)
    {
        $latestYearWithData = \App\Models\ProductionRealization::max('year') ?? \App\Models\StrategicAction::max('year');
        $currentYear = (int)$request->get('year', $latestYearWithData ?? now()->year);

        // 1. Header Scorecard Data
        $productionYtd = $this->statsService->getProductionYtd($currentYear);
        $targetYtdProrated = $this->statsService->getTargetYtdProrated($currentYear);
        $avgProductivity = $this->statsService->getAvgProductivity($currentYear);
        $pickingCapacity = $this->statsService->getPickingCapacity($currentYear);
        $qualityScore = $this->statsService->getQualityScore($currentYear);

        // Strategic Progress (Using Real Data from Service)
        $cultivatorData = $this->statsService->getStrategicProgress('cultivator', $currentYear);
        $cultivatorTarget = $cultivatorData['target_area'];
        $cultivatorRealization = $cultivatorData['realized_area'];
        $cultivatorProgress = $cultivatorData['progress_percent'];

        $fertilizerData = $this->statsService->getStrategicProgress('fertilizer_leaf', $currentYear);
        $fertilizerProgress = $fertilizerData['progress_percent'];

        $weedData = $this->statsService->getStrategicProgress('weed_control', $currentYear);
        $weedControlProgress = $weedData['progress_percent'];

        // Fertilizer Root specific logic (Dosis & Protas)
        $fertilizerRootActions = StrategicAction::where('year', $currentYear)
            ->where('action_type', 'fertilizer_root')
            ->get();
            
        $fertilizerProtas = $fertilizerRootActions->avg('n_protas_percent') ?? 0;
        $protasProgress = $fertilizerProtas;
        $fertilizerRealization = $fertilizerRootActions->avg('realized_dosis_n_kg_ha') ?? 0; // Changed to realized_dosis

        // 2. Charts Data
        $monthlyProduction = $this->statsService->getMonthlyProduction($currentYear);
        $monthlyDryProduction = $this->statsService->getMonthlyDryProduction($currentYear);
        $monthlyProductivity = $this->statsService->getMonthlyProductivity($currentYear);

        // 3. Table Detail Kebun
        $gardenDetails = $this->statsService->getGardenDetails($currentYear);

        // Best and Underperforming
        $bestPerformer = $gardenDetails->first();
        $underPerformer = $gardenDetails->last();

        // Regional Comparison
        $regionalComparison = $this->statsService->getRegionalComparison($gardenDetails);

        // 3. Additional Charts Data
        $regionalChartData = $regionalComparison; // Reuse the collection

        // Machine Age vs Quantity
        $machineActions = StrategicAction::where('year', $currentYear)
            ->where('action_type', 'machine')
            ->with('garden')
            ->get();

        $machineChartData = $machineActions->map(function ($action) {
            return [
                'garden' => $action->garden->kebun_name,
                'avg_age' => $action->avg_machine_age,
                'total' => $action->total_machine
            ];
        });

        // Fertilizer Dosage Comparison
        $fertilizerActions = StrategicAction::where('year', $currentYear)
            ->where('action_type', 'fertilizer_root')
            ->with('garden')
            ->get();

        $fertilizerChartData = $fertilizerActions->map(function ($action) {
            return [
                'garden' => $action->garden->kebun_name,
                'dosage' => $action->realized_dosis_n_kg_ha ?? $action->dosis_n_kg_ha // Use realized if available
            ];
        });

        return view('dashboard.garden', compact(
            'currentYear',
            'productionYtd',
            'targetYtdProrated',
            'avgProductivity',
            'pickingCapacity',
            'qualityScore',
            'cultivatorProgress',
            'cultivatorTarget',
            'cultivatorRealization',
            'fertilizerProgress',
            'fertilizerProtas',
            'fertilizerRealization',
            'protasProgress',
            'weedControlProgress',
            'monthlyProduction',
            'monthlyDryProduction',
            'monthlyProductivity',
            'gardenDetails',
            'bestPerformer',
            'underPerformer',
            'regionalComparison',
            'regionalChartData',
            'machineChartData',
            'fertilizerChartData'
        ));
    }
}
