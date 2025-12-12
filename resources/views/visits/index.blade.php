@extends('layouts.pptk')

@section('title', 'Kunjungan Dinas - Timeline')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 mb-2">Kunjungan Dinas</h1>
            <p class="text-gray-600">Timeline dan dokumentasi kunjungan ke kebun model teh</p>
        </div>
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="{{ route('visits.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kunjungan Baru
            </a>
            <button onclick="filterVisits()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
            <select id="regionFilter" class="border border-gray-300 rounded-lg px-4 py-2" onchange="filterByRegion()">
                <option value="">Semua Wilayah</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Kunjungan</p>
                        <p class="text-2xl font-bold text-green-600">{{ $totalVisits }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Bulan Ini</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $thisMonthVisits }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Wilayah Dikunjungi</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $visitedRegions }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-map-marked-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Rata-rata Rating</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($avgRating, 1) }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-green-800 mb-6">Timeline Kunjungan</h2>

            @if ($visits->count() > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-green-200"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-8">
                        @foreach ($visits as $visit)
                            <div class="relative flex items-start">
                                <!-- Timeline Dot -->
                                <div
                                    class="absolute left-0 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ $loop->iteration }}
                                </div>

                                <!-- Content -->
                                <div class="ml-12 flex-1">
                                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
                                            <div>
                                                <h3 class="font-bold text-gray-800">{{ $visit->title }}</h3>
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $visit->garden->name }}, {{ $visit->garden->region->name }}
                                                </p>
                                            </div>
                                            <div class="text-right mt-2 md:mt-0">
                                                <div class="text-sm font-semibold text-green-600">
                                                    {{ $visit->visit_date->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $visit->visit_date->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-gray-700 mb-3">
                                            {{ \Illuminate\Support\Str::limit($visit->description, 150) }}</p>

                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                            <span>
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $visit->participants_count }} peserta
                                            </span>
                                            <span>
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $visit->duration }} jam
                                            </span>
                                            <span>
                                                <i class="fas fa-star mr-1"></i>
                                                {{ $visit->rating }}/5
                                            </span>
                                            <span
                                                class="px-2 py-1 bg-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-100 text-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-800 rounded-full text-xs">
                                                {{ ucfirst($visit->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 flex gap-2">
                                            <a href="{{ route('visits.show', $visit) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </a>
                                            @can('update', $visit)
                                                <a href="{{ route('visits.edit', $visit) }}"
                                                    class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                            @endcan
                                            @can('delete', $visit)
                                                <form action="{{ route('visits.destroy', $visit) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kunjungan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
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
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum ada kunjungan</h3>
                    <p class="text-gray-500 mb-4">Tambahkan kunjungan dinas pertama Anda untuk memulai dokumentasi.</p>
                    <a href="{{ route('visits.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Kunjungan
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
