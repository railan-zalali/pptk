@extends('layouts.admin')

@section('title', 'Tambah Realisasi Produksi')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Input Data Realisasi & Monitoring
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">Masukkan data produksi bulanan, luasan efektif, dan
                forecast.</p>
        </div>

        <form action="{{ route('admin.production-realizations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="kebun_id" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('kebun_id') border-red-400 @else border-gray-300 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">-- Pilih Kebun --</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}" {{ old('kebun_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->kebun_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('kebun_id')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_month</span>
                        </span>
                        <select name="month"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    @error('month')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">event</span>
                        </span>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Tahun">
                    </div>
                    @error('year')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Luasan Efektif -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-green-600 text-sm">square_foot</span>
                        Luasan Efektif
                    </h4>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Luas Area Petik
                            (Ha)</label>
                        <input type="number" step="0.01" name="active_picking_area_ha"
                            value="{{ old('active_picking_area_ha') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="0.00">
                        @error('active_picking_area_ha')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <span class="material-icons-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Produksi Basah -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-green-600 text-sm">water_drop</span>
                        Produksi Basah
                    </h4>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Produksi
                            Basah (Kg)</label>
                        <input type="number" step="0.01" name="wet_production_kg"
                            value="{{ old('wet_production_kg') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="0">
                        @error('wet_production_kg')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <span class="material-icons-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Produksi Kering -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-amber-600 text-sm">local_fire_department</span>
                        Produksi Kering
                    </h4>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Produksi
                            Kering (Kg)</label>
                        <input type="number" step="0.01" name="dry_production_kg"
                            value="{{ old('dry_production_kg') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors"
                            placeholder="0">
                        @error('dry_production_kg')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <span class="material-icons-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Kapasitas Pemetikan -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-green-600 text-sm">speed</span>
                        Kapasitas Pemetikan
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kapasitas per
                                Ha</label>
                            <input type="number" step="0.01" name="capacity_per_ha"
                                value="{{ old('capacity_per_ha') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                                placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rata-rata
                                Kapasitas</label>
                            <input type="number" step="0.01" name="avg_capacity" value="{{ old('avg_capacity') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- Forecast -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-green-600 text-sm">trending_up</span>
                        Forecast & Kualitas
                    </h4>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estimasi Produksi
                            (Kg)</label>
                        <input type="number" step="0.01" name="estimated_production"
                            value="{{ old('estimated_production') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="0">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Skor Kualitas (0-10)</label>
                        <input type="number" step="0.1" name="quality_score"
                            value="{{ old('quality_score') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: 8.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Asumsi /
                            Catatan</label>
                        <textarea name="assumption_note" rows="2"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Catatan tambahan...">{{ old('assumption_note') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.production-realizations.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
