<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\StrategicAction;
use App\Services\ResearchAnalysisService;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $researchAnalysisService;
    protected $statsService;

    public function __construct(
        ResearchAnalysisService $researchAnalysisService,
        DashboardStatisticsService $statsService
    )
    {
        $this->researchAnalysisService = $researchAnalysisService;
        $this->statsService = $statsService;
    }

    /**
     * Redirect user ke dashboard sesuai role.
     */
    public function user()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role;

        if ($role === 'admin_ppkt') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'manajemen') {
            return redirect()->route('manajemen.dashboard');
        }

        // Fallback jika role tidak dikenal
        return view('dashboard');
    }

    public function research(Request $request)
    {
        $data = $this->researchAnalysisService->getResearchPageData($request);
        return view('dashboard.research', $data);
    }
}
