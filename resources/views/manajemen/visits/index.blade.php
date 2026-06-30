@extends('layouts.admin')

@section('title', 'Kelola Kunjungan Dinas')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">tour</span>
                Kunjungan Dinas
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">Kelola jadwal dan laporan kunjungan lapangan.</p>
        </div>
        <a href="{{ route('manajemen.visits.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah Kunjungan
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="garden_id"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Semua Kebun</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}"
                                    {{ request('garden_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->name }} • {{ $garden->region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">flag</span>
                        </span>
                        <select name="status"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Semua Status</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                    </div>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit"
                        class="w-full bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                        <span class="material-icons-outlined text-sm">filter_list</span>
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Judul</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kebun</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Peserta</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Rating</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($visits as $visit)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">event</span>
                                    {{ $visit->visit_date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $visit->title }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <div>{{ $visit->garden->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $visit->garden->region->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-gray-400 text-sm">group</span>
                                    {{ $visit->participants_count }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-yellow-400 text-sm">
                                    <span
                                        class="font-medium text-gray-700 dark:text-gray-300 mr-1">{{ $visit->rating }}</span>
                                    <span class="material-icons text-[16px]">star</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'scheduled' =>
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                        'completed' =>
                                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800',
                                        'cancelled' =>
                                            'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800',
                                    ];
                                    $statusLabels = [
                                        'scheduled' => 'Dijadwalkan',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClasses[$visit->status] }}">
                                    {{ $statusLabels[$visit->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('manajemen.visits.edit', $visit) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('manajemen.visits.destroy', $visit) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus kunjungan ini?')">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span
                                        class="material-icons-outlined text-4xl mb-2 text-gray-300 dark:text-gray-600">event_busy</span>
                                    <p>Belum ada data kunjungan yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($visits->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
@endsection
