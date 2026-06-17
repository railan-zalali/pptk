<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Program;

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
}
