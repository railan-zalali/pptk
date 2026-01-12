@extends('layouts.admin')

@section('title', 'Kelola Produksi & Realisasi')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Data Realisasi Produksi & Monitoring</h3>
        <a href="{{ route('admin.production-realizations.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Data</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kebun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Luas Efektif (Ha)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prod. Basah (Kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas Pemetikan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Forecast</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($productions as $production)
                        <tr>
                            <td class="px-6 py-4">
                                {{ DateTime::createFromFormat('!m', $production->month)->format('F') }}
                                {{ $production->year }}
                            </td>
                            <td class="px-6 py-4">{{ $production->garden->kebun_name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ number_format($production->active_picking_area_ha, 2) }}</td>
                            <td class="px-6 py-4">{{ number_format($production->wet_production_kg, 0) }}</td>
                            <td class="px-6 py-4 text-xs">
                                Per Ha: {{ $production->capacity_per_ha }}<br>
                                Avg: {{ $production->avg_capacity }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                Est: {{ number_format($production->estimated_production, 0) }}<br>
                                Note: {{ Str::limit($production->assumption_note, 10) }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.production-realizations.edit', $production) }}"
                                    class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                                <form action="{{ route('admin.production-realizations.destroy', $production) }}"
                                    method="POST" class="inline" onsubmit="return confirm('Hapus data realisasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data realisasi
                                produksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $productions->links() }}
    </div>
@endsection
