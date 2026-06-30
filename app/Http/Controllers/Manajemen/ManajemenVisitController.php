<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Visit;
use App\Models\VisitPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManajemenVisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['garden.region'])
            ->orderBy('visit_date', 'desc')
            ->paginate(15);

        $gardens = Garden::orderBy('kebun_name')->get();

        return view('manajemen.visits.index', compact('visits', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('manajemen.visits.create', compact('gardens'));
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
        $validated['visitor_name'] = Auth::user()->name ?? 'Manajemen';
        $validated['purpose'] = $validated['description'] ?? 'Kunjungan dinas';
        $visit = Visit::create($validated);
        
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('visit-photos/' . $visit->id, 'public');
                VisitPhoto::create([
                    'visit_id' => $visit->id,
                    'path'     => $path,
                    'caption'  => $photo->getClientOriginalName(),
                ]);
            }
        }
        return redirect()->route('manajemen.visits.index')->with('success', 'Kunjungan berhasil ditambahkan.');
    }

    public function show(Visit $visit)
    {
        $visit->load(['garden.region', 'photos']);
        return view('manajemen.visits.show', compact('visit'));
    }

    public function edit(Visit $visit)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('manajemen.visits.edit', compact('visit', 'gardens'));
    }

    public function update(Request $request, Visit $visit)
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
        $validated['visitor_name'] = $visit->visitor_name ?? (Auth::user()->name ?? 'Manajemen');
        $validated['purpose'] = $validated['description'] ?? $visit->purpose ?? 'Kunjungan dinas';
        $visit->update($validated);
        
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('visit-photos/' . $visit->id, 'public');
                VisitPhoto::create([
                    'visit_id' => $visit->id,
                    'path'     => $path,
                    'caption'  => $photo->getClientOriginalName(),
                ]);
            }
        }
        return redirect()->route('manajemen.visits.index')->with('success', 'Kunjungan berhasil diperbarui.');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        return redirect()->route('manajemen.visits.index')->with('success', 'Kunjungan berhasil dihapus.');
    }
}
