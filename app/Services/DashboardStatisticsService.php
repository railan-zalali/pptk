<?php

namespace App\Services;

use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    /**
     * Calculate Year-to-Date Production (Wet Production in Kg)
     */
    public function getProductionYtd(int $year, ?int $gardenId = null): float
    {
        $query = ProductionRealization::where('year', $year);
        
        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        return $query->sum('wet_production_kg');
    }

    /**
     * Calculate Prorated Target YTD
     */
    public function getTargetYtdProrated(int $year, ?int $gardenId = null): float
    {
        $gardensQuery = Garden::with(['performanceTargets' => function ($q) use ($year) {
            $q->where('year', $year);
        }]);

        if ($gardenId) {
            $gardensQuery->where('id', $gardenId);
        }

        $gardens = $gardensQuery->get();
        $totalTargetYearly = 0;

        // Eager load production realizations for area calculation to avoid N+1 in loop
        $gardenIds = $gardens->pluck('id');
        $gardenAreas = ProductionRealization::whereIn('kebun_id', $gardenIds)
            ->where('year', $year)
            ->select('kebun_id', DB::raw('AVG(active_picking_area_ha) as avg_area'))
            ->groupBy('kebun_id')
            ->pluck('avg_area', 'kebun_id');

        foreach ($gardens as $garden) {
            $targetProtas = $garden->performanceTargets->first()->target_protas_min ?? 0;
            
            // Use calculated avg area or fallback to total area
            $avgArea = $gardenAreas[$garden->id] ?? $garden->luas_total_ha;

            $totalTargetYearly += $targetProtas * $avgArea;
        }

        $currentYear = now()->year;
        if ($year < $currentYear) {
            $monthProgress = 1.0;
        } elseif ($year == $currentYear) {
            $monthProgress = now()->month / 12;
        } else {
            $monthProgress = 0.0;
        }
        return $totalTargetYearly * $monthProgress;
    }

    /**
     * Calculate Average Productivity (Kg/Ha)
     */
    public function getAvgProductivity(int $year, ?int $gardenId = null): float
    {
        $query = ProductionRealization::where('year', $year);

        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        // Get total production
        $totalProduction = (clone $query)->sum('wet_production_kg');

        // Get average total area across months
        // Logic: For a single garden, it's Avg(Area). For all gardens, it's Avg(Sum(Area per month)).
        $avgTotalArea = 0;

        if ($gardenId) {
            $avgTotalArea = (clone $query)->avg('active_picking_area_ha') ?? 0;
        } else {
            $avgTotalArea = (clone $query)
                ->selectRaw('month, sum(active_picking_area_ha) as total_area')
                ->groupBy('month')
                ->get()
                ->avg('total_area') ?? 0;
        }

        return $avgTotalArea > 0 ? $totalProduction / $avgTotalArea : 0;
    }

    /**
     * Get Picking Capacity
     */
    public function getPickingCapacity(int $year, ?int $gardenId = null): float
    {
        $query = ProductionRealization::where('year', $year);
        
        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        return $query->avg('avg_capacity') ?? 0;
    }

    /**
     * Get Quality Score
     */
    public function getQualityScore(int $year, ?int $gardenId = null): float
    {
        $query = ProductionRealization::where('year', $year)->whereNotNull('quality_score');
        
        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        return $query->avg('quality_score') ?? 0;
    }

    /**
     * Calculate Strategic Progress
     * Now using 'realization_percent' if available, otherwise 0
     */
    public function getStrategicProgress(string $type, int $year, ?int $gardenId = null): array
    {
        $query = StrategicAction::where('year', $year)
            ->where('action_type', $type);

        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        $actions = $query->with('garden')->get();

        $totalTargetArea = 0;
        $totalRealizedArea = 0;

        foreach ($actions as $action) {
            $gardenArea = $action->garden->luas_total_ha ?? 0;
            
            // Target
            $targetPercent = $action->coverage_target_percent ?? 0;
            $targetArea = $gardenArea * ($targetPercent / 100);
            $totalTargetArea += $targetArea;

            // Realization
            // Assuming we will add 'realization_percent' to the model
            $realizationPercent = $action->realization_percent ?? 0;
            $realizedArea = $gardenArea * ($realizationPercent / 100);
            $totalRealizedArea += $realizedArea;
        }

        $progressPercent = $totalTargetArea > 0 ? ($totalRealizedArea / $totalTargetArea) * 100 : 0;

        return [
            'target_area' => $totalTargetArea,
            'realized_area' => $totalRealizedArea,
            'progress_percent' => $progressPercent,
            'target_value' => $actions->avg('coverage_target_percent') ?? 0, // Avg Target %
            'realized_value' => $actions->avg('realization_percent') ?? 0 // Avg Realization %
        ];
    }

    /**
     * Get Monthly Production Chart Data
     */
    public function getMonthlyProduction(int $year, ?int $gardenId = null): Collection
    {
        $query = ProductionRealization::selectRaw('month, SUM(wet_production_kg) as total')
            ->where('year', $year);

        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        return $query->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    }

    /**
     * Get Monthly Dry Production Chart Data (dry_production_kg)
     */
    public function getMonthlyDryProduction(int $year, ?int $gardenId = null): Collection
    {
        $query = ProductionRealization::selectRaw('month, SUM(dry_production_kg) as total')
            ->where('year', $year);

        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        return $query->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    }

    /**
     * Get Monthly Productivity Chart Data
     */
    public function getMonthlyProductivity(int $year, ?int $gardenId = null): Collection
    {
        $query = ProductionRealization::selectRaw('month, SUM(wet_production_kg) as total_prod, SUM(active_picking_area_ha) as total_area')
            ->where('year', $year);

        if ($gardenId) {
            $query->where('kebun_id', $gardenId);
        }

        return $query->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->total_area > 0 ? $item->total_prod / $item->total_area : 0];
            });
    }

    /**
     * Get Garden Details with Calculations
     */
    public function getGardenDetails(int $year): Collection
    {
        // Optimized Eager Loading
        $gardens = Garden::with(['region', 'performanceTargets' => function ($q) use ($year) {
            $q->where('year', $year);
        }])->get();

        // Pre-fetch production data grouped by garden
        $productionData = ProductionRealization::where('year', $year)
            ->selectRaw('kebun_id, SUM(wet_production_kg) as total_prod, AVG(active_picking_area_ha) as avg_area')
            ->groupBy('kebun_id')
            ->get()
            ->keyBy('kebun_id');

        $currentYear = now()->year;
        if ($year < $currentYear) {
            $monthProgress = 1.0;
        } elseif ($year == $currentYear) {
            $monthProgress = now()->month / 12;
        } else {
            $monthProgress = 0.0;
        }

        return $gardens->map(function ($garden) use ($productionData, $year, $monthProgress) {
            $prod = $productionData[$garden->id] ?? null;
            $realization = $prod ? $prod->total_prod : 0;
            $avgArea = $prod ? $prod->avg_area : $garden->luas_total_ha;
            
            $protas = $avgArea > 0 ? $realization / $avgArea : 0;
            $target = $garden->performanceTargets->first()->target_protas_min ?? 0;
            
            $targetYtd = $target * $monthProgress;
            $achievement = $targetYtd > 0 ? ($protas / $targetYtd) * 100 : 0;

            return [
                'id' => $garden->id,
                'name' => $garden->kebun_name,
                'region' => $garden->region->regional_name ?? 'N/A',
                'regional_id' => $garden->region->id ?? null,
                'tm_area' => $avgArea,
                'production_realization' => $realization,
                'protas_achievement' => $protas,
                'target_protas' => $target,
                'achievement_percent' => $achievement
            ];
        })->sortByDesc('protas_achievement')->values();
    }

    /**
     * Get Regional Comparison
     */
    public function getRegionalComparison(Collection $gardenDetails): Collection
    {
        return $gardenDetails->groupBy('region')->map(function ($gardens, $region) {
            return [
                'region' => $region,
                'avg_protas' => $gardens->avg('protas_achievement'),
                'garden_count' => $gardens->count()
            ];
        })->sortByDesc('avg_protas')->values();
    }
}
