@extends('layouts.admin')

@section('title', 'Tambah Blok Baru')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 transition-all duration-300">
        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Form Tambah Blok
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi informasi untuk menambahkan blok baru.</p>
        </div>

        <form action="{{ route('admin.blocks.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Afdeling Selection -->
                <div class="col-span-2">
                    <label for="afdeling_id"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Afdeling <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">domain</span>
                        </span>
                        <select name="afdeling_id" id="afdeling_id"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="">Pilih Afdeling</option>
                            @foreach ($afdelings as $afdeling)
                                <option value="{{ $afdeling->id }}"
                                    {{ old('afdeling_id') == $afdeling->id ? 'selected' : '' }}>
                                    {{ $afdeling->name }} ({{ $afdeling->garden->kebun_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('afdeling_id')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kode
                        Blok <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">tag</span>
                        </span>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: BLK001" required>
                    </div>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                        Blok <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">label</span>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Blok Utara" required>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Plant Type -->
                <div>
                    <label for="plant_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tipe
                        Tanaman <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">grass</span>
                        </span>
                        <select name="plant_type" id="plant_type"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="">Pilih Tipe</option>
                            <option value="seedling" {{ old('plant_type') == 'seedling' ? 'selected' : '' }}>Seedling
                            </option>
                            <option value="klon_gmb" {{ old('plant_type') == 'klon_gmb' ? 'selected' : '' }}>Klon GMB
                            </option>
                            <option value="klon_tri" {{ old('plant_type') == 'klon_tri' ? 'selected' : '' }}>Klon TRI
                            </option>
                        </select>
                    </div>
                    @error('plant_type')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Planting Year -->
                <div>
                    <label for="planting_year"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tahun Tanam <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input type="number" name="planting_year" id="planting_year" value="{{ old('planting_year') }}"
                            min="1900" max="{{ date('Y') + 1 }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="YYYY" required>
                    </div>
                    @error('planting_year')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Initial Class -->
                <div>
                    <label for="initial_class"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kelas Awal <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">grade</span>
                        </span>
                        <select name="initial_class" id="initial_class"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="">Pilih Kelas</option>
                            <option value="A" {{ old('initial_class') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('initial_class') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('initial_class') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('initial_class') == 'D' ? 'selected' : '' }}>D</option>
                            <option value="E" {{ old('initial_class') == 'E' ? 'selected' : '' }}>E</option>
                        </select>
                    </div>
                    @error('initial_class')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Topography -->
                <div>
                    <label for="topography"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Topografi <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">terrain</span>
                        </span>
                        <select name="topography" id="topography"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="">Pilih Topografi</option>
                            <option value="datar" {{ old('topography') == 'datar' ? 'selected' : '' }}>Datar</option>
                            <option value="gelombang" {{ old('topography') == 'gelombang' ? 'selected' : '' }}>Gelombang
                            </option>
                            <option value="curam" {{ old('topography') == 'curam' ? 'selected' : '' }}>Curam</option>
                        </select>
                    </div>
                    @error('topography')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('admin.blocks.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-lg">arrow_back</span>
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all shadow-lg hover:shadow-green-500/30 flex items-center gap-2">
                    <span class="material-icons-outlined text-lg">save</span>
                    Simpan Blok
                </button>
            </div>
        </form>
    </div>
@endsection
