<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRegionController extends Controller
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
        $regions = Region::orderBy('name')->paginate(12);
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'coordinates' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
        ]);

        $region = new Region($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('region-photos', 'public');
            $region->photo_path = $path;
        }

        $region->save();

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil dibuat.');
    }

    public function edit(Region $region)
    {
        $this->ensureAdmin();
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'coordinates' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
        ]);

        $region->fill($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('region-photos', 'public');
            $region->photo_path = $path;
        }

        $region->save();

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil diperbarui.');
    }

    public function destroy(Region $region)
    {
        $this->ensureAdmin();
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil dihapus.');
    }
}
