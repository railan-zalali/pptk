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
        $garden->load(['photos', 'strategicActions.program', 'productionRealizations', 'performanceTargets']);

        // dd($garden);

        return view('strategic.garden', compact('region', 'garden'));
    }
}
