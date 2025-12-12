@extends('layouts.admin')

@section('title', 'Edit Wilayah')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.regions.update', $region) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Wilayah *</label>
                <input name="name" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('name', $region->name) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                <input name="province" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('province', $region->province) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Koordinat (lat,long)</label>
                <input name="coordinates" type="text" class="w-full border rounded px-3 py-2" value="{{ old('coordinates', $region->coordinates) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Wilayah</label>
                @if($region->photo_path)
                    <img src="{{ asset('storage/'.$region->photo_path) }}" alt="Foto Wilayah" class="w-24 h-24 object-cover rounded mb-2">
                @endif
                <input name="photo" type="file" accept="image/*" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.regions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection

