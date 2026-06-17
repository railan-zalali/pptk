<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Insight;

class ManajemenInsightController extends Controller
{
    public function index()
    {
        $insights = Insight::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('manajemen.insights.index', compact('insights'));
    }

    public function show(Insight $insight)
    {
        return view('manajemen.insights.show', compact('insight'));
    }
}
