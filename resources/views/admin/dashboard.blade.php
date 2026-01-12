@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Data Completeness & Warnings -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Kelengkapan Data</h3>

            <div class="mb-4">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Skor Kelengkapan</span>
                    <span class="text-sm font-medium text-blue-700">{{ number_format($completenessScore, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $completenessScore }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Missing Targets -->
                <div
                    class="border rounded-md p-4 {{ $missingTargetGardens->count() > 0 ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' }}">
                    <h4
                        class="font-medium {{ $missingTargetGardens->count() > 0 ? 'text-red-800' : 'text-green-800' }} mb-2">
                        Target Protas (Tahun Ini)
                    </h4>
                    @if ($missingTargetGardens->count() > 0)
                        <p class="text-sm text-red-600 mb-2">Belum diinput untuk {{ $missingTargetGardens->count() }} kebun:
                        </p>
                        <ul class="list-disc list-inside text-xs text-red-600 max-h-24 overflow-y-auto">
                            @foreach ($missingTargetGardens as $garden)
                                <li>{{ $garden->kebun_name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-green-600">Semua kebun memiliki target.</p>
                    @endif
                </div>

                <!-- Missing Realization -->
                <div
                    class="border rounded-md p-4 {{ $missingRealizationGardens->count() > 0 ? 'bg-yellow-50 border-yellow-200' : 'bg-green-50 border-green-200' }}">
                    <h4
                        class="font-medium {{ $missingRealizationGardens->count() > 0 ? 'text-yellow-800' : 'text-green-800' }} mb-2">
                        Realisasi Produksi (Bulan Ini)
                    </h4>
                    @if ($missingRealizationGardens->count() > 0)
                        <p class="text-sm text-yellow-600 mb-2">Belum diinput untuk
                            {{ $missingRealizationGardens->count() }} kebun:</p>
                        <ul class="list-disc list-inside text-xs text-yellow-600 max-h-24 overflow-y-auto">
                            @foreach ($missingRealizationGardens as $garden)
                                <li>{{ $garden->kebun_name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-green-600">Semua kebun telah lapor bulan ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Kebun</p>
            <p class="text-3xl font-bold text-green-700">{{ $stats['gardens'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Wilayah</p>
            <p class="text-3xl font-bold text-green-700">{{ $stats['regions'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Kunjungan</p>
            <p class="text-3xl font-bold text-green-700">{{ $stats['visits'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Produksi</p>
            <p class="text-3xl font-bold text-blue-700">{{ number_format($stats['production_total'], 0) }} kg</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Rata-rata Produktivitas</p>
            <p class="text-3xl font-bold text-blue-700">{{ number_format($stats['productivity_avg'], 1) }} kg/ha</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Kunjungan Terbaru</h3>
                <a href="{{ route('visits.index') }}" class="text-sm text-green-700">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @foreach ($recentVisits as $visit)
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $visit->title ?? 'Kunjungan' }}</p>
                            <p class="text-xs text-gray-600">{{ $visit->garden->kebun_name }} •
                                {{ $visit->garden->region->regional_name }}</p>
                        </div>
                        <div class="text-sm text-gray-500">{{ $visit->visit_date->format('d M Y') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Top Kebun (Produktivitas)</h3>
                <a href="{{ route('admin.gardens.index') }}" class="text-sm text-green-700">Kelola</a>
            </div>
            <div class="space-y-3">
                @foreach ($topGardens as $garden)
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $garden->kebun_name }}</p>
                            <p class="text-xs text-gray-600">{{ $garden->region->regional_name }}</p>
                        </div>
                        <div class="text-sm font-semibold text-green-700">
                            {{ number_format($garden->calculated_productivity ?? 0, 2) }} kg/ha
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
