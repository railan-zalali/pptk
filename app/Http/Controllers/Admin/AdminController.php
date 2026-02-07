<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Region;
use App\Models\ProductionRealization;
use App\Models\Visit;
use App\Models\Insight;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensureAdmin();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Calculate aggregates from ProductionRealization (New System)
        $totalWetProduction = ProductionRealization::sum('wet_production_kg') ?? 0;

        // Calculate average productivity (Protas Basah)
        $totalArea = ProductionRealization::sum('active_picking_area_ha') ?? 0;
        $avgProductivity = $totalArea > 0 ? $totalWetProduction / $totalArea : 0;

        // Data Completeness Logic
        $totalGardens = Garden::count();
        $missingTargetGardens = Garden::whereDoesntHave('performanceTargets', function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        })->get();

        $missingRealizationGardens = Garden::whereDoesntHave('productionRealizations', function ($q) use ($currentMonth, $currentYear) {
            $q->where('month', $currentMonth)->where('year', $currentYear);
        })->get();

        $gardensWithTarget = $totalGardens - $missingTargetGardens->count();
        $gardensWithRealization = $totalGardens - $missingRealizationGardens->count();

        $targetScore = $totalGardens > 0 ? ($gardensWithTarget / $totalGardens) * 50 : 0;
        $realizationScore = $totalGardens > 0 ? ($gardensWithRealization / $totalGardens) * 50 : 0;
        $completenessScore = $targetScore + $realizationScore;

        $stats = [
            'gardens' => $totalGardens,
            'regions' => Region::count(),
            'visits' => Visit::count(),
            'insights' => Insight::count(),
            'production_total' => $totalWetProduction,
            'productivity_avg' => $avgProductivity,
        ];

        $recentVisits = Visit::with(['garden.region'])->latest('visit_date')->take(5)->get();

        // Top Gardens based on Realization (Average Productivity)
        $topGardens = Garden::with(['productionRealizations' => function ($q) use ($currentYear) {
            $q->where('year', $currentYear);
        }, 'region'])
            ->get()
            ->map(function ($garden) {
                $totalWet = $garden->productionRealizations->sum('wet_production_kg');
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
            'recentVisits',
            'topGardens'
        ));
    }
}
