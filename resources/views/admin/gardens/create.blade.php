@extends('layouts.admin')

@section('title', 'Tambah Kebun')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Kebun Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi formulir berikut untuk menambahkan data kebun baru.
            </p>
        </div>

        <form action="{{ route('admin.gardens.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kebun Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kebun *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <input name="kebun_name" type="text" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('kebun_name') }}" placeholder="Masukkan nama kebun">
                    </div>
                </div>

                <!-- Regional -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wilayah *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">map</span>
                        </span>
                        <select name="regional_id" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Wilayah</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('regional_id') == $region->id ? 'selected' : '' }}>{{ $region->regional_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Luas Total Ha -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Luas Total (ha) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">aspect_ratio</span>
                        </span>
                        <input name="luas_total_ha" type="number" step="0.01" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('luas_total_ha') }}">
                    </div>
                </div>

                <!-- Kebun Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kebun *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">category</span>
                        </span>
                        <select name="kebun_type" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="Model" {{ old('kebun_type') == 'Model' ? 'selected' : '' }}>Model</option>
                            <option value="Pengembangan" {{ old('kebun_type') == 'Pengembangan' ? 'selected' : '' }}>
                                Pengembangan</option>
                        </select>
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">place</span>
                        </span>
                        <input name="location" type="text"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('location') }}" placeholder="Contoh: Bandung Selatan">
                    </div>
                </div>

                <!-- Established At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Berdiri</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input name="established_at" type="date"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('established_at') }}">
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">description</span>
                        </span>
                        <textarea name="description" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Deskripsi lengkap tentang kebun">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Agro Climate Note -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Agro
                        Klimat</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">cloud</span>
                        </span>
                        <textarea name="agro_climate_note" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Informasi cuaca, tanah, curah hujan, dll">{{ old('agro_climate_note') }}</textarea>
                    </div>
                </div>

                <!-- Photo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Kebun
                        (opsional)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">photo_camera</span>
                        </span>
                        <input name="photo" type="file" accept="image/*"
                            class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-300">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG. Maksimal 4MB.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan
                </button>
                <a href="{{ route('admin.gardens.index') }}"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
