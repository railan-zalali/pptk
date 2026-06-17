<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Visit;

class ManajemenVisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['garden.region'])
            ->orderBy('visit_date', 'desc')
            ->paginate(15);

        return view('manajemen.visits.index', compact('visits'));
    }

    public function show(Visit $visit)
    {
        $visit->load(['garden.region', 'photos']);
        return view('manajemen.visits.show', compact('visit'));
    }
}
