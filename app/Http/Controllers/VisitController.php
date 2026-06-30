<?php

namespace App\Http\Controllers;

use App\Models\Garden;
use App\Models\Visit;
use App\Models\VisitPhoto;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('month') && $request->has('year')) {
            $month = $request->month;
            $year = $request->year;
            
            $visits = Visit::with(['garden.region', 'photos'])
                ->whereMonth('visit_date', $month)
                ->whereYear('visit_date', $year)
                ->get()
                ->map(function ($visit) {
                    $photos = $visit->photos->map(function ($photo) {
                        return [
                            'url' => asset('storage/' . $photo->path),
                            'caption' => $photo->caption,
                        ];
                    })->values();

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
                        'photos' => $photos,
                        'photo_url' => $photos->first()['url'] ?? null,
                    ];
                });
                
            return response()->json($visits);
        }

        $visits = Visit::with(['garden', 'garden.region'])
            ->when(request('region'), function ($query, $regionId) {
                $query->whereHas('garden', function ($q) use ($regionId) {
                    $q->where('regional_id', $regionId); // kolom di tabel gardens adalah 'regional_id'
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

    public function create()
    {
        $gardens = Garden::all();
        return view('visits.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'garden_id' => 'required|exists:gardens,id',
            'visit_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:24',
            'participants_count' => 'required|integer|min:1',
            'participants_list' => 'nullable|string',
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'rating' => 'required|integer|between:1,5',
            'status' => 'required|in:scheduled,completed,cancelled',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
        ]);

        $validated['visitor_name'] = auth()->user()->name ?? 'Petugas';
        $validated['purpose'] = $validated['description'] ?? 'Kunjungan dinas';

        $visit = Visit::create($validated);

        // Handle photo uploads if provided
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('visit-photos/' . $visit->id, 'public');
                VisitPhoto::create([
                    'visit_id' => $visit->id,
                    'path' => $path,
                    'caption' => $photo->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('visits.index')->with('success', 'Kunjungan berhasil disimpan.');
    }

    public function show(Visit $visit)
    {
        $visit->load(['garden.region', 'photos']);
        return view('visits.show', compact('visit'));
    }

    public function edit(Visit $visit)
    {
        $this->authorize('update', $visit);

        $gardens = Garden::all();
        return view('visits.edit', compact('visit', 'gardens'));
    }

    public function update(Request $request, Visit $visit)
    {
        $this->authorize('update', $visit);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'garden_id' => 'required|exists:gardens,id',
            'visit_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:24',
            'participants_count' => 'required|integer|min:1',
            'participants_list' => 'nullable|string',
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'rating' => 'required|integer|between:1,5',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $visit->update($validated);

        return redirect()->route('visits.index')->with('success', 'Kunjungan berhasil diperbarui.');
    }

    public function destroy(Visit $visit)
    {
        $this->authorize('delete', $visit);
        $visit->delete();
        return redirect()->route('visits.index')->with('success', 'Kunjungan berhasil dihapus.');
    }
}
