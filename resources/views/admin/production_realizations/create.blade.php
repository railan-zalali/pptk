@extends('layouts.admin')

@section('title', 'Input Produksi Realisasi')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.production-realizations.index') }}" class="text-green-600 hover:text-green-800 flex items-center mb-4">
                <span class="material-icons mr-1">arrow_back</span> Kembali ke Daftar
            </a>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Form Input Produksi Bulanan</h2>
        </div>

        <form action="{{ route('admin.production-realizations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Afdeling Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="afdeling_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Afdeling</label>
                    <select name="afdeling_id" id="afdeling_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Afdeling</option>
                        @foreach ($afdelings as $afdeling)
                            <option value="{{ $afdeling->id }}" {{ old('afdeling_id') == $afdeling->id ? 'selected' : '' }}>
                                {{ $afdeling->name }} ({{ $afdeling->garden->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('afdeling_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div class="col-span-2 md:col-span-1">
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal/Bulan</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harvested Area -->
                <div>
                    <label for="harvested_area_ha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Luas Petik (Ha)</label>
                    <input type="number" step="0.01" name="harvested_area_ha" id="harvested_area_ha" value="{{ old('harvested_area_ha') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('harvested_area_ha')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Wet Yield -->
                <div>
                    <label for="wet_yield_kg" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produksi Basah (Kg)</label>
                    <input type="number" step="0.01" name="wet_yield_kg" id="wet_yield_kg" value="{{ old('wet_yield_kg') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('wet_yield_kg')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dry Yield -->
                <div>
                    <label for="dry_yield_kg" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rendemen / Prod. Kering (Kg)</label>
                    <input type="number" step="0.01" name="dry_yield_kg" id="dry_yield_kg" value="{{ old('dry_yield_kg') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('dry_yield_kg')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Manpower Count -->
                <div>
                    <label for="manpower_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah HK (Orang)</label>
                    <input type="number" name="manpower_count" id="manpower_count" value="{{ old('manpower_count') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('manpower_count')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Effective Days -->
                <div>
                    <label for="effective_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hari Kerja Efektif</label>
                    <input type="number" name="effective_days" id="effective_days" value="{{ old('effective_days') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('effective_days')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
