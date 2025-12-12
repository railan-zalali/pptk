@extends('layouts.admin')

@section('title', 'Edit Kebun')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.gardens.update', $garden) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kebun *</label>
                <input name="name" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('name', $garden->name) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                <input name="location" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('location', $garden->location) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah *</label>
                <select name="region_id" required class="w-full border rounded px-3 py-2">
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id', $garden->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <input name="address" type="text" class="w-full border rounded px-3 py-2" value="{{ old('address', $garden->address) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Luas (ha) *</label>
                <input name="area_hectares" type="number" step="0.01" required class="w-full border rounded px-3 py-2" value="{{ old('area_hectares', $garden->area_hectares) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Koordinat (lat,long)</label>
                <input name="coordinates" type="text" class="w-full border rounded px-3 py-2" value="{{ old('coordinates', $garden->coordinates) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input name="latitude" type="number" step="0.000001" class="w-full border rounded px-3 py-2" value="{{ old('latitude', $garden->latitude) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input name="longitude" type="number" step="0.000001" class="w-full border rounded px-3 py-2" value="{{ old('longitude', $garden->longitude) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Varietas Teh</label>
                <input name="tea_variety" type="text" class="w-full border rounded px-3 py-2" value="{{ old('tea_variety', $garden->tea_variety) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kebun</label>
                <input name="garden_type" type="text" class="w-full border rounded px-3 py-2" value="{{ old('garden_type', $garden->garden_type) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <input name="status" type="text" class="w-full border rounded px-3 py-2" value="{{ old('status', $garden->status) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $garden->description) }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.gardens.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection

