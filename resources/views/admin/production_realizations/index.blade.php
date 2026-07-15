@extends('layouts.admin')

@section('title', 'Kelola Produksi & Realisasi')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">assessment</span>
            Data Realisasi Produksi & Monitoring
        </h3>
        <a href="{{ route('admin.production-realizations.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah Data
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Periode</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kebun</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Luas Efektif (Ha)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Prod. Basah (Kg)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Prod. Kering (Kg)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kapasitas Pemetikan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Forecast</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($productions as $production)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                                    {{ DateTime::createFromFormat('!m', $production->month)->format('F') }}
                                    {{ $production->year }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                                    {{ $production->garden->kebun_name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format($production->active_picking_area_ha, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                {{ number_format($production->wet_production_kg, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-600 dark:text-amber-400">
                                {{ $production->dry_production_kg !== null ? number_format($production->dry_production_kg, 0) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col gap-1">
                                    <span>Per Ha: {{ $production->capacity_per_ha }}</span>
                                    <span>Avg: {{ $production->avg_capacity }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col gap-1">
                                    <span>Est: {{ number_format($production->estimated_production, 0) }}</span>
                                    <span title="{{ $production->assumption_note }}">Note:
                                        {{ Str::limit($production->assumption_note, 10) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.production-realizations.edit', $production) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.production-realizations.destroy', $production) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data realisasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg transition-colors"
                                            title="Hapus">
                                            <span class="material-icons-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-icons-outlined text-4xl text-gray-300">inbox</span>
                                    <p class="text-base">Belum ada data realisasi produksi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
        {{ $productions->links() }}
    </div>
@endsection
