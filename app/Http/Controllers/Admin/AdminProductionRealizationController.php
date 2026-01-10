<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Afdeling;
use App\Models\ProductionRealization;
use Illuminate\Http\Request;

class AdminProductionRealizationController extends Controller
{
    public function index()
    {
        $productions = ProductionRealization::with('afdeling.garden')->orderBy('date', 'desc')->paginate(10);
        return view('admin.production_realizations.index', compact('productions'));
    }

    public function create()
    {
        $afdelings = Afdeling::with('garden')->get();
        return view('admin.production_realizations.create', compact('afdelings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'afdeling_id' => 'required|exists:afdelings,id',
            'date' => 'required|date',
            'harvested_area_ha' => 'required|numeric|min:0',
            'wet_yield_kg' => 'required|numeric|min:0',
            'dry_yield_kg' => 'required|numeric|min:0',
            'manpower_count' => 'required|integer|min:0',
            'effective_days' => 'required|integer|min:0',
        ]);

        ProductionRealization::create($request->all());

        return redirect()->route('admin.production-realizations.index')->with('success', 'Production Data created successfully.');
    }

    public function edit(ProductionRealization $productionRealization)
    {
        $afdelings = Afdeling::with('garden')->get();
        return view('admin.production_realizations.edit', compact('productionRealization', 'afdelings'));
    }

    public function update(Request $request, ProductionRealization $productionRealization)
    {
        $request->validate([
            'afdeling_id' => 'required|exists:afdelings,id',
            'date' => 'required|date',
            'harvested_area_ha' => 'required|numeric|min:0',
            'wet_yield_kg' => 'required|numeric|min:0',
            'dry_yield_kg' => 'required|numeric|min:0',
            'manpower_count' => 'required|integer|min:0',
            'effective_days' => 'required|integer|min:0',
        ]);

        $productionRealization->update($request->all());

        return redirect()->route('admin.production-realizations.index')->with('success', 'Production Data updated successfully.');
    }

    public function destroy(ProductionRealization $productionRealization)
    {
        $productionRealization->delete();
        return redirect()->route('admin.production-realizations.index')->with('success', 'Production Data deleted successfully.');
    }
}
