<?php

namespace App\Http\Controllers;

use App\Models\Garden;
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
        $region->load('gardens');
        return view('strategic.region', compact('region'));
    }

    public function garden(Region $region, Garden $garden)
    {
        $garden->load('region');

        // Get latest production data
        $latestProduction = $garden->productionData()->latest()->first();

        // Get average productivity
        $avgProductivity = $garden->productionData()->avg('productivity') ?? 0;

        // Get total production
        $totalProduction = $garden->productionData()->sum('production') ?? 0;

        // Get visit count
        $visitCount = $garden->visits()->count();

        // Get recent insights
        $insights = $garden->insights()->latest()->take(5)->get();

        return view('strategic.garden', compact(
            'garden',
            'latestProduction',
            'avgProductivity',
            'totalProduction',
            'visitCount',
            'insights'
        ));
    }
}
