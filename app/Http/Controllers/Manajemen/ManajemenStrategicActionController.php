<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\StrategicAction;
use App\Models\Garden;

class ManajemenStrategicActionController extends Controller
{
    public function index()
    {
        $strategicActions = StrategicAction::with(['garden.region'])
            ->orderBy('year', 'desc')
            ->paginate(15);

        $gardens = Garden::orderBy('kebun_name')->get();

        return view('manajemen.strategic_actions.index', compact('strategicActions', 'gardens'));
    }

    public function show(StrategicAction $strategicAction)
    {
        $strategicAction->load('garden.region');
        return view('manajemen.strategic_actions.show', compact('strategicAction'));
    }
}
