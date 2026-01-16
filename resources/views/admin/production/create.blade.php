@extends('layouts.admin')

@section('title', 'Tambah Data Produksi')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 transition-all duration-300">
        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Data Produksi
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi informasi data produksi harian.</p>
        </div>

        <form action="{{ route('admin.production.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Garden -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="garden_id" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Kebun</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}" {{ old('garden_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->kebun_name }} • {{ $garden->region->regional_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Record Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Catatan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input name="record_date" type="date" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('record_date') }}">
                    </div>
                </div>

                <!-- Production -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Produksi Kering
                        (kg)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">inventory_2</span>
                        </span>
                        <input name="production" type="number" step="0.01"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('production') }}" placeholder="0.00">
                    </div>
                </div>

                <!-- Productivity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Produktivitas
                        (kg/ha)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">trending_up</span>
                        </span>
                        <input name="productivity" type="number" step="0.01"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('productivity') }}" placeholder="0.00">
                    </div>
                </div>

                <!-- RKAP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">RKAP (%)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">pie_chart</span>
                        </span>
                        <input name="rkap_percentage" type="number" step="0.01"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('rkap_percentage') }}" placeholder="0.00">
                    </div>
                </div>

                <!-- Wet Production -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Produksi Basah
                        (kg)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">water_drop</span>
                        </span>
                        <input name="wet_production_kg" type="number" step="0.01"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('wet_production_kg') }}" placeholder="0.00">
                    </div>
                </div>

                <!-- Quality Score -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mutu Pucuk</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">grade</span>
                        </span>
                        <input name="quality_score" type="number" step="0.1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('quality_score') }}" placeholder="0.0">
                    </div>
                </div>

                <!-- Weather -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Cuaca</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">wb_sunny</span>
                        </span>
                        <input name="weather_condition" type="text"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('weather_condition') }}" placeholder="Cerah/Hujan">
                    </div>
                </div>

                <!-- Temperature -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Suhu Rata-rata
                        (°C)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">thermostat</span>
                        </span>
                        <input name="temperature_avg" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('temperature_avg') }}" placeholder="25">
                    </div>
                </div>

                <!-- Rainfall -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Curah Hujan
                        (mm)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">cloud</span>
                        </span>
                        <input name="rainfall_mm" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('rainfall_mm') }}" placeholder="0">
                    </div>
                </div>

                <!-- Humidity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kelembaban
                        (%)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">opacity</span>
                        </span>
                        <input name="humidity_percent" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('humidity_percent') }}" placeholder="80">
                    </div>
                </div>

                <!-- Soil Moisture -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kadar Air Tanah
                        (%)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">grass</span>
                        </span>
                        <input name="soil_moisture_percent" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('soil_moisture_percent') }}" placeholder="60">
                    </div>
                </div>

                <!-- Pest -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Insiden Hama</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">bug_report</span>
                        </span>
                        <input name="pest_incidence" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('pest_incidence') }}" placeholder="0">
                    </div>
                </div>

                <!-- Disease -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Insiden
                        Penyakit</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">coronavirus</span>
                        </span>
                        <input name="disease_incidence" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('disease_incidence') }}" placeholder="0">
                    </div>
                </div>

                <!-- Fertilizer -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pupuk (kg)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">science</span>
                        </span>
                        <input name="fertilizer_used" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('fertilizer_used') }}" placeholder="0">
                    </div>
                </div>

                <!-- Labor Hours -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam Kerja</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">schedule</span>
                        </span>
                        <input name="labor_hours" type="number" step="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('labor_hours') }}" placeholder="0">
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                    <div class="relative">
                        <span class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">note</span>
                        </span>
                        <textarea name="notes" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('admin.production.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-lg">arrow_back</span>
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all shadow-lg hover:shadow-green-500/30 flex items-center gap-2">
                    <span class="material-icons-outlined text-lg">save</span>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
