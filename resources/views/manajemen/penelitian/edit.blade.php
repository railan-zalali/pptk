@extends('layouts.admin')

@section('title', 'Edit Halaman Penelitian')

@section('content')
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-4 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manajemen.penelitian.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Section 1: Hero & General -->
            <div class="mb-8 border-b dark:border-gray-800 pb-4">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">1. Informasi Umum Halaman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Hero</label>
                        <input name="title" type="text" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2"
                            value="{{ old('title', $page->title) }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subjudul Hero</label>
                        <input name="subtitle" type="text" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2"
                            value="{{ old('subtitle', $page->subtitle) }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Hero</label>
                        @if ($page->hero_photo_path)
                            <img src="{{ asset('storage/' . $page->hero_photo_path) }}" alt="Hero"
                                class="w-32 h-20 object-cover rounded mb-2">
                        @endif
                        <input name="hero_photo" type="file" accept="image/*" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Section 2: Ringkasan Penelitian (Summary Stats) -->
            <div class="mb-8 border-b dark:border-gray-800 pb-4">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">2. Ringkasan Penelitian</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Kegiatan</label>
                        <input name="meta[summary][total_activities]" type="number" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2"
                            value="{{ old('meta.summary.total_activities', $page->meta['summary']['total_activities'] ?? 0) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Anggaran (Rp)</label>
                        <input name="meta[summary][total_budget]" type="number" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2"
                            value="{{ old('meta.summary.total_budget', $page->meta['summary']['total_budget'] ?? 0) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sisa Anggaran (Rp)</label>
                        <input name="meta[summary][remaining_budget]" type="number" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2"
                            value="{{ old('meta.summary.remaining_budget', $page->meta['summary']['remaining_budget'] ?? 0) }}">
                    </div>
                </div>
            </div>

            <!-- Section 3: Informasi Penelitian (Descriptive) -->
            <div class="mb-8 border-b dark:border-gray-800 pb-4">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">3. Informasi Detail Penelitian</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Riset Internal (Deskripsi)</label>
                        <textarea name="meta[info][internal_research]" rows="3" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2">{{ old('meta.info.internal_research', $page->meta['info']['internal_research'] ?? '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Riset Eksternal (Deskripsi)</label>
                        <textarea name="meta[info][external_research]" rows="3" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2">{{ old('meta.info.external_research', $page->meta['info']['external_research'] ?? '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Inkubasi Riset (Deskripsi)</label>
                        <textarea name="meta[info][incubation]" rows="3" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2">{{ old('meta.info.incubation', $page->meta['info']['incubation'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ringkasan RKAP (Anggaran)</label>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">Jumlah anggaran RKAP yang dialokasikan (Rp)</p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400 pointer-events-none">Rp</span>
                            <input type="number" name="meta[info][rkap_budget]" min="0" step="1000"
                                class="w-full border dark:border-gray-700 dark:bg-gray-800 rounded pl-10 pr-3 py-2 text-sm"
                                value="{{ old('meta.info.rkap_budget', $page->meta['info']['rkap_budget'] ?? 0) }}"
                                placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ringkasan Pencairan</label>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">Total dana yang sudah dicairkan (Rp)</p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400 pointer-events-none">Rp</span>
                            <input type="number" name="meta[info][rkap_disbursement]" min="0" step="1000"
                                class="w-full border dark:border-gray-700 dark:bg-gray-800 rounded pl-10 pr-3 py-2 text-sm"
                                value="{{ old('meta.info.rkap_disbursement', $page->meta['info']['rkap_disbursement'] ?? 0) }}"
                                placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Daftar Kegiatan (Repeater) -->
            <div class="mb-8 border-b dark:border-gray-800 pb-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">4. Daftar Kegiatan Penelitian</h2>
                    <button type="button" onclick="addActivityRow()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        + Tambah Kegiatan
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="activitiesTable">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-350">
                            <tr>
                                <th class="px-4 py-3">Judul Penelitian</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Tahun</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="activitiesContainer">
                            @php
                                $activities = old('meta.activities', $page->meta['activities'] ?? []);
                            @endphp
                            @foreach($activities as $index => $activity)
                            <tr class="bg-white dark:bg-gray-900 border-b dark:border-gray-800 activity-row">
                                <td class="px-4 py-2">
                                    <input type="text" name="meta[activities][{{ $index }}][title]" value="{{ $activity['title'] ?? '' }}" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-2 py-1" required>
                                </td>
                                <td class="px-4 py-2">
                                    <select name="meta[activities][{{ $index }}][type]" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-2 py-1">
                                        <option value="Internal" {{ ($activity['type'] ?? '') == 'Internal' ? 'selected' : '' }}>Internal</option>
                                        <option value="Eksternal" {{ ($activity['type'] ?? '') == 'Eksternal' ? 'selected' : '' }}>Eksternal</option>
                                        <option value="Inkubasi" {{ ($activity['type'] ?? '') == 'Inkubasi' ? 'selected' : '' }}>Inkubasi</option>
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="meta[activities][{{ $index }}][year]" value="{{ $activity['year'] ?? date('Y') }}" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-2 py-1" style="width: 80px;">
                                </td>
                                <td class="px-4 py-2">
                                    <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-500 mt-2">* Tambahkan kegiatan penelitian secara manual di sini.</p>
            </div>

            <!-- Section 5: Dokumentasi & Laporan -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">5. Dokumentasi & Laporan</h2>
                
                <!-- Existing Files -->
                @if(!empty($page->files))
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Tersimpan:</h3>
                    <ul class="space-y-2">
                        @foreach($page->files as $index => $file)
                        <li class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 p-2 rounded">
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2">description</span>
                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                    {{ $file['name'] }}
                                </a>
                                <span class="text-xs text-gray-400 ml-2">({{ round($file['size'] / 1024) }} KB)</span>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="existing_files[{{ $index }}]" value="1">
                                <button type="button" onclick="removeFile(this)" class="text-red-500 hover:text-red-700 text-xs ml-4">Hapus</button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Upload New -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-350 mb-2">Upload File Baru (PDF/Doc/XLS/Gambar)</label>
                    <input type="file" name="new_files[]" multiple class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-3 py-2">
                </div>
            </div>

            <div class="mt-6 flex gap-3 sticky bottom-0 bg-white dark:bg-gray-900 py-4 border-t dark:border-gray-800">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-medium">Simpan Perubahan</button>
                <a href="{{ route('manajemen.penelitian.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded font-medium">Kembali</a>
            </div>
        </form>
    </div>

    <script>
        function addActivityRow() {
            const container = document.getElementById('activitiesContainer');
            const index = container.children.length;
            const year = new Date().getFullYear();
            
            const row = `
                <tr class="bg-white dark:bg-gray-900 border-b dark:border-gray-800 activity-row">
                    <td class="px-4 py-2">
                        <input type="text" name="meta[activities][${index}][title]" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-2 py-1" required placeholder="Judul Penelitian">
                    </td>
                    <td class="px-4 py-2">
                        <select name="meta[activities][${index}][type]" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-2 py-1">
                            <option value="Internal">Internal</option>
                            <option value="Eksternal">Eksternal</option>
                            <option value="Inkubasi">Inkubasi</option>
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" name="meta[activities][${index}][year]" value="${year}" class="w-full border dark:border-gray-700 dark:bg-gray-850 rounded px-2 py-1" style="width: 80px;">
                    </td>
                    <td class="px-4 py-2">
                        <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">Hapus</button>
                    </td>
                </tr>
            `;
            container.insertAdjacentHTML('beforeend', row);
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
        }

        function removeFile(btn) {
            btn.closest('li').remove();
        }
    </script>
@endsection
