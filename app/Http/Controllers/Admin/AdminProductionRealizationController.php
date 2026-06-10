<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionRealizationRequest;
use App\Http\Requests\UpdateProductionRealizationRequest;
use App\Models\Garden;
use App\Models\ProductionRealization;

class AdminProductionRealizationController extends Controller
{
    public function index()
    {
        $productions = ProductionRealization::with('garden.region')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(15);

        return view('admin.production_realizations.index', compact('productions'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.production_realizations.create', compact('gardens'));
    }

    public function store(StoreProductionRealizationRequest $request)
    {
        ProductionRealization::create($request->validated());

        return redirect()->route('admin.production-realizations.index')
            ->with('success', 'Data realisasi produksi berhasil ditambahkan.');
    }

    public function edit(ProductionRealization $productionRealization)
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.production_realizations.edit', compact('productionRealization', 'gardens'));
    }

    public function update(UpdateProductionRealizationRequest $request, ProductionRealization $productionRealization)
    {
        $productionRealization->update($request->validated());

        return redirect()->route('admin.production-realizations.index')
            ->with('success', 'Data realisasi produksi berhasil diperbarui.');
    }

    public function destroy(ProductionRealization $productionRealization)
    {
        $productionRealization->delete();

        return redirect()->route('admin.production-realizations.index')
            ->with('success', 'Data realisasi produksi berhasil dihapus.');
    }
}
