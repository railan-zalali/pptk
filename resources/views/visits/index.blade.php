@extends('layouts.pptk')

@section('title', 'Kunjungan Dinas - Timeline')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">Kunjungan Dinas</h1>
            <p class="text-gray-600 dark:text-gray-300">Timeline dan dokumentasi kunjungan ke kebun model teh</p>
        </div>
        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded flex items-center">
                <span class="material-icons mr-2">check_circle</span>{{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="{{ route('visits.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                <span class="material-icons mr-2">add</span>
                Tambah Kunjungan Baru
            </a>
            <button onclick="filterVisits()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                <span class="material-icons mr-2">filter_list</span>
                Filter
            </button>
            <select id="regionFilter" class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-green-500 focus:border-green-500" onchange="filterByRegion()">
                <option value="">Semua Wilayah</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Total Kunjungan</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalVisits }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full flex items-center justify-center">
                        <span class="material-icons text-green-600 dark:text-green-400 text-xl">event_available</span>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Bulan Ini</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thisMonthVisits }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full flex items-center justify-center">
                        <span class="material-icons text-blue-600 dark:text-blue-400 text-xl">date_range</span>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Wilayah Dikunjungi</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $visitedRegions }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full flex items-center justify-center">
                        <span class="material-icons text-purple-600 dark:text-purple-400 text-xl">map</span>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Rata-rata Rating</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($avgRating, 1) }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full flex items-center justify-center">
                        <span class="material-icons text-yellow-600 dark:text-yellow-400 text-xl">star</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold text-green-800 dark:text-green-100 mb-6">Timeline Kunjungan</h2>

            @if ($visits->count() > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-green-200 dark:bg-green-800"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-8">
                        @foreach ($visits as $visit)
                            <div class="relative flex items-start">
                                <!-- Timeline Dot -->
                                <div
                                    class="absolute left-0 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md z-10">
                                    {{ $loop->iteration }}
                                </div>

                                <!-- Content -->
                                <div class="ml-12 flex-1">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border border-gray-100 dark:border-gray-600">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
                                            <div>
                                                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{ $visit->title }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center mt-1">
                                                    <span class="material-icons text-sm mr-1">location_on</span>
                                                    {{ $visit->garden->name }}, {{ $visit->garden->region->name }}
                                                </p>
                                            </div>
                                            <div class="text-right mt-2 md:mt-0">
                                                <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                    {{ $visit->visit_date->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $visit->visit_date->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-gray-700 dark:text-gray-300 mb-3 leading-relaxed">
                                            {{ \Illuminate\Support\Str::limit($visit->description, 150) }}</p>

                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <span class="material-icons text-sm mr-1">person</span>
                                                {{ $visit->participants_count }} peserta
                                            </span>
                                            <span class="flex items-center">
                                                <span class="material-icons text-sm mr-1">schedule</span>
                                                {{ $visit->duration }} jam
                                            </span>
                                            <span class="flex items-center">
                                                <span class="material-icons text-sm mr-1">star</span>
                                                {{ $visit->rating }}/5
                                            </span>
                                            <span
                                                class="px-2 py-1 bg-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-100 dark:bg-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-900/50 text-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-800 dark:text-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-100 rounded-full text-xs font-medium border border-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-200 dark:border-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-800">
                                                {{ ucfirst($visit->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600 flex gap-2">
                                            <a href="{{ route('visits.show', $visit) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium flex items-center">
                                                <span class="material-icons text-sm mr-1">visibility</span>Detail
                                            </a>
                                            @can('update', $visit)
                                                <a href="{{ route('visits.edit', $visit) }}"
                                                    class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 text-sm font-medium flex items-center">
                                                    <span class="material-icons text-sm mr-1">edit</span>Edit
                                                </a>
                                            @endcan
                                            @can('delete', $visit)
                                                <form action="{{ route('visits.destroy', $visit) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kunjungan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium flex items-center">
                                                        <span class="material-icons text-sm mr-1">delete</span>Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $visits->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                    <span class="material-icons text-6xl text-gray-300 dark:text-gray-500 mb-4">event_busy</span>
                    <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">Belum ada kunjungan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Tambahkan kunjungan dinas pertama Anda untuk memulai dokumentasi.</p>
                    <a href="{{ route('visits.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg inline-flex items-center transition-colors">
                        <span class="material-icons mr-2">add</span>Tambah Kunjungan
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function filterVisits() {
            // Implement filter functionality
            alert('Fitur filter akan segera tersedia');
        }

        function filterByRegion() {
            const regionId = document.getElementById('regionFilter').value;
            if (regionId) {
                window.location.href = `{{ route('visits.index') }}?region=${regionId}`;
            } else {
                window.location.href = `{{ route('visits.index') }}`;
            }
        }

        // Check for region filter in URL
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const regionId = urlParams.get('region');
            if (regionId) {
                document.getElementById('regionFilter').value = regionId;
            }
        });
    </script>
@endpush
