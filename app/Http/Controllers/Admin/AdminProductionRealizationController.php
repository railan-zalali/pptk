<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Insight;
use App\Models\ProductionRealization;
use App\Services\InsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminProductionRealizationController extends Controller
{
    protected InsightService $insightService;

    public function __construct(InsightService $insightService)
    {
        $this->insightService = $insightService;
    }

    public function index()
    {
        $productions = ProductionRealization::with('garden')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
            
        return view('admin.production_realizations.index', compact('productions'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.production_realizations.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        Log::info('Storing Production Realization', $request->all());

        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('production_realizations')->where(function ($query) use ($request) {
                    return $query->where('kebun_id', $request->kebun_id)
                                 ->where('year', $request->year);
                })
            ],
            'year' => 'required|integer|min:2000|max:2099',
            'active_picking_area_ha' => 'nullable|numeric|min:0',
            'wet_production_kg' => 'nullable|numeric|min:0',
            'dry_production_kg' => 'nullable|numeric|min:0',
            'capacity_per_ha' => 'nullable|numeric|min:0',
            'avg_capacity' => 'nullable|numeric|min:0',
            'estimated_production' => 'nullable|numeric|min:0',
            'quality_score' => 'nullable|numeric|min:0|max:10',
            'assumption_note' => 'nullable|string',
        ], [
            'month.unique' => 'Data realisasi untuk kebun dan periode bulan/tahun ini sudah ada.',
        ]);

        $realization = ProductionRealization::create($validated);

        // ── Auto-generate insight via Rule Engine ──────────────────────────
        try {
            // Rule Basah: IF protas_basah < 1000 → High | 1000-1300 → Medium | ≥1300 → Low
            $this->insightService->generateProductivityInsight(
                $realization->kebun_id,
                $realization->year
            );
            // Rule Kering: IF protas_kering < 220 → High | 220-286 → Medium | ≥286 → Low
            $this->insightService->generateDryProductivityInsight(
                $realization->kebun_id,
                $realization->year
            );
            if (!empty($validated['quality_score'])) {
                $this->insightService->generateQualityInsight(
                    $realization->kebun_id,
                    $realization->year
                );
            }
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal generate insight setelah store: ' . $e->getMessage());
        }
        // ─────────────────────────────────────────────────────────────────

        return redirect()->route('admin.production-realizations.index')->with('success', 'Data Realisasi berhasil ditambahkan.');
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
            'dry_production_kg' => 'nullable|numeric|min:0',
            'capacity_per_ha' => 'nullable|numeric|min:0',
            'avg_capacity' => 'nullable|numeric|min:0',
            'estimated_production' => 'nullable|numeric|min:0',
            'quality_score' => 'nullable|numeric|min:0|max:10',
            'assumption_note' => 'nullable|string',
        ]);

        $productionRealization->update($validated);

        // ── Auto-generate insight via Rule Engine ──────────────────────────
        try {
            // Rule Basah: IF protas_basah < 1000 → High | 1000-1300 → Medium | ≥1300 → Low
            $this->insightService->generateProductivityInsight(
                $productionRealization->kebun_id,
                $productionRealization->year
            );
            // Rule Kering: IF protas_kering < 220 → High | 220-286 → Medium | ≥286 → Low
            $this->insightService->generateDryProductivityInsight(
                $productionRealization->kebun_id,
                $productionRealization->year
            );
            if (!empty($validated['quality_score'])) {
                $this->insightService->generateQualityInsight(
                    $productionRealization->kebun_id,
                    $productionRealization->year
                );
            }
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal generate insight setelah update: ' . $e->getMessage());
        }
        // ─────────────────────────────────────────────────────────────────

        return redirect()->route('admin.production-realizations.index')->with('success', 'Realization Data updated successfully.');
    }

    public function destroy(ProductionRealization $productionRealization)
    {
        $gardenId = $productionRealization->kebun_id;
        $year     = $productionRealization->year;

        $productionRealization->delete();

        // ── Refresh insight setelah delete ────────────────────────────────
        try {
            $remaining = ProductionRealization::where('kebun_id', $gardenId)
                ->where('year', $year)
                ->exists();

            if ($remaining) {
                // Masih ada data — regenerasi insight dari data terbaru
                $this->insightService->generateProductivityInsight($gardenId, $year);
                $this->insightService->generateDryProductivityInsight($gardenId, $year);
                $this->insightService->generateQualityInsight($gardenId, $year);
            } else {
                // Tidak ada data tersisa — hapus insight produksi agar tidak stale
                Insight::where('garden_id', $gardenId)
                    ->where('year', $year)
                    ->whereIn('insight_type', ['productivity', 'productivity_dry', 'quality'])
                    ->delete();
                Log::info("[InsightService] Insight produksi kebun #{$gardenId} tahun {$year} dihapus karena semua data realisasi dihapus.");
            }
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal refresh insight setelah destroy: ' . $e->getMessage());
        }
        // ─────────────────────────────────────────────────────────────────

        return redirect()->route('admin.production-realizations.index')->with('success', 'Realization Data deleted successfully.');
    }
}
