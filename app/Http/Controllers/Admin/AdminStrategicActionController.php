<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStrategicActionRequest;
use App\Http\Requests\UpdateStrategicActionRequest;
use App\Models\Garden;
use App\Models\Program;
use App\Models\StrategicAction;

class AdminStrategicActionController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = StrategicAction::with('garden.region', 'program');

        if ($request->filled('kebun_id')) {
            $query->where('kebun_id', $request->kebun_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $actions = $query->orderBy('year', 'desc')->paginate(15)->withQueryString();
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.strategic_actions.index', compact('actions', 'gardens'));
    }

    public function create()
    {
        $gardens  = Garden::orderBy('kebun_name')->get();
        $programs = Program::where('status', true)->orderBy('program_name')->get();

        return view('admin.strategic_actions.create', compact('gardens', 'programs'));
    }

    public function store(StoreStrategicActionRequest $request)
    {
        StrategicAction::create($request->validated());

        return redirect()->route('admin.strategic-actions.index')
            ->with('success', 'Aksi strategis berhasil ditambahkan.');
    }

    public function edit(StrategicAction $strategicAction)
    {
        $gardens  = Garden::orderBy('kebun_name')->get();
        $programs = Program::where('status', true)->orderBy('program_name')->get();

        return view('admin.strategic_actions.edit', compact('strategicAction', 'gardens', 'programs'));
    }

    public function update(UpdateStrategicActionRequest $request, StrategicAction $strategicAction)
    {
        $strategicAction->update($request->validated());

        return redirect()->route('admin.strategic-actions.index')
            ->with('success', 'Aksi strategis berhasil diperbarui.');
    }

    public function destroy(StrategicAction $strategicAction)
    {
        $strategicAction->delete();

        return redirect()->route('admin.strategic-actions.index')
            ->with('success', 'Aksi strategis berhasil dihapus.');
    }
}
