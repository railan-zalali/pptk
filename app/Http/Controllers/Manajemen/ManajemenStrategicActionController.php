<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\StrategicAction;
use App\Services\InsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManajemenStrategicActionController extends Controller
{
    protected InsightService $insightService;

    public function __construct(InsightService $insightService)
    {
        $this->insightService = $insightService;
    }

    public function index()
    {
        $strategicActions = StrategicAction::with(['garden.region'])
            ->orderBy('year', 'desc')
            ->paginate(15);

        $gardens = Garden::orderBy('kebun_name')->get();

        return view('manajemen.strategic_actions.index', compact('strategicActions', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('manajemen.strategic_actions.create', compact('gardens'));
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

        try {
            $this->insightService->generateStrategicInsight($action);
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal generate strategic insight setelah store (Manajemen): ' . $e->getMessage());
        }

        return redirect()->route('manajemen.strategic-actions.index')->with('success', 'Strategic Action recorded successfully.');
    }

    public function show(StrategicAction $strategicAction)
    {
        $strategicAction->load('garden.region');
        return view('manajemen.strategic_actions.show', compact('strategicAction'));
    }

    public function edit(StrategicAction $strategicAction)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('manajemen.strategic_actions.edit', compact('strategicAction', 'gardens'));
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

        try {
            $this->insightService->generateStrategicInsight($strategicAction);
        } catch (\Exception $e) {
            Log::warning('[InsightService] Gagal generate strategic insight setelah update (Manajemen): ' . $e->getMessage());
        }

        return redirect()->route('manajemen.strategic-actions.index')->with('success', 'Strategic Action updated successfully.');
    }

    public function destroy(StrategicAction $strategicAction)
    {
        $strategicAction->delete();
        return redirect()->route('manajemen.strategic-actions.index')->with('success', 'Strategic Action deleted successfully.');
    }
}
