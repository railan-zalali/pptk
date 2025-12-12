@extends('layouts.admin')

@section('title', 'Kelola Kebun')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Kebun</h3>
        <a href="{{ route('admin.gardens.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Kebun</a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wilayah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Luas (ha)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koordinat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($gardens as $garden)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $garden->name }}</div>
                            <div class="text-xs text-gray-500">{{ $garden->tea_variety }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $garden->region->name }}</td>
                        <td class="px-6 py-4">{{ number_format($garden->area_hectares, 1) }}</td>
                        <td class="px-6 py-4">{{ $garden->coordinates }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 rounded text-xs bg-{{ $garden->status === 'active' ? 'green' : 'gray' }}-100 text-{{ $garden->status === 'active' ? 'green' : 'gray' }}-800">{{ $garden->status ?? 'n/a' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.gardens.edit', $garden) }}"
                                class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                            <form action="{{ route('admin.gardens.destroy', $garden) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus kebun ini?')">
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
        {{ $gardens->links() }}
    </div>
@endsection
