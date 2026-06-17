<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\CommunityService;
use App\Models\Insight;
use App\Models\Program;
use App\Models\StrategicAction;
use App\Models\Visit;

class ManajemenController extends Controller
{
    public function index()
    {
        // Statistik untuk Dashboard Manajemen
        $stats = [
            'programs'          => Program::count(),
            'strategic_actions' => StrategicAction::count(),
            'insights'          => Insight::count(),
            'visits'            => Visit::count(),
            'community_services'=> CommunityService::count(),
        ];

        $recentPrograms = Program::orderBy('year', 'desc')
            ->take(5)
            ->get();

        $recentStrategicActions = StrategicAction::with('garden')
            ->latest()
            ->take(5)
            ->get();

        $recentInsights = Insight::latest()
            ->take(5)
            ->get();

        $recentVisits = Visit::with('garden')
            ->latest('visit_date')
            ->take(5)
            ->get();

        $recentCommunityServices = CommunityService::latest()
            ->take(5)
            ->get();

        return view('manajemen.dashboard', compact(
            'stats',
            'recentPrograms',
            'recentStrategicActions',
            'recentInsights',
            'recentVisits',
            'recentCommunityServices'
        ));
    }
}
