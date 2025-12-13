@extends('layouts.pptk')

@section('title', 'Detail Kunjungan Dinas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">Detail Kunjungan</h1>
        <p class="text-gray-600 dark:text-gray-300">Informasi lengkap kunjungan dinas ke kebun model</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ $visit->title }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $visit->garden->name }}, {{ $visit->garden->region->name }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-calendar-day mr-2"></i>{{ $visit->visit_date->format('d M Y') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-clock mr-2"></i>{{ $visit->duration }} jam
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-user mr-2"></i>{{ $visit->participants_count }} peserta
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-star mr-2"></i>{{ $visit->rating }}/5
                </p>
                <span class="inline-block px-3 py-1 rounded-full text-xs bg-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-100 dark:bg-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-900/50 text-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-800 dark:text-{{ $visit->status == 'completed' ? 'green' : ($visit->status == 'scheduled' ? 'blue' : 'yellow') }}-100">
                    {{ ucfirst($visit->status) }}
                </span>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Deskripsi</h3>
                <p class="text-gray-700 dark:text-gray-300">{{ $visit->description }}</p>
                @if($visit->objectives)
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-2">Tujuan</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $visit->objectives }}</p>
                @endif
                @if($visit->findings)
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-2">Temuan</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $visit->findings }}</p>
                @endif
                @if($visit->recommendations)
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-2">Rekomendasi</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $visit->recommendations }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('visits.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
            Kembali
        </a>
        @can('update', $visit)
            <a href="{{ route('visits.edit', $visit) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2 rounded-lg">
                Edit
            </a>
        @endcan
    </div>
</div>
@endsection
