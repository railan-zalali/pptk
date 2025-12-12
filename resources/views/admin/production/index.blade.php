@extends('layouts.admin')

@section('title', 'Kelola Data Produksi')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Data Produksi</h3>
        <a href="{{ route('admin.production.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Data</a>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kebun</label>
                <select name="garden_id" class="w-full border rounded px-3 py-2">
                    <option value="">Semua Kebun</option>
                    @foreach ($gardens as $garden)
                        <option value="{{ $garden->id }}"
                            {{ request('garden_id') == $garden->id ? 'selected' : '' }}>
                            {{ $garden->name }} • {{ $garden->region->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <input type="number" name="month" min="1" max="12" class="w-full border rounded px-3 py-2"
                       value="{{ request('month') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <input type="number" name="year" min="2000" max="2100" class="w-full border rounded px-3 py-2"
                       value="{{ request('year') }}">
            </div>
            <div class="flex items-end">
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded w-full">Filter</button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kebun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produksi (kg)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produktivitas (kg/ha)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mutu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RKAP (%)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($records as $record)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $record->record_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $record->garden->name }} • {{ $record->garden->region->name }}
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                        {{ number_format($record->production ?? 0, 0) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ number_format($record->productivity ?? 0, 1) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ number_format($record->quality_score ?? 0, 1) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ number_format($record->rkap_percentage ?? 0, 1) }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.production.edit', $record) }}"
                           class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                        <form action="{{ route('admin.production.destroy', $record) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus data ini?')">
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
        {{ $records->links() }}
    </div>
@endsection

