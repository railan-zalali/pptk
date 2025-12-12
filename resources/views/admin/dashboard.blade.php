@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
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
                        <p class="font-medium text-gray-800">{{ $visit->title }}</p>
                        <p class="text-xs text-gray-600">{{ $visit->garden->name }} • {{ $visit->garden->region->name }}</p>
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
                        <p class="font-medium text-gray-800">{{ $garden->name }}</p>
                        <p class="text-xs text-gray-600">{{ $garden->region->name }}</p>
                    </div>
                    <div class="text-sm font-semibold text-green-700">
                        {{ number_format($garden->productionData->avg('productivity') ?? 0, 1) }} kg/ha
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

