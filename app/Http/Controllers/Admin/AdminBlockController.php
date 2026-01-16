<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Afdeling;
use App\Models\Block;
use App\Models\Garden;
use Illuminate\Http\Request;

class AdminBlockController extends Controller
{
    public function index(Request $request)
    {
        $query = Block::with('afdeling.garden');

        if ($request->filled('garden_id')) {
            $query->whereHas('afdeling', function ($q) use ($request) {
                $q->where('garden_id', $request->garden_id);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $blocks = $query->paginate(10);
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.blocks.index', compact('blocks', 'gardens'));
    }

    public function create()
    {
        $afdelings = Afdeling::with('garden')->get();
        return view('admin.blocks.create', compact('afdelings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'afdeling_id' => 'required|exists:afdelings,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:blocks,code',
            'plant_type' => 'required|in:seedling,klon_gmb,klon_tri',
            'planting_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'initial_class' => 'required|in:A,B,C,D,E',
            'topography' => 'required|in:datar,gelombang,curam',
        ]);

        Block::create($request->all());

        return redirect()->route('admin.blocks.index')->with('success', 'Block created successfully.');
    }

    public function edit(Block $block)
    {
        $afdelings = Afdeling::with('garden')->get();
        return view('admin.blocks.edit', compact('block', 'afdelings'));
    }

    public function update(Request $request, Block $block)
    {
        $request->validate([
            'afdeling_id' => 'required|exists:afdelings,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:blocks,code,' . $block->id,
            'plant_type' => 'required|in:seedling,klon_gmb,klon_tri',
            'planting_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'initial_class' => 'required|in:A,B,C,D,E',
            'topography' => 'required|in:datar,gelombang,curam',
        ]);

        $block->update($request->all());

        return redirect()->route('admin.blocks.index')->with('success', 'Block updated successfully.');
    }

    public function destroy(Block $block)
    {
        $block->delete();
        return redirect()->route('admin.blocks.index')->with('success', 'Block deleted successfully.');
    }
}
