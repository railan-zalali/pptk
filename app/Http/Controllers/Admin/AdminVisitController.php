<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\Garden;
use App\Models\Visit;
use App\Models\VisitPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminVisitController extends Controller
{
    public function index(Request $request)
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        $query = Visit::with(['garden.region'])->orderByDesc('visit_date');

        if ($request->filled('garden_id')) {
            $query->where('garden_id', $request->garden_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $visits = $query->paginate(15)->withQueryString();

        return view('admin.visits.index', compact('visits', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.visits.create', compact('gardens'));
    }

    public function store(StoreVisitRequest $request)
    {
        $validated = $request->validated();
        $validated['visitor_name'] = Auth::user()->name;
        $validated['purpose']      = $validated['description'];

        $visit = Visit::create($validated);

        $this->storePhotos($request, $visit);

        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan berhasil ditambahkan.');
    }

    public function edit(Visit $visit)
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.visits.edit', compact('visit', 'gardens'));
    }

    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        $validated = $request->validated();
        $validated['purpose'] = $validated['description'];

        $visit->update($validated);

        $this->storePhotos($request, $visit);

        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan berhasil diperbarui.');
    }

    public function destroy(Visit $visit)
    {
        // Hapus semua foto dari storage
        foreach ($visit->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $visit->delete();

        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan berhasil dihapus.');
    }

    private function storePhotos(Request $request, Visit $visit): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('visit-photos/' . $visit->id, 'public');
            VisitPhoto::create([
                'visit_id' => $visit->id,
                'path'     => $path,
                'caption'  => $photo->getClientOriginalName(),
            ]);
        }
    }
}
