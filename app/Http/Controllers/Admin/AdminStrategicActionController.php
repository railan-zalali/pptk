<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Insight;
use App\Models\StrategicAction;
use App\Services\InsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminStrategicActionController extends Controller
{
    protected InsightService $insightService;

    public function __construct(InsightService $insightService)
    {
        $this->insightService = $insightService;
    }

    public function index()
    {
        $actions = StrategicAction::with(['garden', 'program'])->orderBy('year', 'desc')->paginate(10);
        return view('admin.strategic_actions.index', compact('actions'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.strategic_actions.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'year' => 'required|integer|min:2000|max:2099',
            'action_type' => 'required|string',
            'status' => 'nullable|string|in:planned,in_progress,completed',
            'realization_date' => 'nullable|date',

            'dosis_n_kg_ha' => 'nullable|numeric',
            'realized_dosis_n_kg_ha' => 'nullable|numeric',
            'n_protas_percent' => 'nullable|numeric',
            'application_frequency' => 'nullable|integer',
            'fertilizer_type' => 'nullable|string',
            'technical_note' => 'nullable|string',
            'coverage_target_percent' => 'nullable|numeric',
            'realization_percent' => 'nullable|numeric|min:0|max:100',
            'application_interval' => 'nullable|string',
            'rotation_per_year' => 'nullable|integer',
            'method' => 'nullable|string',
            'focus_area' => 'nullable|string',
            'picking_system' => 'nullable|string',
            'cushion_consistency' => 'nullable|string',
            'kandas_risk' => 'nullable|boolean',
            'total_machine' => 'nullable|integer',
            'avg_machine_age' => 'nullable|numeric',
            'renewal_status' => 'nullable|string',
            'opt_status' => 'nullable|string',
            'tp_normalization' => 'nullable|boolean',
            'treatment_note' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $action = StrategicAction::create($validated);

        // ── Auto-generate insight via Rule Engine ──────────────────────────
        try {
            $this->insightService->generateStrategicInsight($action);
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal generate strategic insight setelah store: ' . $e->getMessage());
        }
        // ─────────────────────────────────────────────────────────────────

        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action recorded successfully.');
    }

    public function edit(StrategicAction $strategicAction)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.strategic_actions.edit', compact('strategicAction', 'gardens'));
    }

    public function update(Request $request, StrategicAction $strategicAction)
    {
        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'year' => 'required|integer|min:2000|max:2099',
            'action_type' => 'required|string',
            'status' => 'nullable|string|in:planned,in_progress,completed',
            'realization_date' => 'nullable|date',

            'dosis_n_kg_ha' => 'nullable|numeric',
            'realized_dosis_n_kg_ha' => 'nullable|numeric',
            'n_protas_percent' => 'nullable|numeric',
            'application_frequency' => 'nullable|integer',
            'fertilizer_type' => 'nullable|string',
            'technical_note' => 'nullable|string',
            'coverage_target_percent' => 'nullable|numeric',
            'realization_percent' => 'nullable|numeric|min:0|max:100',
            'application_interval' => 'nullable|string',
            'rotation_per_year' => 'nullable|integer',
            'method' => 'nullable|string',
            'focus_area' => 'nullable|string',
            'picking_system' => 'nullable|string',
            'cushion_consistency' => 'nullable|string',
            'kandas_risk' => 'nullable|boolean',
            'total_machine' => 'nullable|integer',
            'avg_machine_age' => 'nullable|numeric',
            'renewal_status' => 'nullable|string',
            'opt_status' => 'nullable|string',
            'tp_normalization' => 'nullable|boolean',
            'treatment_note' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $strategicAction->update($validated);
        $strategicAction->refresh();

        // ── Auto-generate insight via Rule Engine ──────────────────────────
        try {
            $this->insightService->generateStrategicInsight($strategicAction);
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal generate strategic insight setelah update: ' . $e->getMessage());
        }
        // ─────────────────────────────────────────────────────────────────

        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action updated successfully.');
    }

    public function destroy(StrategicAction $strategicAction)
    {
        $gardenId   = $strategicAction->kebun_id;
        $insightKey = 'strategic_' . $strategicAction->action_type;

        $strategicAction->delete();

        // ── Hapus insight spesifik action yang dihapus ───────────────────────
        try {
            Insight::where('garden_id', $gardenId)
                ->where('insight_type', $insightKey)
                ->delete();
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal hapus insight setelah destroy strategic action: ' . $e->getMessage());
        }
        // ───────────────────────────────────────────────────────

        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action deleted successfully.');
    }
}
