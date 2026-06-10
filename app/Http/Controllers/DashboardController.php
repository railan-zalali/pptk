<?php

namespace App\Http\Controllers;

use App\Services\InsightService;
use App\Services\ResearchAnalysisService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected ResearchAnalysisService $researchAnalysisService,
        protected InsightService $insightService,
    ) {}

    /**
     * Dashboard user yang login — tampilkan insight terbaru.
     */
    public function user()
    {
        $recentInsights = $this->insightService->getInsightsForDashboard(5);

        return view('dashboard', compact('recentInsights'));
    }

    /**
     * Dashboard Penelitian.
     */
    public function research(Request $request)
    {
        $data = $this->researchAnalysisService->getResearchPageData($request);

        return view('dashboard.research', $data);
    }
}
