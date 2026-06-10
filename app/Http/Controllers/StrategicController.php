<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\Region;

class StrategicController extends Controller
{
    public function index()
    {
        $regions = Region::with(['gardens', 'photos'])->orderBy('regional_name')->get();
        return view('strategic.index', compact('regions'));
    }

    public function region(Region $region)
    {
        $region->load(['gardens.photos', 'photos']);
        return view('strategic.region', compact('region'));
    }

    public function garden(Region $region, Garden $garden)
    {
        abort_unless($garden->regional_id === $region->id, 404);

        $garden->load([
            'region.photos',
            'photos',
            'strategicActions.program',
            'productionRealizations',
            'performanceTargets',
            'visits',
            'insights',
        ]);

        $currentYear = now()->year;
        
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
        $insights = $garden->insights->sortByDesc('generated_at')->take(5);

        return view('strategic.garden', compact(
            'region',
            'garden',
            'latestProduction',
            'avgProductivity',
            'totalProduction',
            'visitCount',
            'insights'
        ));
    }
}
