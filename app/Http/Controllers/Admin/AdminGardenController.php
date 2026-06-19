<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Region;
use App\Models\GardenPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGardenController extends Controller
{
    private function ensureAdmin()
    {
        // Ideally handled by middleware, but keeping for consistency
        if (Auth::check() && !Auth::user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensureAdmin();
        $gardens = Garden::with('region')->orderBy('kebun_name')->paginate(12);
        return view('admin.gardens.index', compact('gardens'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $regions = Region::orderBy('regional_name')->get();
        return view('admin.gardens.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'kebun_name' => 'required|string|max:255',
            'regional_id' => 'required|exists:regions,id',
            'luas_total_ha' => 'required|numeric|min:0',
            'kebun_type' => 'required|in:Model,Pengembangan',
            'agro_climate_note' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'established_at' => 'nullable|date',
            'photo' => 'nullable|image|max:4096',
        ]);

        $garden = Garden::create($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('garden-photos', 'public');
            $garden->photo_path = $path;
            $garden->save();
        }

        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil dibuat.');
    }

    public function edit(Garden $garden)
    {
        $this->ensureAdmin();
        $regions = Region::orderBy('regional_name')->get();
        return view('admin.gardens.edit', compact('garden', 'regions'));
    }

    public function update(Request $request, Garden $garden)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'kebun_name' => 'required|string|max:255',
            'regional_id' => 'required|exists:regions,id',
            'luas_total_ha' => 'required|numeric|min:0',
            'kebun_type' => 'required|in:Model,Pengembangan',
            'agro_climate_note' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'established_at' => 'nullable|date',
            'photo' => 'nullable|image|max:4096',
        ]);

        $garden->update($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('garden-photos', 'public');
            $garden->photo_path = $path;
            $garden->save();
        }

        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil diperbarui.');
    }

    public function destroy(Garden $garden)
    {
        $this->ensureAdmin();
        $garden->delete();
        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil dihapus.');
    }
}
