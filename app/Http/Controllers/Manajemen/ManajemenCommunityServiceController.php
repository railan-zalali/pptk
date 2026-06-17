<?php

namespace App\Http\Controllers\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\CommunityService;

class ManajemenCommunityServiceController extends Controller
{
    public function index()
    {
        $communityServices = CommunityService::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('manajemen.community_services.index', compact('communityServices'));
    }
}
