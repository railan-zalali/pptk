<?php

namespace App\Http\Controllers;

use App\Models\CommunityService;
use Illuminate\Http\Request;

class CommunityServiceController extends Controller
{
    public function index()
    {
        $services = CommunityService::latest()->get();
        return view('community_services.index', compact('services'));
    }
}
