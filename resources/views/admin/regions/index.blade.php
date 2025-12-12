@extends('layouts.admin')

@section('title', 'Kelola Wilayah')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Wilayah</h3>
        <a href="{{ route('admin.regions.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Wilayah</a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provinsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koordinat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($regions as $region)
                    <tr>
                        <td class="px-6 py-4">{{ $region->name }}</td>
                        <td class="px-6 py-4">{{ $region->province }}</td>
                        <td class="px-6 py-4">{{ $region->coordinates }}</td>
                        <td class="px-6 py-4">
                            @if ($region->photo_path)
                                <img src="{{ asset('storage/' . $region->photo_path) }}" alt="Foto Wilayah"
                                    class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-xs text-gray-500">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.regions.edit', $region) }}"
                                class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                            <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus wilayah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $regions->links() }}
    </div>
@endsection
