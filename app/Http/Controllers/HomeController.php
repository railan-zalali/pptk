<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\RegionPhoto;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $heroPhotoUrl = null;
        if (Schema::hasTable('regions')) {
            $regionWithPhoto = Region::whereNotNull('photo_path')->inRandomOrder()->first();
            $heroPhotoUrl = $regionWithPhoto ? asset('storage/' . $regionWithPhoto->photo_path) : null;
        }
        if (!$heroPhotoUrl && Schema::hasTable('region_photos')) {
            $anyPhoto = RegionPhoto::inRandomOrder()->first();
            $heroPhotoUrl = $anyPhoto ? asset('storage/' . $anyPhoto->path) : null;
        }
        return view('home', compact('heroPhotoUrl'));
    }
}
