<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Page;

class ManajemenResearchController extends Controller
{
    public function index()
    {
        // Load halaman penelitian dari tabel pages
        $page = Page::where('slug', 'research')->first();

        return view('manajemen.penelitian.index', compact('page'));
    }
}
