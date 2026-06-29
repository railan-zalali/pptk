<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\CommunityService;
// Insight model dipertahankan (data tetap ada, menu disembunyikan)
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

        // $recentInsights tidak ditampilkan di dashboard (Insight disembunyikan dari menu)

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
            'recentVisits',
            'recentCommunityServices'
        ));
    }
}
