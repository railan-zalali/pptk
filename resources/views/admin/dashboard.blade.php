@extends('layouts.admin')

@section('title', 'Dashboard Admin PPKT')

@section('content')
    {{-- Stats Cards: Master Kebun --}}
    <div class="mb-2">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Master Kebun</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Wilayah</p>
                <p class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $stats['regions'] }}</p>
            </div>
            <span class="material-icons text-4xl text-green-200 dark:text-green-800">map</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Kebun</p>
                <p class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $stats['gardens'] }}</p>
            </div>
            <span class="material-icons text-4xl text-green-200 dark:text-green-800">park</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Afdeling</p>
                <p class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $stats['afdelings'] }}</p>
            </div>
            <span class="material-icons text-4xl text-green-200 dark:text-green-800">grid_view</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Blok</p>
                <p class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $stats['blocks'] }}</p>
            </div>
            <span class="material-icons text-4xl text-green-200 dark:text-green-800">crop_square</span>
        </div>
    </div>

    {{-- Stats Cards: Produksi --}}
    <div class="mb-2">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Produksi & Kinerja</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Produksi (kg)</p>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">
                    {{ number_format($stats['production_total'], 0, ',', '.') }} kg
                </p>
            </div>
            <span class="material-icons text-4xl text-blue-200 dark:text-blue-800">precision_manufacturing</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Rata-rata Produktivitas</p>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">
                    {{ number_format($stats['productivity_avg'], 1, ',', '.') }} kg/ha
                </p>
            </div>
            <span class="material-icons text-4xl text-blue-200 dark:text-blue-800">trending_up</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Target Kinerja ({{ date('Y') }})</p>
                <p class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">{{ $stats['targets'] }}</p>
            </div>
            <span class="material-icons text-4xl text-indigo-200 dark:text-indigo-800">track_changes</span>
        </div>
    </div>

    {{-- Data Completeness --}}
    <div class="mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Status Kelengkapan Data</h3>
            <div class="mb-4">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Skor Kelengkapan</span>
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-400">{{ number_format($completenessScore, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-700" style="width: {{ $completenessScore }}%"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Missing Targets -->
                <div class="border rounded-md p-4 {{ $missingTargetGardens->count() > 0 ? 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800' : 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' }}">
                    <h4 class="font-medium {{ $missingTargetGardens->count() > 0 ? 'text-red-800 dark:text-red-300' : 'text-green-800 dark:text-green-300' }} mb-2">
                        Target Protas (Tahun Ini)
                    </h4>
                    @if ($missingTargetGardens->count() > 0)
                        <p class="text-sm text-red-600 dark:text-red-400 mb-2">Belum diinput untuk {{ $missingTargetGardens->count() }} kebun:</p>
                        <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-400 max-h-24 overflow-y-auto">
                            @foreach ($missingTargetGardens as $garden)
                                <li>{{ $garden->kebun_name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-green-600 dark:text-green-400">Semua kebun memiliki target.</p>
                    @endif
                </div>

                <!-- Missing Realization -->
                <div class="border rounded-md p-4 {{ $missingRealizationGardens->count() > 0 ? 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800' : 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' }}">
                    <h4 class="font-medium {{ $missingRealizationGardens->count() > 0 ? 'text-yellow-800 dark:text-yellow-300' : 'text-green-800 dark:text-green-300' }} mb-2">
                        Realisasi Produksi (Bulan Ini)
                    </h4>
                    @if ($missingRealizationGardens->count() > 0)
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mb-2">Belum diinput untuk {{ $missingRealizationGardens->count() }} kebun:</p>
                        <ul class="list-disc list-inside text-xs text-yellow-600 dark:text-yellow-400 max-h-24 overflow-y-auto">
                            @foreach ($missingRealizationGardens as $garden)
                                <li>{{ $garden->kebun_name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-green-600 dark:text-green-400">Semua kebun telah lapor bulan ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Realisasi Terbaru --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Realisasi Produksi Terbaru</h3>
                <a href="{{ route('admin.production-realizations.index') }}"
                    class="text-sm text-green-700 dark:text-green-400 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentRealizations as $realization)
                    <div class="flex items-center justify-between border-b dark:border-gray-700 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $realization->garden->kebun_name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::createFromDate($realization->year, $realization->month, 1)->translatedFormat('F Y') }}
                            </p>
                        </div>
                        <div class="text-sm font-semibold text-blue-700 dark:text-blue-400">
                            {{ number_format($realization->wet_production_kg ?? 0, 0, ',', '.') }} kg
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data realisasi.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Gardens --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Top Kebun (Produktivitas)</h3>
                <a href="{{ route('admin.gardens.index') }}"
                    class="text-sm text-green-700 dark:text-green-400 hover:underline">Kelola</a>
            </div>
            <div class="space-y-3">
                @forelse ($topGardens as $garden)
                    <div class="flex items-center justify-between border-b dark:border-gray-700 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $garden->kebun_name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $garden->region->regional_name ?? '-' }}</p>
                        </div>
                        <div class="text-sm font-semibold text-green-700 dark:text-green-400">
                            {{ number_format($garden->calculated_productivity ?? 0, 2) }} kg/ha
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data kebun.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
