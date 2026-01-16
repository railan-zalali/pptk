@extends('layouts.admin')

@section('title', 'Kelola Data Produksi')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">inventory_2</span>
            Data Produksi
        </h3>
        <a href="{{ route('admin.production.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah Data
        </a>
    </div>

    <form method="GET"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                    </span>
                    <select name="garden_id"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none transition-colors">
                        <option value="">Semua Kebun</option>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ request('garden_id') == $garden->id ? 'selected' : '' }}>
                                {{ $garden->kebun_name }} • {{ $garden->region->regional_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">calendar_month</span>
                    </span>
                    <input type="number" name="month" min="1" max="12"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                        value="{{ request('month') }}" placeholder="1-12">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                    </span>
                    <input type="number" name="year" min="2000" max="2100"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                        value="{{ request('year') }}" placeholder="Tahun">
                </div>
            </div>
            <div class="flex items-end">
                <button
                    class="w-full bg-gray-800 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-500 text-white px-4 py-2.5 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-icons-outlined text-sm">filter_list</span>
                    Filter
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kebun</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Produksi (kg)</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Produktivitas (kg/ha)</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Mutu</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            RKAP (%)</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($records as $record)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $record->record_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ $record->garden->kebun_name }} <span class="text-gray-400 mx-1">•</span>
                                {{ $record->garden->region->regional_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($record->production ?? 0, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($record->productivity ?? 0, 1) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($record->quality_score ?? 0, 1) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($record->rkap_percentage ?? 0, 1) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.production.edit', $record) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.production.destroy', $record) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus data ini?')">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <div
                                        class="h-16 w-16 bg-gray-100 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-icons-outlined text-3xl text-gray-400">inventory_2</span>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada data
                                        produksi</h3>
                                    <p class="text-sm">Mulai dengan menambahkan data produksi baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($records->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $records->links() }}
            </div>
        @endif
    </div>
@endsection
