<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityService;
use Illuminate\Http\Request;

class AdminCommunityServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = CommunityService::orderBy('year', 'desc')->latest()->paginate(10);
        return view('admin.community_services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.community_services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'team_name' => 'required|string|max:255',
            'total_budget' => 'required|numeric|min:0',
            'remaining_budget' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2000|max:2099',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        CommunityService::create($validated);

        return redirect()->route('manajemen.community-services.index')
            ->with('success', 'Kegiatan pengabdian masyarakat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CommunityService $communityService)
    {
        return view('admin.community_services.show', compact('communityService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommunityService $communityService)
    {
        return view('admin.community_services.edit', compact('communityService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommunityService $communityService)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'team_name' => 'required|string|max:255',
            'total_budget' => 'required|numeric|min:0',
            'remaining_budget' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2000|max:2099',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        $communityService->update($validated);

        return redirect()->route('manajemen.community-services.index')
            ->with('success', 'Kegiatan pengabdian masyarakat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommunityService $communityService)
    {
        $communityService->delete();

        return redirect()->route('manajemen.community-services.index')
            ->with('success', 'Kegiatan pengabdian masyarakat berhasil dihapus.');
    }
}
