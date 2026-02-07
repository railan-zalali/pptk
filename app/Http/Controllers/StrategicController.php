<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Models\Region;
use Illuminate\Http\Request;

class StrategicController extends Controller
{
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
        $garden->load(['photos', 'strategicActions.program', 'productionRealizations', 'performanceTargets', 'visits']);

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

        return view('strategic.garden', compact('region', 'garden', 'latestProduction', 'avgProductivity', 'totalProduction', 'visitCount'));
    }
}
