<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\RegionPhoto;
use App\Models\Garden;
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

        // Data kebun dengan koordinat untuk peta halaman depan
        $gardensForMap = [];
        if (Schema::hasTable('gardens')) {
            $gardensForMap = Garden::whereNotNull('coordinates')
                ->where('coordinates', '!=', '')
                ->select('id', 'kebun_name', 'location', 'coordinates', 'kebun_type')
                ->get()
                ->map(fn($g) => [
                    'name'        => $g->kebun_name,
                    'location'    => $g->location,
                    'type'        => $g->kebun_type,
                    'coordinates' => $g->coordinates,
                ])
                ->toArray();
        }

        return view('home', compact('heroPhotoUrl', 'gardensForMap'));
    }
}
