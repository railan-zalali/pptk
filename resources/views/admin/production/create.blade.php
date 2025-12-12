@extends('layouts.admin')

@section('title', 'Tambah Data Produksi')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.production.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kebun *</label>
                <select name="garden_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Kebun</option>
                    @foreach ($gardens as $garden)
                        <option value="{{ $garden->id }}">{{ $garden->name }} • {{ $garden->region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Catatan *</label>
                <input name="record_date" type="date" required class="w-full border rounded px-3 py-2" value="{{ old('record_date') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produksi Kering (kg)</label>
                <input name="production" type="number" step="0.01" class="w-full border rounded px-3 py-2" value="{{ old('production') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produktivitas (kg/ha)</label>
                <input name="productivity" type="number" step="0.01" class="w-full border rounded px-3 py-2" value="{{ old('productivity') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">RKAP (%)</label>
                <input name="rkap_percentage" type="number" step="0.01" class="w-full border rounded px-3 py-2" value="{{ old('rkap_percentage') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produksi Basah (kg)</label>
                <input name="wet_production_kg" type="number" step="0.01" class="w-full border rounded px-3 py-2" value="{{ old('wet_production_kg') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mutu Pucuk</label>
                <input name="quality_score" type="number" step="0.1" class="w-full border rounded px-3 py-2" value="{{ old('quality_score') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cuaca</label>
                <input name="weather_condition" type="text" class="w-full border rounded px-3 py-2" value="{{ old('weather_condition') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Suhu Rata-rata (°C)</label>
                <input name="temperature_avg" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('temperature_avg') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Curah Hujan (mm)</label>
                <input name="rainfall_mm" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('rainfall_mm') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelembaban (%)</label>
                <input name="humidity_percent" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('humidity_percent') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kadar Air Tanah (%)</label>
                <input name="soil_moisture_percent" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('soil_moisture_percent') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Insiden Hama</label>
                <input name="pest_incidence" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('pest_incidence') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Insiden Penyakit</label>
                <input name="disease_incidence" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('disease_incidence') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pupuk (kg)</label>
                <input name="fertilizer_used" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('fertilizer_used') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Kerja</label>
                <input name="labor_hours" type="number" step="1" class="w-full border rounded px-3 py-2" value="{{ old('labor_hours') }}">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.production.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection

