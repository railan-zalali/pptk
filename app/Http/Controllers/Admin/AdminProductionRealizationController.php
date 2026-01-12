<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\ProductionRealization;
use Illuminate\Http\Request;

class AdminProductionRealizationController extends Controller
{
    public function index()
    {
        $productions = ProductionRealization::with('garden')->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(10);
        return view('admin.production_realizations.index', compact('productions'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.production_realizations.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2099',
            'active_picking_area_ha' => 'nullable|numeric|min:0',
            'wet_production_kg' => 'nullable|numeric|min:0',
            'capacity_per_ha' => 'nullable|numeric|min:0',
            'avg_capacity' => 'nullable|numeric|min:0',
            'estimated_production' => 'nullable|numeric|min:0',
            'assumption_note' => 'nullable|string',
        ]);

        ProductionRealization::create($validated);

        return redirect()->route('admin.production-realizations.index')->with('success', 'Realization Data created successfully.');
    }

    public function edit(ProductionRealization $productionRealization)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.production_realizations.edit', compact('productionRealization', 'gardens'));
    }

    public function update(Request $request, ProductionRealization $productionRealization)
    {
        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2099',
            'active_picking_area_ha' => 'nullable|numeric|min:0',
            'wet_production_kg' => 'nullable|numeric|min:0',
            'capacity_per_ha' => 'nullable|numeric|min:0',
            'avg_capacity' => 'nullable|numeric|min:0',
            'estimated_production' => 'nullable|numeric|min:0',
            'assumption_note' => 'nullable|string',
        ]);

        $productionRealization->update($validated);

        return redirect()->route('admin.production-realizations.index')->with('success', 'Realization Data updated successfully.');
    }

    public function destroy(ProductionRealization $productionRealization)
    {
        $productionRealization->delete();
        return redirect()->route('admin.production-realizations.index')->with('success', 'Realization Data deleted successfully.');
    }
}
