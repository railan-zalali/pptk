<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\StrategicAction;
use Illuminate\Http\Request;

class AdminStrategicActionController extends Controller
{
    public function index()
    {
        $actions = StrategicAction::with('garden')->orderBy('year', 'desc')->paginate(10);
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

        StrategicAction::create($validated);

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

        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action updated successfully.');
    }

    public function destroy(StrategicAction $strategicAction)
    {
        $strategicAction->delete();
        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action deleted successfully.');
    }
}
