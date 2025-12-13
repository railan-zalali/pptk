@extends('layouts.admin')

@section('title', 'Edit Halaman Tentang')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('admin.pages.about.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Hero</label>
                    <input name="title" type="text" class="w-full border rounded px-3 py-2"
                        value="{{ old('title', $page->title) }}" placeholder="Contoh: Tentang Kebun Model">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subjudul Hero</label>
                    <input name="subtitle" type="text" class="w-full border rounded px-3 py-2"
                        value="{{ old('subtitle', $page->subtitle) }}" placeholder="Subjudul singkat">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Hero</label>
                    @if ($page->hero_photo_path)
                        <img src="{{ asset('storage/' . $page->hero_photo_path) }}" alt="Hero"
                            class="w-32 h-20 object-cover rounded mb-2">
                    @endif
                    <input name="hero_photo" type="file" accept="image/*" class="w-full border rounded px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overview (HTML)</label>
                    <textarea name="overview_html" rows="6" class="w-full border rounded px-3 py-2"
                        placeholder="Overview kebun model">{{ old('overview_html', $page->overview_html) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sejarah (HTML)</label>
                    <textarea name="sejarah_html" rows="8" class="w-full border rounded px-3 py-2" placeholder="Sejarah kebun model">{{ old('sejarah_html', $page->sejarah_html) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan (HTML)</label>
                    <textarea name="tujuan_html" rows="8" class="w-full border rounded px-3 py-2" placeholder="Tujuan PPTK">{{ old('tujuan_html', $page->tujuan_html) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Manfaat (HTML)</label>
                    <textarea name="manfaat_html" rows="8" class="w-full border rounded px-3 py-2" placeholder="Manfaat kebun model">{{ old('manfaat_html', $page->manfaat_html) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi (HTML)</label>
                    <textarea name="lokasi_html" rows="10" class="w-full border rounded px-3 py-2" placeholder="Lokasi kebun model">{{ old('lokasi_html', $page->lokasi_html) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </form>
    </div>
@endsection
