<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Insight;
use Illuminate\Http\Request;

class ManajemenInsightController extends Controller
{
    public function index(Request $request)
    {
        $alertFilter = $request->get('alert');

        $insights = Insight::with(['garden.region'])
            ->when($alertFilter, fn($q) => $q->where('alert_level', $alertFilter))
            ->orderByRaw("CASE alert_level WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderBy('generated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $counts = Insight::selectRaw("alert_level, count(*) as total")
            ->groupBy('alert_level')
            ->pluck('total', 'alert_level');

        return view('manajemen.insights.index', compact('insights', 'counts', 'alertFilter'));
    }

    public function show(Insight $insight)
    {
        $insight->load(['garden.region']);
        return view('manajemen.insights.show', compact('insight'));
    }
}
