<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAfdelingRequest;
use App\Http\Requests\UpdateAfdelingRequest;
use App\Models\Afdeling;
use App\Models\Garden;

class AdminAfdelingController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Afdeling::with('garden.region');

        if ($request->filled('kebun_id')) {
            $query->where('kebun_id', $request->kebun_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $afdelings = $query->orderBy('name')->paginate(15)->withQueryString();
        $gardens   = Garden::orderBy('kebun_name')->get();

        return view('admin.afdelings.index', compact('afdelings', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.afdelings.create', compact('gardens'));
    }

    public function store(StoreAfdelingRequest $request)
    {
        Afdeling::create($request->validated());

        return redirect()->route('admin.afdelings.index')->with('success', 'Afdeling berhasil dibuat.');
    }

    public function edit(Afdeling $afdeling)
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.afdelings.edit', compact('afdeling', 'gardens'));
    }

    public function update(UpdateAfdelingRequest $request, Afdeling $afdeling)
    {
        $afdeling->update($request->validated());

        return redirect()->route('admin.afdelings.index')->with('success', 'Afdeling berhasil diperbarui.');
    }

    public function destroy(Afdeling $afdeling)
    {
        $afdeling->delete();

        return redirect()->route('admin.afdelings.index')->with('success', 'Afdeling berhasil dihapus.');
    }
}
