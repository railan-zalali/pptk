<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('month') && $request->has('year')) {
            $month = $request->month;
            $year = $request->year;
            
            $visits = Visit::with(['garden', 'garden.region'])
                ->whereMonth('visit_date', $month)
                ->whereYear('visit_date', $year)
                ->when($request->filled('region') && $request->region !== 'all', function ($query) use ($request) {
                    $query->whereHas('garden', function ($q) use ($request) {
                        $q->where('regional_id', $request->region);
                    });
                })
                ->get()
                ->map(function ($visit) {
                    return [
                        'id' => $visit->id,
                        'title' => $visit->title,
                        'date' => $visit->visit_date->format('Y-m-d'),
                        'visitor_name' => $visit->visitor_name,
                        'participants_list' => $visit->participants_list,
                        'location' => $visit->garden?->kebun_name ?? '-',
                        'status' => $visit->status,
                        'time' => '09:00 - 15:00', // Default time as it's not in DB
                        'description' => $visit->description,
                    ];
                });
                
            return response()->json($visits);
        }

        $visits = Visit::with(['garden', 'garden.region'])
            ->when(request('region'), function ($query, $regionId) {
                $query->whereHas('garden', function ($q) use ($regionId) {
                    $q->where('regional_id', $regionId);
                });
            })
            ->latest()
            ->paginate(10);

        $regions = \App\Models\Region::all();
        $totalVisits = Visit::count();
        $thisMonthVisits = Visit::whereMonth('visit_date', now()->month)->count();
        $visitedRegions = Visit::distinct()->count('garden_id');
        $avgRating = Visit::avg('rating') ?? 0;

        return view('visits.index', compact('visits', 'regions', 'totalVisits', 'thisMonthVisits', 'visitedRegions', 'avgRating'));
    }

    public function show(Visit $visit)
    {
        $visit->load(['garden.region', 'photos']);
        return view('visits.show', compact('visit'));
    }
}
