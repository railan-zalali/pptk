<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatisticsService;
use App\Models\StrategicAction;
use Illuminate\Http\Request;

class StrategicDashboardController extends Controller
{
    public function __construct(
        protected DashboardStatisticsService $statsService,
    ) {}

    public function index(Request $request)
    {
        $currentYear    = now()->year;
        $selectedGardenId = $request->integer('garden_id') ?: null;

        // 1. Header Scorecard (semua dengan caching)
        $productionYtd      = $this->statsService->getProductionYtd($currentYear, $selectedGardenId);
        $targetYtdProrated  = $this->statsService->getTargetYtdProrated($currentYear, $selectedGardenId);
        $avgProductivity    = $this->statsService->getAvgProductivity($currentYear, $selectedGardenId);
        $pickingCapacity    = $this->statsService->getPickingCapacity($currentYear, $selectedGardenId);
        $qualityScore       = $this->statsService->getQualityScore($currentYear, $selectedGardenId);

        // 2. Strategic Progress
        $cultivatorData       = $this->statsService->getStrategicProgress('cultivator', $currentYear, $selectedGardenId);
        $cultivatorTarget     = $cultivatorData['target_area'];
        $cultivatorRealization = $cultivatorData['realized_area'];
        $cultivatorProgress   = $cultivatorData['progress_percent'];

        $fertilizerLeafData   = $this->statsService->getStrategicProgress('fertilizer_leaf', $currentYear, $selectedGardenId);
        $fertilizerProgress   = $fertilizerLeafData['progress_percent'];

        $weedData             = $this->statsService->getStrategicProgress('weed_control', $currentYear, $selectedGardenId);
        $weedControlProgress  = $weedData['progress_percent'];

        // 3. Data Pemupukan Akar (terpusat di service)
        $fertRootData         = $this->statsService->getFertilizerRootData($currentYear, $selectedGardenId);
        $fertilizerProtas     = $fertRootData['avg_n_protas_percent'];
        $fertilizerRealization = $fertRootData['avg_realized_dosis'];
        $protasProgress       = $fertilizerProtas;

        // 4. Charts
        $monthlyProduction  = $this->statsService->getMonthlyProduction($currentYear, $selectedGardenId);
        $monthlyProductivity = $this->statsService->getMonthlyProductivity($currentYear, $selectedGardenId);

        // 5. Tabel Detail Kebun (selalu semua kebun untuk perbandingan)
        $gardenDetails      = $this->statsService->getGardenDetails($currentYear);
        $bestPerformer      = $gardenDetails->first();
        $underPerformer     = $gardenDetails->last();
        $regionalComparison = $this->statsService->getRegionalComparison($gardenDetails);
        $regionalChartData  = $regionalComparison;

        // 6. Chart Mesin Petik
        $machineChartData = StrategicAction::where('year', $currentYear)
            ->where('action_type', 'machine')
            ->with('garden')
            ->get()
            ->map(fn ($action) => [
                'garden'  => $action->garden?->kebun_name ?? '-',
                'avg_age' => (float) $action->avg_machine_age,
                'total'   => (int) $action->total_machine,
            ]);

        // 7. Chart Dosis Pupuk Akar
        $fertilizerChartData = StrategicAction::where('year', $currentYear)
            ->where('action_type', 'fertilizer_root')
            ->with('garden')
            ->get()
            ->map(fn ($action) => [
                'garden' => $action->garden?->kebun_name ?? '-',
                'dosage' => (float) ($action->realized_dosis_n_kg_ha ?? $action->dosis_n_kg_ha ?? 0),
            ]);

        return view('dashboard.garden', compact(
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
