<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerformanceTarget;
use App\Models\Garden;
use Illuminate\Http\Request;

class AdminPerformanceTargetController extends Controller
{
    public function index()
    {
        $targets = PerformanceTarget::with('garden')->orderBy('year', 'desc')->paginate(10);
        return view('admin.performance_targets.index', compact('targets'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.performance_targets.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'year' => 'required|integer|min:2000|max:2099',
            'target_protas_min' => 'required|numeric|min:0',
            'target_protas_max' => 'required|numeric|min:0|gte:target_protas_min',
            'note' => 'nullable|string',
        ]);

        PerformanceTarget::create($validated);

        return redirect()->route('admin.performance-targets.index')->with('success', 'Target Protas created successfully.');
    }

    public function edit(PerformanceTarget $performanceTarget)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.performance_targets.edit', compact('performanceTarget', 'gardens'));
    }

    public function update(Request $request, PerformanceTarget $performanceTarget)
    {
        $validated = $request->validate([
            'kebun_id' => 'required|exists:gardens,id',
            'year' => 'required|integer|min:2000|max:2099',
            'target_protas_min' => 'required|numeric|min:0',
            'target_protas_max' => 'required|numeric|min:0|gte:target_protas_min',
            'note' => 'nullable|string',
        ]);

        $performanceTarget->update($validated);

        return redirect()->route('admin.performance-targets.index')->with('success', 'Target Protas updated successfully.');
    }

    public function destroy(PerformanceTarget $performanceTarget)
    {
        $performanceTarget->delete();
        return redirect()->route('admin.performance-targets.index')->with('success', 'Target Protas deleted successfully.');
    }
}
