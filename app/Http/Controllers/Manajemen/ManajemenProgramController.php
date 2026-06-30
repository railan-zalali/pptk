<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ManajemenProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('year', 'desc')
            ->orderBy('program_name')
            ->paginate(15);

        return view('manajemen.programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        return view('manajemen.programs.show', compact('program'));
    }

    public function create()
    {
        return view('manajemen.programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:2000|max:2099',
            'program_type' => 'required|in:Model,Pengembangan',
            'status' => 'boolean',
        ]);

        Program::create($validated);

        return redirect()->route('manajemen.programs.index')->with('success', 'Program created successfully.');
    }

    public function edit(Program $program)
    {
        return view('manajemen.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:2000|max:2099',
            'program_type' => 'required|in:Model,Pengembangan',
            'status' => 'boolean',
        ]);

        $program->update($validated);

        return redirect()->route('manajemen.programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('manajemen.programs.index')->with('success', 'Program deleted successfully.');
    }
}
