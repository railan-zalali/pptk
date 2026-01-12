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
        $garden->load(['region.photos', 'photos', 'strategicActions' => function($q) {
            $q->where('year', now()->year);
        }, 'performanceTargets' => function($q) {
            $q->where('year', now()->year);
        }]);

        // Get latest production data
        $latestRealization = $garden->productionRealizations()->where('year', now()->year)->latest('month')->first();

        // Get average productivity (YTD)
        $totalWet = $garden->productionRealizations()->where('year', now()->year)->sum('wet_production_kg');
        $avgArea = $garden->productionRealizations()->where('year', now()->year)->avg('active_picking_area_ha');
        $avgProductivity = $avgArea > 0 ? $totalWet / $avgArea : 0;

        // Get total production
        $totalProduction = $totalWet;

        // Get visit count
        $visitCount = $garden->visits()->count();

        // Get recent insights
        $insights = $garden->insights()->latest()->take(5)->get();

        return view('strategic.garden', compact(
            'garden',
            'latestRealization',
            'avgProductivity',
            'totalProduction',
            'visitCount',
            'insights'
        ));
    }
}
