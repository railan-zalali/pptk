<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Region;
use App\Models\ProductionData;
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

        $stats = [
            'gardens' => Garden::count(),
            'regions' => Region::count(),
            'visits' => Visit::count(),
            'insights' => Insight::count(),
            'production_total' => ProductionData::sum('production') ?? 0,
            'productivity_avg' => ProductionData::avg('productivity') ?? 0,
        ];

        $recentVisits = Visit::with(['garden', 'garden.region'])->latest()->take(5)->get();
        $topGardens = Garden::with(['region', 'productionData'])
            ->get()
            ->sortByDesc(fn($g) => $g->productionData->avg('productivity') ?? 0)
            ->take(5);

        return view('admin.dashboard', compact('stats', 'recentVisits', 'topGardens'));
    }
}

