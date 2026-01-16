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

    public function editResearch()
    {
        $this->ensureAdmin();
        $page = Page::firstOrCreate(['slug' => 'research'], [
            'title' => 'Penelitian & Pengembangan',
            'subtitle' => 'Inovasi dan riset untuk keberlanjutan perkebunan teh',
            'meta' => [
                'summary' => [
                    'total_activities' => 0,
                    'total_budget' => 0,
                    'status' => 'Aktif'
                ],
                'info' => [
                    'internal_research' => '',
                    'external_research' => '',
                    'incubation' => '',
                    'rkap_notes' => ''
                ],
                'activities' => [],
            ],
            'files' => []
        ]);
        return view('admin.pages.research', compact('page'));
    }

    public function updateResearch(Request $request)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content_html' => 'nullable|string',
            'hero_photo' => 'nullable|image|max:4096',
            
            // Meta Data Validation
            'meta.summary.total_activities' => 'nullable|integer',
            'meta.summary.total_budget' => 'nullable|numeric',
            'meta.summary.status' => 'nullable|string',
            
            'meta.info.internal_research' => 'nullable|string',
            'meta.info.external_research' => 'nullable|string',
            'meta.info.incubation' => 'nullable|string',
            'meta.info.rkap_notes' => 'nullable|string',
            
            'meta.activities' => 'nullable|array',
            'meta.activities.*.title' => 'required|string',
            'meta.activities.*.type' => 'required|string',
            'meta.activities.*.year' => 'required|integer',
            
            // File Uploads
            'new_files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:10240',
            'existing_files' => 'nullable|array',
        ]);

        $page = Page::firstOrCreate(['slug' => 'research']);
        
        // Handle basic fields
        $page->title = $validated['title'];
        $page->subtitle = $validated['subtitle'];
        $page->content_html = $validated['content_html'];

        // Handle Hero Photo
        if ($request->hasFile('hero_photo')) {
            $path = $request->file('hero_photo')->store('page-photos', 'public');
            $page->hero_photo_path = $path;
        }

        // Handle Meta Data
        $meta = [
            'summary' => [
                'total_activities' => $request->input('meta.summary.total_activities', 0),
                'total_budget' => $request->input('meta.summary.total_budget', 0),
                'status' => $request->input('meta.summary.status', 'Aktif'),
            ],
            'info' => [
                'internal_research' => $request->input('meta.info.internal_research'),
                'external_research' => $request->input('meta.info.external_research'),
                'incubation' => $request->input('meta.info.incubation'),
                'rkap_notes' => $request->input('meta.info.rkap_notes'),
            ],
            'activities' => array_values($request->input('meta.activities', [])), // Re-index array
        ];
        $page->meta = $meta;

        // Handle Files
        $currentFiles = $page->files ?? [];
        
        // Keep existing files that were sent back
        $keptFiles = [];
        if ($request->has('existing_files')) {
            foreach ($request->input('existing_files') as $index => $val) {
                if (isset($currentFiles[$index])) {
                    $keptFiles[] = $currentFiles[$index];
                }
            }
        }

        // Add new files
        if ($request->hasFile('new_files')) {
            foreach ($request->file('new_files') as $file) {
                $path = $file->store('research-files', 'public');
                $keptFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
        }
        
        $page->files = $keptFiles;
        $page->save();

        return redirect()->route('admin.pages.research.edit')->with('success', 'Halaman Penelitian berhasil diperbarui.');
    }
}
