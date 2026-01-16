@extends('layouts.admin')

@section('title', 'Tambah Wilayah')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Wilayah Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi formulir berikut untuk menambahkan data wilayah
                baru.</p>
        </div>

        <form action="{{ route('admin.regions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Wilayah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Wilayah *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">map</span>
                        </span>
                        <input name="regional_name" type="text" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Jawa Barat" value="{{ old('regional_name') }}">
                    </div>
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Provinsi *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">location_city</span>
                        </span>
                        <input name="province" type="text" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Jawa Barat" value="{{ old('province') }}">
                    </div>
                </div>

                <!-- Koordinat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Koordinat
                        (lat,long)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">place</span>
                        </span>
                        <input name="coordinates" type="text"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: -6.9175,107.6191" value="{{ old('coordinates') }}">
                    </div>
                </div>

                <!-- Foto Utama -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Wilayah
                        Utama</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">photo_camera</span>
                        </span>
                        <input name="photo" type="file" accept="image/*"
                            class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-300">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG. Maksimal 4MB.</p>
                </div>

                <!-- Foto Tambahan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Tambahan
                        (Galeri)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">collections</span>
                        </span>
                        <input name="photos[]" type="file" accept="image/*" multiple
                            class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-300">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bisa pilih lebih dari satu foto.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan
                </button>
                <a href="{{ route('admin.regions.index') }}"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
