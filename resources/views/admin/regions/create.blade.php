@extends('layouts.admin')

@section('title', 'Tambah Wilayah')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.regions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Wilayah *</label>
                <input name="name" type="text" required class="w-full border rounded px-3 py-2" placeholder="Contoh: Jawa Barat" value="{{ old('name') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                <input name="province" type="text" required class="w-full border rounded px-3 py-2" placeholder="Contoh: Jawa Barat" value="{{ old('province') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Koordinat (lat,long)</label>
                <input name="coordinates" type="text" class="w-full border rounded px-3 py-2" placeholder="Contoh: -6.9175,107.6191" value="{{ old('coordinates') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Wilayah</label>
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

