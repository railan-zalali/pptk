<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\Region;
use App\Models\RegionPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminRegionController extends Controller
{
    public function index(Request $request)
    {
        $query = Region::withCount('gardens');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('regional_name', 'like', '%' . $request->search . '%')
                  ->orWhere('province', 'like', '%' . $request->search . '%');
            });
        }

        $regions = $query->orderBy('regional_name')->paginate(12)->withQueryString();

        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(StoreRegionRequest $request)
    {
        $validated = $request->validated();

        $region = new Region($validated);
        $region->regional_code = $this->generateRegionalCode($validated['regional_name']);

        if ($request->hasFile('photo')) {
            $region->photo_path = $request->file('photo')->store('region-photos', 'public');
        }

        $region->save();

        $this->storeAdditionalPhotos($request, $region);

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil dibuat.');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(UpdateRegionRequest $request, Region $region)
    {
        $validated = $request->validated();

        $region->fill($validated);

        if ($region->isDirty('regional_name')) {
            $region->regional_code = $this->generateRegionalCode($validated['regional_name']);
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($region->photo_path) {
                Storage::disk('public')->delete($region->photo_path);
            }
            $region->photo_path = $request->file('photo')->store('region-photos', 'public');
        }

        $region->save();

        $this->storeAdditionalPhotos($request, $region);

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil diperbarui.');
    }

    public function destroy(Region $region)
    {
        if ($region->photo_path) {
            Storage::disk('public')->delete($region->photo_path);
        }

        $region->delete(); // SoftDelete — cascade via RegionObserver

        return redirect()->route('admin.regions.index')->with('success', 'Wilayah berhasil dihapus.');
    }

    private function generateRegionalCode(string $name): string
    {
        return Str::upper(Str::slug($name, '_'));
    }

    private function storeAdditionalPhotos(Request $request, Region $region): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        foreach ($request->file('photos') as $file) {
            $path = $file->store('region-photos', 'public');
            RegionPhoto::create(['region_id' => $region->id, 'path' => $path]);
        }
    }
}
