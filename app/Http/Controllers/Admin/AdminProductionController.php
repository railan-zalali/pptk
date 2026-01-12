<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\ProductionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProductionController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();
        $gardens = Garden::orderBy('kebun_name')->get();
        $query = ProductionData::with('garden.region')->orderByDesc('record_date');

        if ($request->filled('garden_id')) {
            $query->where('garden_id', $request->get('garden_id'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('record_date', $request->get('month'));
        }
        if ($request->filled('year')) {
            $query->whereYear('record_date', $request->get('year'));
        }

        $records = $query->paginate(15);

        return view('admin.production.index', compact('records', 'gardens'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $gardens = Garden::orderBy('name')->get();
        return view('admin.production.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'record_date' => 'required|date',
            'production' => 'nullable|numeric',
            'productivity' => 'nullable|numeric',
            'rkap_percentage' => 'nullable|numeric',
            'wet_production_kg' => 'nullable|numeric',
            'quality_score' => 'nullable|numeric',
            'weather_condition' => 'nullable|string',
            'temperature_avg' => 'nullable|integer',
            'rainfall_mm' => 'nullable|integer',
            'humidity_percent' => 'nullable|integer',
            'soil_moisture_percent' => 'nullable|integer',
            'pest_incidence' => 'nullable|integer',
            'disease_incidence' => 'nullable|integer',
            'fertilizer_used' => 'nullable|integer',
            'labor_hours' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $validated['month'] = \Carbon\Carbon::parse($validated['record_date'])->format('F');
        $validated['year'] = \Carbon\Carbon::parse($validated['record_date'])->format('Y');

        ProductionData::create($validated);

        return redirect()->route('admin.production.index')->with('success', 'Data produksi berhasil ditambahkan.');
    }

    public function edit(ProductionData $production)
    {
        $this->ensureAdmin();
        $gardens = Garden::orderBy('name')->get();
        return view('admin.production.edit', compact('production', 'gardens'));
    }

    public function update(Request $request, ProductionData $production)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'record_date' => 'required|date',
            'production' => 'nullable|numeric',
            'productivity' => 'nullable|numeric',
            'rkap_percentage' => 'nullable|numeric',
            'wet_production_kg' => 'nullable|numeric',
            'quality_score' => 'nullable|numeric',
            'weather_condition' => 'nullable|string',
            'temperature_avg' => 'nullable|integer',
            'rainfall_mm' => 'nullable|integer',
            'humidity_percent' => 'nullable|integer',
            'soil_moisture_percent' => 'nullable|integer',
            'pest_incidence' => 'nullable|integer',
            'disease_incidence' => 'nullable|integer',
            'fertilizer_used' => 'nullable|integer',
            'labor_hours' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $validated['month'] = \Carbon\Carbon::parse($validated['record_date'])->format('F');
        $validated['year'] = \Carbon\Carbon::parse($validated['record_date'])->format('Y');

        $production->update($validated);

        return redirect()->route('admin.production.index')->with('success', 'Data produksi berhasil diperbarui.');
    }

    public function destroy(ProductionData $production)
    {
        $this->ensureAdmin();
        $production->delete();
        return redirect()->route('admin.production.index')->with('success', 'Data produksi berhasil dihapus.');
    }
}

