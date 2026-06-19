<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVisitController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        $query = Visit::with(['garden.region'])->orderByDesc('visit_date');
        if ($request->filled('garden_id')) {
            $query->where('garden_id', $request->get('garden_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        $visits = $query->paginate(15);
        return view('admin.visits.index', compact('visits', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.visits.create', compact('gardens'));
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
        $validated['visitor_name'] = Auth::user()->name ?? 'Admin';
        $validated['purpose'] = $validated['description'] ?? 'Kunjungan dinas';
        $visit = Visit::create($validated);
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photo->store('visit-photos/' . $visit->id, 'public');
            }
        }
        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan berhasil ditambahkan.');
    }

    public function edit(Visit $visit)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.visits.edit', compact('visit', 'gardens'));
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
        $validated['visitor_name'] = $visit->visitor_name ?? (Auth::user()->name ?? 'Admin');
        $validated['purpose'] = $validated['description'] ?? $visit->purpose ?? 'Kunjungan dinas';
        $visit->update($validated);
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photo->store('visit-photos/' . $visit->id, 'public');
            }
        }
        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan berhasil diperbarui.');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan berhasil dihapus.');
    }
}
