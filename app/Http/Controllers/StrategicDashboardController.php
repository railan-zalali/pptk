<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Models\PerformanceTarget;
use App\Models\StrategicAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategicDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Header Scorecard Data
        $currentYear = now()->year;

        // Total Production YTD (Wet Production)
        $productionYtd = ProductionRealization::where('year', $currentYear)->sum('wet_production_kg');

        // Target Production YTD
        // Calculate based on PerformanceTarget (Kg/Ha) * Active Area (Ha)
        // Since active area varies by month, we can approximate:
        // Sum of (Target Protas Min / 12 * Active Area) for each month recorded?
        // Or simplified: Total Target Protas * Avg Area * (Months passed / 12)

        $gardens = Garden::with(['performanceTargets' => function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        }])->get();

        $totalTargetYearly = 0;
        foreach ($gardens as $garden) {
            $targetProtas = $garden->performanceTargets->first()->target_protas_min ?? 0; // Min target as baseline
            // Get average area for this garden in current year
            $avgArea = ProductionRealization::where('kebun_id', $garden->id)
                ->where('year', $currentYear)
                ->avg('active_picking_area_ha');

            // If no realization yet, use luas_total_ha from garden
            if (!$avgArea) {
                $avgArea = $garden->luas_total_ha;
            }

            $totalTargetYearly += $targetProtas * $avgArea;
        }

        $monthProgress = now()->month / 12;
        $targetYtdProrated = $totalTargetYearly * $monthProgress;

        // Avg Productivity (Kg/Ha) - Wet
        $totalAreaSum = ProductionRealization::where('year', $currentYear)->sum('active_picking_area_ha');
        $avgProductivity = $totalAreaSum > 0 ? $productionYtd / $totalAreaSum : 0;
        // Note: The above is "Avg Monthly Productivity". For Yearly YTD Productivity, it's Total Prod / Avg Area.
        // Let's use Total Prod / Avg Total Area of all gardens.
        $avgTotalArea = ProductionRealization::where('year', $currentYear)
            ->selectRaw('month, sum(active_picking_area_ha) as total_area')
            ->groupBy('month')
            ->get()
            ->avg('total_area');

        if ($avgTotalArea > 0) {
            $avgProductivity = $productionYtd / $avgTotalArea; // This is YTD Productivity
        } else {
            $avgProductivity = 0;
        }

        // Picking Capacity (Avg Capacity from Realization)
        $pickingCapacity = ProductionRealization::where('year', $currentYear)->avg('avg_capacity') ?? 0;

        // Helper to get target coverage area
        $calculateTargetArea = function ($type) use ($currentYear) {
            $actions = StrategicAction::where('year', $currentYear)
                ->where('action_type', $type)
                ->with('garden')
                ->get();

            $totalTargetArea = 0;
            foreach ($actions as $action) {
                $gardenArea = $action->garden->luas_total_ha ?? 0;
                $targetPercent = $action->coverage_target_percent ?? 0;
                $totalTargetArea += $gardenArea * ($targetPercent / 100);
            }
            return $totalTargetArea;
        };

        // Cultivator Progress (%)
        $cultivatorTarget = $calculateTargetArea('cultivator');
        // Simulate realization: (Month / 12) * Target * random factor (0.8-1.0)
        // This is a placeholder logic since we don't have a 'RealizationAction' table yet.
        $simulatedProgress = min((now()->month / 12), 1.0);
        $cultivatorRealization = $cultivatorTarget * $simulatedProgress * 0.9;
        $cultivatorProgress = $cultivatorTarget > 0 ? ($cultivatorRealization / $cultivatorTarget) * 100 : 0;

        // Fertilizer Progress (Using Leaf Fertilizer as proxy)
        $fertilizerTarget = $calculateTargetArea('fertilizer_leaf');
        $fertilizerRealization = $fertilizerTarget * $simulatedProgress * 0.95;
        $fertilizerProgress = $fertilizerTarget > 0 ? ($fertilizerRealization / $fertilizerTarget) * 100 : 0;

        // Weed Control Progress
        $weedTarget = $calculateTargetArea('weed_control');
        $weedRealization = $weedTarget * $simulatedProgress * 0.98;
        $weedControlProgress = $weedTarget > 0 ? ($weedRealization / $weedTarget) * 100 : 0;

        // 2. Charts Data

        // Bar Chart: Production vs Target per Month
        // We need to aggregate across all gardens
        $monthlyProduction = ProductionRealization::selectRaw('month, SUM(wet_production_kg) as total')
            ->where('year', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Line Chart: Productivity Trend per Month
        // We calculate avg productivity per month across all gardens
        // Avg Prod = Sum(Wet Production) / Sum(Active Area)
        $monthlyProductivity = ProductionRealization::selectRaw('month, SUM(wet_production_kg) as total_prod, SUM(active_picking_area_ha) as total_area')
            ->where('year', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->total_area > 0 ? $item->total_prod / $item->total_area : 0];
            });

        // 3. Table Detail Kebun
        $gardenDetails = Garden::with(['productionRealizations' => function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        }, 'performanceTargets' => function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        }, 'region'])->get()->map(function ($garden) {
            $realization = $garden->productionRealizations->sum('wet_production_kg');
            $avgArea = $garden->productionRealizations->avg('active_picking_area_ha') ?? $garden->luas_total_ha;

            $protas = $avgArea > 0 ? $realization / $avgArea : 0;

            $target = $garden->performanceTargets->first()->target_protas_min ?? 0;
            // Target YTD = Target / 12 * Months Passed
            $targetYtd = $target * (now()->month / 12);
            $achievement = $targetYtd > 0 ? ($protas / $targetYtd) * 100 : 0;

            return [
                'name' => $garden->kebun_name,
                'region' => $garden->region->regional_name ?? 'N/A',
                'tm_area' => $avgArea,
                'production_realization' => $realization,
                'protas_achievement' => $protas,
                'target_protas' => $target,
                'achievement_percent' => $achievement
            ];
        })->sortByDesc('protas_achievement');

        // Best and Underperforming
        $bestPerformer = $gardenDetails->first();
        $underPerformer = $gardenDetails->last();

        // Regional Comparison
        $regionalComparison = $gardenDetails->groupBy('region')->map(function ($gardens, $region) {
            $avgProtas = $gardens->avg('protas_achievement');
            return [
                'region' => $region,
                'avg_protas' => $avgProtas,
                'garden_count' => $gardens->count()
            ];
        })->sortByDesc('avg_protas');

        return view('dashboard.garden', compact(
            'productionYtd',
            'targetYtdProrated',
            'avgProductivity',
            'pickingCapacity',
            'cultivatorProgress',
            'cultivatorTarget',
            'cultivatorRealization',
            'fertilizerProgress',
            'weedControlProgress',
            'monthlyProduction',
            'monthlyProductivity',
            'gardenDetails',
            'bestPerformer',
            'underPerformer',
            'regionalComparison'
        ));
    }
}
