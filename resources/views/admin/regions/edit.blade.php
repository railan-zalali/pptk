@extends('layouts.admin')

@section('title', 'Edit Wilayah')

@section('content')
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-200">
        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-yellow-600">edit</span>
                Edit Wilayah
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui informasi wilayah dan foto.</p>
        </div>

        <form action="{{ route('admin.regions.update', $region) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Wilayah *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">map</span>
                        </span>
                        <input name="regional_name" type="text" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Wilayah I" value="{{ old('regional_name', $region->regional_name) }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Provinsi *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">location_city</span>
                        </span>
                        <input name="province" type="text" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Jawa Barat" value="{{ old('province', $region->province) }}">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Koordinat
                        (lat,long)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">place</span>
                        </span>
                        <input name="coordinates" type="text"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="-7.123456, 107.123456" value="{{ old('coordinates', $region->coordinates) }}">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Wilayah
                        Utama</label>
                    <div class="flex items-start gap-4 mb-3">
                        @if ($region->photo_path)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $region->photo_path) }}" alt="Foto Wilayah"
                                    class="w-32 h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Saat ini</p>
                            </div>
                        @endif
                        <div class="flex-grow">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-icons-outlined text-gray-400 text-sm">photo_camera</span>
                                </span>
                                <input name="photo" type="file" accept="image/*"
                                    class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400">
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload foto baru untuk mengganti.</p>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Tambahan
                        (Galeri)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">collections</span>
                        </span>
                        <input name="photos[]" type="file" accept="image/*" multiple
                            class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-3">Upload foto tambahan baru (foto lama tetap
                        ada).</p>

                    @if ($region->photos && $region->photos->count())
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach ($region->photos as $p)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $p->path) }}" alt="Foto Wilayah"
                                        class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <!-- Optional: Add delete button for individual photos if controller supports it,
                                                 currently controller doesn't seem to have individual photo delete in update method explicitly
                                                 but for UI/UX let's just show them cleanly. -->
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-8 flex gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.regions.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
