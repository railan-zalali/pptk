<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\RegionPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminRegionController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        
        $query = Region::orderBy('regional_name');

        if ($request->filled('search')) {
             $query->where(function($q) use ($request) {
                $q->where('regional_name', 'like', '%' . $request->search . '%')
                  ->orWhere('province', 'like', '%' . $request->search . '%');
            });
        }

        $regions = $query->paginate(12);
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'regional_name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'coordinates' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $region = new Region($validated);
        $region->regional_code = Str::upper(Str::slug($request->regional_name));

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('region-photos', 'public');
            $region->photo_path = $path;
        }

        $region->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('region-photos', 'public');
                RegionPhoto::create([
                    'region_id' => $region->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil dibuat.');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {

        $validated = $request->validate([
            'regional_name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'coordinates' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $region->fill($validated);
        if ($region->isDirty('regional_name')) {
             $region->regional_code = Str::upper(Str::slug($request->regional_name));
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('region-photos', 'public');
            $region->photo_path = $path;
        }

        $region->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('region-photos', 'public');
                RegionPhoto::create([
                    'region_id' => $region->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil diperbarui.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil dihapus.');
    }
}
