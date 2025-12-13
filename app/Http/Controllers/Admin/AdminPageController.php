<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function editAbout()
    {
        $this->ensureAdmin();
        $page = Page::firstOrCreate(['slug' => 'about'], [
            'title' => 'Tentang Kebun Model',
            'subtitle' => 'Memahami peran kebun model teh dalam pengembangan berkelanjutan',
        ]);
        return view('admin.pages.about', compact('page'));
    }

    public function updateAbout(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content_html' => 'nullable|string',
            'overview_html' => 'nullable|string',
            'sejarah_html' => 'nullable|string',
            'tujuan_html' => 'nullable|string',
            'manfaat_html' => 'nullable|string',
            'lokasi_html' => 'nullable|string',
            'hero_photo' => 'nullable|image|max:4096',
        ]);

        $page = Page::firstOrCreate(['slug' => 'about']);
        $page->fill($validated);

        if ($request->hasFile('hero_photo')) {
            $path = $request->file('hero_photo')->store('page-photos', 'public');
            $page->hero_photo_path = $path;
        }

        $page->save();

        return redirect()->route('admin.pages.about.edit')->with('success', 'Halaman Tentang berhasil diperbarui.');
    }
}
