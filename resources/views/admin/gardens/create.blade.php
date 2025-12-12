@extends('layouts.admin')

@section('title', 'Tambah Kebun')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.gardens.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kebun *</label>
                <input name="name" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('name') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                <input name="location" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('location') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah *</label>
                <select name="region_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Wilayah</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <input name="address" type="text" class="w-full border rounded px-3 py-2" value="{{ old('address') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Luas (ha) *</label>
                <input name="area_hectares" type="number" step="0.01" required class="w-full border rounded px-3 py-2" value="{{ old('area_hectares') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Koordinat (lat,long)</label>
                <input name="coordinates" type="text" class="w-full border rounded px-3 py-2" value="{{ old('coordinates') }}" placeholder="Contoh: -7.1234,107.5678">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input name="latitude" type="number" step="0.000001" class="w-full border rounded px-3 py-2" value="{{ old('latitude') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input name="longitude" type="number" step="0.000001" class="w-full border rounded px-3 py-2" value="{{ old('longitude') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Varietas Teh</label>
                <input name="tea_variety" type="text" class="w-full border rounded px-3 py-2" value="{{ old('tea_variety') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kebun</label>
                <input name="garden_type" type="text" class="w-full border rounded px-3 py-2" value="{{ old('garden_type') }}" placeholder="contoh: perkebunan / penelitian">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">pH Tanah</label>
                <input name="soil_ph" type="number" step="0.1" class="w-full border rounded px-3 py-2" value="{{ old('soil_ph') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tanah</label>
                <input name="soil_type" type="text" class="w-full border rounded px-3 py-2" value="{{ old('soil_type') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.gardens.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection

