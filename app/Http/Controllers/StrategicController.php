<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Models\Region;
use App\Services\InsightService;
use Illuminate\Http\Request;

class StrategicController extends Controller
{
    public function __construct(protected InsightService $insightService) {}

    public function index()
    {
        $regions = Region::with('gardens')->get();
        return view('strategic.index', compact('regions'));
    }

    public function region(Region $region)
    {
        $region->load('gardens.photos');
        return view('strategic.region', compact('region'));
    }

    public function garden(Region $region, Garden $garden)
    {
        // Cari tahun terbaru yang ada data realisasi (fallback ke tahun berjalan)
        $latestDataYear = $garden->productionRealizations()
            ->max('year') ?? now()->year;
        $currentYear = $latestDataYear;

        // Auto-refresh insight berdasarkan data terkini (silent fail — tidak crash halaman publik)
        try {
            $this->insightService->generateAllInsightsForGarden($garden->id, $currentYear);
        } catch (\Throwable) {
            // Insight gagal di-generate — halaman tetap tampil dengan data lama
        }

        $garden->load(['photos', 'strategicActions.program', 'productionRealizations', 'performanceTargets', 'visits', 'insights']);

        // Filter realizations for current year
        $realizations = $garden->productionRealizations->where('year', $currentYear);

        $totalProduction = $realizations->sum('wet_production_kg');

        // Latest production (by month)
        $latestProduction = $realizations->sortByDesc('month')->first();

        // Avg Productivity (Total Production / Avg Area)
        $avgArea = $realizations->avg('active_picking_area_ha') ?? $garden->luas_total_ha;
        $avgProductivity = $avgArea > 0 ? $totalProduction / $avgArea : 0;

        // Visit Count
        $visitCount = $garden->visits->count();

        $insights = $garden->insights()->orderByDesc('generated_at')->get();

        return view('strategic.garden', compact('region', 'garden', 'latestProduction', 'avgProductivity', 'totalProduction', 'visitCount', 'insights'));
    }
}

