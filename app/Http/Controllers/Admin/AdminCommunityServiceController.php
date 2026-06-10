<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommunityServiceRequest;
use App\Models\CommunityService;
use App\Models\Garden;
use Illuminate\Http\Request;

class AdminCommunityServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = CommunityService::with('garden');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('garden_id')) {
            $query->where('garden_id', $request->garden_id);
        }

        $services = $query->orderBy('year', 'desc')->paginate(15)->withQueryString();
        $gardens  = Garden::orderBy('kebun_name')->get();

        return view('admin.community_services.index', compact('services', 'gardens'));
    }

    public function create()
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.community_services.create', compact('gardens'));
    }

    public function store(StoreCommunityServiceRequest $request)
    {
        CommunityService::create($request->validated());

        return redirect()->route('admin.community-services.index')
            ->with('success', 'Kegiatan pengabdian masyarakat berhasil ditambahkan.');
    }

    public function show(CommunityService $communityService)
    {
        $communityService->load('garden.region');

        return view('admin.community_services.show', compact('communityService'));
    }

    public function edit(CommunityService $communityService)
    {
        $gardens = Garden::orderBy('kebun_name')->get();

        return view('admin.community_services.edit', compact('communityService', 'gardens'));
    }

    public function update(StoreCommunityServiceRequest $request, CommunityService $communityService)
    {
        $communityService->update($request->validated());

        return redirect()->route('admin.community-services.index')
            ->with('success', 'Kegiatan pengabdian masyarakat berhasil diperbarui.');
    }

    public function destroy(CommunityService $communityService)
    {
        $communityService->delete();

        return redirect()->route('admin.community-services.index')
            ->with('success', 'Kegiatan pengabdian masyarakat berhasil dihapus.');
    }
}
