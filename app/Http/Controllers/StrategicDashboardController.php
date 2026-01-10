<?php

namespace App\Http\Controllers;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategicDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Header Scorecard Data
        $yearStart = now()->startOfYear();
        $monthStart = now()->startOfMonth();

        // Total Production YTD
        $productionYtd = ProductionRealization::where('date', '>=', $yearStart)->sum('wet_yield_kg');
        
        // Target Production YTD (Simplified assumption: aggregating from performance targets if available, or just placeholder)
        // Since PerformanceTarget is per Afdeling per Year, we can sum it up.
        // Assuming target_yield_kg is annual target.
        $targetYtd = Afdeling::with(['performanceTargets' => function($q) {
            $q->where('year', now()->year);
        }])->get()->sum(function($afdeling) {
            return $afdeling->performanceTargets->sum('target_yield_kg');
        });
        // Adjust target to YTD (simple pro-rate)
        $monthProgress = now()->month / 12;
        $targetYtdProrated = $targetYtd * $monthProgress;

        // Avg Productivity (Kg/Ha)
        // Formula: Total Production / Total Harvested Area (averaged or summed? Usually sum prod / sum area)
        $totalProd = ProductionRealization::where('date', '>=', $yearStart)->sum('wet_yield_kg');
        $totalAreaHarvested = ProductionRealization::where('date', '>=', $yearStart)->sum('harvested_area_ha');
        $avgProductivity = $totalAreaHarvested > 0 ? $totalProd / $totalAreaHarvested : 0;

        // Picking Capacity (Kg/HK)
        $totalManpower = ProductionRealization::where('date', '>=', $yearStart)->sum('manpower_count');
        $pickingCapacity = $totalManpower > 0 ? $totalProd / $totalManpower : 0;

        // Cultivator Progress (%)
        // Target vs Realization for 'cultivator' action type
        $cultivatorActions = StrategicAction::where('action_type', 'cultivator')
            ->where('period', '>=', $yearStart)
            ->get();
        $cultivatorTarget = $cultivatorActions->sum('target_volume');
        $cultivatorRealization = $cultivatorActions->sum('realization_volume');
        $cultivatorProgress = $cultivatorTarget > 0 ? ($cultivatorRealization / $cultivatorTarget) * 100 : 0;

        // 2. Charts Data
        
        // Bar Chart: Production vs Target per Month
        $monthlyProduction = ProductionRealization::selectRaw('MONTH(date) as month, SUM(wet_yield_kg) as total')
            ->whereYear('date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
        
        // Line Chart: Productivity Trend per Month
        $monthlyProductivity = ProductionRealization::selectRaw('MONTH(date) as month, SUM(wet_yield_kg) as total_prod, SUM(harvested_area_ha) as total_area')
            ->whereYear('date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->month => $item->total_area > 0 ? $item->total_prod / $item->total_area : 0];
            });

        // 3. Table Detail Afdeling
        $afdelings = Afdeling::with(['productionRealizations' => function($q) use ($yearStart) {
            $q->where('date', '>=', $yearStart);
        }, 'blocks', 'performanceTargets' => function($q) {
            $q->where('year', now()->year);
        }])->get()->map(function($afdeling) {
            $realization = $afdeling->productionRealizations->sum('wet_yield_kg');
            $area = $afdeling->tm_area_ha; // Use TM Area for Protas calculation base usually, or harvested area. 
            // Using TM Area as per "Luas TM" column requirement.
            $protas = $area > 0 ? $realization / $area : 0;
            
            // Determine dominant block class
            $blockClasses = $afdeling->blocks->pluck('initial_class')->countBy()->sortDesc()->keys()->first() ?? '-';

            return [
                'name' => $afdeling->name,
                'tm_area' => $afdeling->tm_area_ha,
                'block_class' => $blockClasses,
                'production_realization' => $realization,
                'protas_achievement' => $protas,
            ];
        });

        // 4. Widgets Data
        // Fertilizer Progress
        $fertilizerActions = StrategicAction::whereIn('action_type', ['fertilizer_root', 'fertilizer_leaf'])
            ->where('period', '>=', $yearStart)
            ->get();
        $fertilizerTarget = $fertilizerActions->sum('target_volume');
        $fertilizerRealization = $fertilizerActions->sum('realization_volume');
        $fertilizerProgress = $fertilizerTarget > 0 ? ($fertilizerRealization / $fertilizerTarget) * 100 : 0;

        return view('dashboard.garden', compact(
            'productionYtd',
            'targetYtdProrated',
            'avgProductivity',
            'pickingCapacity',
            'cultivatorProgress',
            'monthlyProduction',
            'monthlyProductivity',
            'afdelings',
            'fertilizerProgress',
            'cultivatorRealization',
            'cultivatorTarget'
        ));
    }
}
