<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGardenRequest;
use App\Http\Requests\UpdateGardenRequest;
use App\Models\Garden;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminGardenController extends Controller
{
    public function index(Request $request)
    {
        $query = Garden::with('region');

        if ($request->filled('search')) {
            $query->where('kebun_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('regional_id')) {
            $query->where('regional_id', $request->regional_id);
        }

        if ($request->filled('kebun_type')) {
            $query->where('kebun_type', $request->kebun_type);
        }

        $gardens = $query->orderBy('kebun_name')->paginate(12)->withQueryString();
        $regions = Region::orderBy('regional_name')->get();

        return view('admin.gardens.index', compact('gardens', 'regions'));
    }

    public function create()
    {
        $regions = Region::orderBy('regional_name')->get();

        return view('admin.gardens.create', compact('regions'));
    }

    public function store(StoreGardenRequest $request)
    {
        $validated = $request->validated();

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

    public function update(UpdateGardenRequest $request, Garden $garden)
    {
        $validated = $request->validated();

        $garden->update($validated);

        if ($request->hasFile('photo')) {
            // Hapus foto lama dari storage
            if ($garden->photo_path) {
                Storage::disk('public')->delete($garden->photo_path);
            }

            $path = $request->file('photo')->store('garden-photos', 'public');
            $garden->photo_path = $path;
            $garden->save();
        }

        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil diperbarui.');
    }

    public function destroy(Garden $garden)
    {
        // Hapus foto utama dari storage
        if ($garden->photo_path) {
            Storage::disk('public')->delete($garden->photo_path);
        }

        $garden->delete(); // SoftDelete — cascade via GardenObserver

        return redirect()->route('admin.gardens.index')->with('success', 'Kebun berhasil dihapus.');
    }
}
