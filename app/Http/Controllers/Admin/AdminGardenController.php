<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Region;
use App\Models\GardenPhoto;
use App\Support\ProvinceData;
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
        $regions   = Region::orderBy('regional_name')->get();
        $provinces = ProvinceData::all();
        return view('admin.gardens.create', compact('regions', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kebun_name'        => 'required|string|max:255',
            'regional_id'       => 'required|exists:regions,id',
            'luas_total_ha'     => 'required|numeric|min:0.01',
            'kebun_type'        => 'required|in:Model,Pengembangan',
            'agro_climate_note' => 'nullable|string|max:2000',
            'province'          => 'nullable|string|max:100',
            'location'          => 'nullable|string|max:255',
            'description'       => 'nullable|string|max:5000',
            'established_at'    => 'nullable|date|before_or_equal:today',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'coordinates'       => ['nullable', 'string', 'regex:/^-?\d{1,3}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$/'],
        ], [
            'luas_total_ha.min'      => 'Luas total harus lebih dari 0.',
            'established_at.before_or_equal' => 'Tanggal berdiri tidak boleh di masa depan.',
            'coordinates.regex'      => 'Format koordinat tidak valid. Gunakan format: lat, lng (contoh: -7.053, 107.641)',
            'photo.mimes'            => 'Format foto harus JPG, PNG, atau WebP.',
            'photo.max'              => 'Ukuran foto maksimal 4MB.',
        ]);

        // Gabung province + location jadi satu string
        if (!empty($validated['province']) && !empty($validated['location'])) {
            $validated['location'] = $validated['province'] . ', ' . $validated['location'];
        } elseif (!empty($validated['province'])) {
            $validated['location'] = $validated['province'];
        }
        unset($validated['province']);

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
        $regions   = Region::orderBy('regional_name')->get();
        $provinces = ProvinceData::all();
        return view('admin.gardens.edit', compact('garden', 'regions', 'provinces'));
    }

    public function update(Request $request, Garden $garden)
    {
        $validated = $request->validate([
            'kebun_name'        => 'required|string|max:255',
            'regional_id'       => 'required|exists:regions,id',
            'luas_total_ha'     => 'required|numeric|min:0.01',
            'kebun_type'        => 'required|in:Model,Pengembangan',
            'agro_climate_note' => 'nullable|string|max:2000',
            'province'          => 'nullable|string|max:100',
            'location'          => 'nullable|string|max:255',
            'description'       => 'nullable|string|max:5000',
            'established_at'    => 'nullable|date|before_or_equal:today',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'coordinates'       => ['nullable', 'string', 'regex:/^-?\d{1,3}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$/'],
        ], [
            'luas_total_ha.min'      => 'Luas total harus lebih dari 0.',
            'established_at.before_or_equal' => 'Tanggal berdiri tidak boleh di masa depan.',
            'coordinates.regex'      => 'Format koordinat tidak valid. Gunakan format: lat, lng (contoh: -7.053, 107.641)',
            'photo.mimes'            => 'Format foto harus JPG, PNG, atau WebP.',
            'photo.max'              => 'Ukuran foto maksimal 4MB.',
        ]);

        // Gabung province + location jadi satu string
        if (!empty($validated['province']) && !empty($validated['location'])) {
            $validated['location'] = $validated['province'] . ', ' . $validated['location'];
        } elseif (!empty($validated['province'])) {
            $validated['location'] = $validated['province'];
        }
        unset($validated['province']);

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
