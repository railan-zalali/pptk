<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garden;
use App\Models\Insight;
use Illuminate\Http\Request;

class AdminInsightController extends Controller
{
    public function index(Request $request)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        $query = Insight::with('garden.region')->orderByDesc('created_at');
        if ($request->filled('garden_id')) {
            $query->where('garden_id', $request->get('garden_id'));
        }
        if ($request->filled('alert_level')) {
            $query->where('alert_level', $request->get('alert_level'));
        }
        $insights = $query->paginate(15);
        return view('admin.insights.index', compact('insights', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.insights.create', compact('gardens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'insight_type' => 'required|string|max:100',
            'message' => 'required|string',
            'alert_level' => 'required|in:low,medium,high',
            'recommendations' => 'nullable|array',
        ]);

        $payload = $validated;
        if (isset($payload['recommendations']) && is_array($payload['recommendations'])) {
            $payload['recommendations'] = json_encode(array_values($payload['recommendations']));
        }

        Insight::create($payload);

        return redirect()->route('admin.insights.index')->with('success', 'Insight berhasil ditambahkan.');
    }

    public function edit(Insight $insight)
    {
        $gardens = Garden::orderBy('kebun_name')->get();
        return view('admin.insights.edit', compact('insight', 'gardens'));
    }

    public function update(Request $request, Insight $insight)
    {
        $validated = $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'insight_type' => 'required|string|max:100',
            'message' => 'required|string',
            'alert_level' => 'required|in:low,medium,high',
            'recommendations' => 'nullable|array',
        ]);

        $payload = $validated;
        if (isset($payload['recommendations']) && is_array($payload['recommendations'])) {
            $payload['recommendations'] = json_encode(array_values($payload['recommendations']));
        }

        $insight->update($payload);

        return redirect()->route('admin.insights.index')->with('success', 'Insight berhasil diperbarui.');
    }

    public function destroy(Insight $insight)
    {
        $insight->delete();
        return redirect()->route('admin.insights.index')->with('success', 'Insight berhasil dihapus.');
    }
}
