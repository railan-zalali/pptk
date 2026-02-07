@extends('layouts.admin')

@section('title', 'Tambah Afdeling Baru')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Afdeling Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi informasi berikut untuk menambahkan data afdeling
                baru.</p>
        </div>

        <form action="{{ route('admin.afdelings.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Garden Selection -->
                <div class="md:col-span-2">
                    <label for="kebun_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kebun
                        Induk *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="kebun_id" id="kebun_id"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="">Pilih Kebun</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}" {{ old('kebun_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->kebun_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('kebun_id')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                        Afdeling *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">label</span>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Afdeling A" required>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Manager Name -->
                <div>
                    <label for="manager_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                        Manager</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">person</span>
                        </span>
                        <input type="text" name="manager_name" id="manager_name" value="{{ old('manager_name') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Nama Manager Afdeling">
                    </div>
                    @error('manager_name')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Total Area -->
                <div>
                    <label for="total_area_ha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total
                        Area (Ha) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">aspect_ratio</span>
                        </span>
                        <input type="number" step="0.01" name="total_area_ha" id="total_area_ha"
                            value="{{ old('total_area_ha') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required>
                    </div>
                    @error('total_area_ha')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- TM Area -->
                <div>
                    <label for="tm_area_ha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Luas TM
                        (Ha) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">grass</span>
                        </span>
                        <input type="number" step="0.01" name="tm_area_ha" id="tm_area_ha"
                            value="{{ old('tm_area_ha') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required>
                    </div>
                    @error('tm_area_ha')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error_outline</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan
                </button>
                <a href="{{ route('admin.afdelings.index') }}"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
