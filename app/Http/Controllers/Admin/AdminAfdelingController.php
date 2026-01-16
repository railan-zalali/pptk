<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Afdeling;
use App\Models\Garden;
use Illuminate\Http\Request;

class AdminAfdelingController extends Controller
{
    public function index(Request $request)
    {
        $query = Afdeling::with('garden');

        if ($request->filled('garden_id')) {
            $query->where('garden_id', $request->garden_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $afdelings = $query->paginate(10);
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.afdelings.index', compact('afdelings', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::all();
        return view('admin.afdelings.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'name' => 'required|string|max:255',
            'total_area_ha' => 'required|numeric|min:0',
            'tm_area_ha' => 'required|numeric|min:0|lte:total_area_ha',
            'manager_name' => 'nullable|string|max:255',
        ]);

        Afdeling::create($request->all());

        return redirect()->route('admin.afdelings.index')->with('success', 'Afdeling created successfully.');
    }

    public function edit(Afdeling $afdeling)
    {
        $gardens = Garden::all();
        return view('admin.afdelings.edit', compact('afdeling', 'gardens'));
    }

    public function update(Request $request, Afdeling $afdeling)
    {
        $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'name' => 'required|string|max:255',
            'total_area_ha' => 'required|numeric|min:0',
            'tm_area_ha' => 'required|numeric|min:0|lte:total_area_ha',
            'manager_name' => 'nullable|string|max:255',
        ]);

        $afdeling->update($request->all());

        return redirect()->route('admin.afdelings.index')->with('success', 'Afdeling updated successfully.');
    }

    public function destroy(Afdeling $afdeling)
    {
        $afdeling->delete();
        return redirect()->route('admin.afdelings.index')->with('success', 'Afdeling deleted successfully.');
    }
}
