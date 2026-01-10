<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\StrategicAction;
use Illuminate\Http\Request;

class AdminStrategicActionController extends Controller
{
    public function index()
    {
        $actions = StrategicAction::with('block.afdeling.garden')->orderBy('period', 'desc')->paginate(10);
        return view('admin.strategic_actions.index', compact('actions'));
    }

    public function create()
    {
        $blocks = Block::with('afdeling.garden')->get();
        return view('admin.strategic_actions.create', compact('blocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'block_id' => 'required|exists:blocks,id',
            'period' => 'required|date',
            'action_type' => 'required|in:fertilizer_root,fertilizer_leaf,cultivator,weed_control',
            'target_volume' => 'required|numeric|min:0',
            'realization_volume' => 'required|numeric|min:0',
            'nitrogen_content' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        StrategicAction::create($request->all());

        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action recorded successfully.');
    }

    public function edit(StrategicAction $strategicAction)
    {
        $blocks = Block::with('afdeling.garden')->get();
        return view('admin.strategic_actions.edit', compact('strategicAction', 'blocks'));
    }

    public function update(Request $request, StrategicAction $strategicAction)
    {
        $request->validate([
            'block_id' => 'required|exists:blocks,id',
            'period' => 'required|date',
            'action_type' => 'required|in:fertilizer_root,fertilizer_leaf,cultivator,weed_control',
            'target_volume' => 'required|numeric|min:0',
            'realization_volume' => 'required|numeric|min:0',
            'nitrogen_content' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $strategicAction->update($request->all());

        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action updated successfully.');
    }

    public function destroy(StrategicAction $strategicAction)
    {
        $strategicAction->delete();
        return redirect()->route('admin.strategic-actions.index')->with('success', 'Strategic Action deleted successfully.');
    }
}
