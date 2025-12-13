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
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensureAdmin();
        $gardens = Garden::with('region')->orderBy('name')->paginate(12);
        return view('admin.gardens.index', compact('gardens'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $regions = Region::orderBy('name')->get();
        return view('admin.gardens.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
            'area_hectares' => 'required|numeric|min:0',
            'area' => 'nullable|numeric|min:0',
            'elevation' => 'nullable|integer',
            'rainfall' => 'nullable|integer',
            'tea_variety' => 'nullable|string|max:255',
            'garden_type' => 'nullable|string|max:255',
            'coordinates' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'soil_ph' => 'nullable|numeric',
            'soil_type' => 'nullable|string|max:255',
            'drainage' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'established_at' => 'nullable|date',
            'photo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $garden = Garden::create($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('garden-photos', 'public');
            $garden->photo_path = $path;
            $garden->save();
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('garden-photos', 'public');
                GardenPhoto::create([
                    'garden_id' => $garden->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil dibuat.');
    }

    public function edit(Garden $garden)
    {
        $this->ensureAdmin();
        $regions = Region::orderBy('name')->get();
        return view('admin.gardens.edit', compact('garden', 'regions'));
    }

    public function update(Request $request, Garden $garden)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
            'area_hectares' => 'required|numeric|min:0',
            'area' => 'nullable|numeric|min:0',
            'elevation' => 'nullable|integer',
            'rainfall' => 'nullable|integer',
            'tea_variety' => 'nullable|string|max:255',
            'garden_type' => 'nullable|string|max:255',
            'coordinates' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'soil_ph' => 'nullable|numeric',
            'soil_type' => 'nullable|string|max:255',
            'drainage' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'established_at' => 'nullable|date',
            'photo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $garden->update($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('garden-photos', 'public');
            $garden->photo_path = $path;
            $garden->save();
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('garden-photos', 'public');
                GardenPhoto::create([
                    'garden_id' => $garden->id,
                    'path' => $path,
                ]);
            }
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
