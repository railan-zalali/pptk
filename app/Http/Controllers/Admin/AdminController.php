<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Afdeling;
use App\Models\Block;
use App\Models\Garden;
use App\Models\PerformanceTarget;
use App\Models\ProductionRealization;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Statistik Master Kebun
        $totalRegions  = Region::count();
        $totalGardens  = Garden::count();
        $totalAfdelings = Afdeling::count();
        $totalBlocks   = Block::count();

        // Data Produksi & Realisasi
        $totalWetProduction = ProductionRealization::sum('wet_production_kg') ?? 0;
        $totalDryProduction = ProductionRealization::sum('dry_production_kg') ?? 0;
        $totalArea          = ProductionRealization::sum('active_picking_area_ha') ?? 0;
        $avgProductivity    = $totalArea > 0 ? $totalWetProduction / $totalArea : 0;

        // Target Kinerja - total tahun ini
        $totalTargets = PerformanceTarget::where('year', $currentYear)->count();

        // Data Completeness Logic
        $missingTargetGardens = Garden::whereDoesntHave('performanceTargets', function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        })->get();

        $missingRealizationGardens = Garden::whereDoesntHave('productionRealizations', function ($q) use ($currentMonth, $currentYear) {
            $q->where('month', $currentMonth)->where('year', $currentYear);
        })->get();

        $gardensWithTarget      = $totalGardens - $missingTargetGardens->count();
        $gardensWithRealization = $totalGardens - $missingRealizationGardens->count();

        $targetScore       = $totalGardens > 0 ? ($gardensWithTarget / $totalGardens) * 50 : 0;
        $realizationScore  = $totalGardens > 0 ? ($gardensWithRealization / $totalGardens) * 50 : 0;
        $completenessScore = $targetScore + $realizationScore;

        $stats = [
            'regions'            => $totalRegions,
            'gardens'            => $totalGardens,
            'afdelings'          => $totalAfdelings,
            'blocks'             => $totalBlocks,
            'production_total'   => $totalWetProduction,
            'dry_production_total' => $totalDryProduction,
            'productivity_avg'   => $avgProductivity,
            'targets'            => $totalTargets,
        ];

        // Recent realizations
        $recentRealizations = ProductionRealization::with('garden')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(5)
            ->get();

        // Top Gardens based on productivity
        $topGardens = Garden::with(['productionRealizations' => function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        }, 'region'])
            ->get()
            ->map(function ($garden) {
                $totalWet  = $garden->productionRealizations->sum('wet_production_kg');
                $totalArea = $garden->productionRealizations->sum('active_picking_area_ha');
                $productivity = $totalArea > 0 ? $totalWet / $totalArea : 0;
                $garden->calculated_productivity = $productivity;
                return $garden;
            })
            ->sortByDesc('calculated_productivity')
            ->take(5);

        return view('admin.dashboard', compact(
            'stats',
            'completenessScore',
            'missingTargetGardens',
            'missingRealizationGardens',
            'recentRealizations',
            'topGardens'
        ));
    }
}
