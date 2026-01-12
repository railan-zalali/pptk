@extends('layouts.admin')

@section('title', 'Tambah Realisasi Produksi')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Input Data Realisasi & Monitoring</h3>
            <p class="text-sm text-gray-600">Masukkan data produksi bulanan, luasan efektif, dan forecast.</p>
        </div>

        <form action="{{ route('admin.production-realizations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Identitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kebun</label>
                    <select name="kebun_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Kebun --</option>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ old('kebun_id') == $garden->id ? 'selected' : '' }}>
                                {{ $garden->kebun_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('kebun_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="month"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    @error('month')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Luasan Efektif -->
                <div class="bg-gray-50 p-4 rounded-md border">
                    <h4 class="font-medium text-gray-800 mb-3">Luasan Efektif</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Luas Area Petik (Ha)</label>
                        <input type="number" step="0.01" name="active_picking_area_ha"
                            value="{{ old('active_picking_area_ha') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('active_picking_area_ha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Produksi Basah -->
                <div class="bg-gray-50 p-4 rounded-md border">
                    <h4 class="font-medium text-gray-800 mb-3">Produksi Basah</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Produksi Basah (Kg)</label>
                        <input type="number" step="0.01" name="wet_production_kg"
                            value="{{ old('wet_production_kg') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('wet_production_kg')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kapasitas Pemetikan -->
                <div class="bg-gray-50 p-4 rounded-md border">
                    <h4 class="font-medium text-gray-800 mb-3">Kapasitas Pemetikan</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas per Ha</label>
                            <input type="number" step="0.01" name="capacity_per_ha"
                                value="{{ old('capacity_per_ha') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Kapasitas</label>
                            <input type="number" step="0.01" name="avg_capacity" value="{{ old('avg_capacity') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>
                </div>

                <!-- Forecast -->
                <div class="bg-gray-50 p-4 rounded-md border">
                    <h4 class="font-medium text-gray-800 mb-3">Forecast Produksi</h4>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Produksi (Kg)</label>
                        <input type="number" step="0.01" name="estimated_production"
                            value="{{ old('estimated_production') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Asumsi / Catatan</label>
                        <textarea name="assumption_note" rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('assumption_note') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.production-realizations.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
