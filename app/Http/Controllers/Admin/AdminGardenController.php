<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Region;
use App\Models\GardenPhoto;
use Illuminate\Http\Request;

class AdminGardenController extends Controller
{
    public function index()
    {
        $gardens = Garden::with('region')->orderBy('kebun_name')->paginate(12);
        return view('admin.gardens.index', compact('gardens'));
    }

    public function create()
    {
        $regions = Region::orderBy('regional_name')->get();
        return view('admin.gardens.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kebun_name'       => 'required|string|max:255',
            'regional_id'      => 'required|exists:regions,id',
            'luas_total_ha'    => 'required|numeric|min:0',
            'kebun_type'       => 'required|in:Model,Pengembangan',
            'agro_climate_note'=> 'nullable|string',
            'location'         => 'nullable|string',
            'description'      => 'nullable|string',
            'established_at'   => 'nullable|date',
            'photo'            => 'nullable|image|max:4096',
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
        $regions = Region::orderBy('regional_name')->get();
        return view('admin.gardens.edit', compact('garden', 'regions'));
    }

    public function update(Request $request, Garden $garden)
    {
        $validated = $request->validate([
            'kebun_name'       => 'required|string|max:255',
            'regional_id'      => 'required|exists:regions,id',
            'luas_total_ha'    => 'required|numeric|min:0',
            'kebun_type'       => 'required|in:Model,Pengembangan',
            'agro_climate_note'=> 'nullable|string',
            'location'         => 'nullable|string',
            'description'      => 'nullable|string',
            'established_at'   => 'nullable|date',
            'photo'            => 'nullable|image|max:4096',
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
        $garden->delete();
        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil dihapus.');
    }
}

