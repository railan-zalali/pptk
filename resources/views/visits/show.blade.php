@extends('layouts.pptk')

@section('title', 'Detail Kunjungan Dinas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">Detail Kunjungan</h1>
            <p class="text-gray-600 dark:text-gray-300">Informasi lengkap kunjungan dinas ke kebun model</p>
        </div>

        <div
            class="pptk-card bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8 border border-gray-100 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">{{ $visit->title }}</h2>
                    <div class="space-y-3">
                        <p class="text-gray-700 dark:text-gray-300 flex items-center">
                            <span class="material-icons text-green-600 dark:text-green-400 mr-3 w-6">location_on</span>
                            <span>{{ $visit->garden->kebun_name }}, {{ $visit->garden->region->regional_name }}</span>
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 flex items-center">
                            <span class="material-icons text-green-600 dark:text-green-400 mr-3 w-6">calendar_today</span>
                            <span>{{ $visit->visit_date->format('d M Y') }}</span>
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 flex items-center">
                            <span class="material-icons text-green-600 dark:text-green-400 mr-3 w-6">schedule</span>
                            <span>{{ $visit->duration }} jam</span>
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 flex items-center">
                            <span class="material-icons text-green-600 dark:text-green-400 mr-3 w-6">group</span>
                            <span>{{ $visit->participants_count }} peserta</span>
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 flex items-center">
                            <span class="material-icons text-yellow-500 mr-3 w-6">star</span>
                            <span>{{ $visit->rating }}/5</span>
                        </p>
                    </div>

                    <div class="mt-6">
                        @php
                            $statusColors = [
                                'scheduled' =>
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 border-blue-200 dark:border-blue-800',
                                'completed' =>
                                    'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 border-green-200 dark:border-green-800',
                                'cancelled' =>
                                    'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 border-red-200 dark:border-red-800',
                            ];
                            $statusClass =
                                $statusColors[$visit->status] ??
                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusClass }}">
                            {{ ucfirst($visit->status) }}
                        </span>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                        <span class="material-icons text-gray-500 dark:text-gray-400 mr-2">description</span>
                        Deskripsi
                    </h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $visit->description }}</p>

                    @if ($visit->objectives)
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-2">Tujuan</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $visit->objectives }}</p>
                    @endif

                    @if ($visit->findings)
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-2">Temuan</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $visit->findings }}</p>
                    @endif

                    @if ($visit->recommendations)
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4 mb-2">Rekomendasi</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $visit->recommendations }}</p>
                    @endif
                </div>
            </div>

            @if ($visit->photos->count() > 0)
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                        <span class="material-icons text-green-600 dark:text-green-400 mr-2">photo_library</span>
                        Dokumentasi Foto
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($visit->photos as $photo)
                            <div class="relative group rounded-lg overflow-hidden shadow-md bg-gray-100 dark:bg-gray-700">
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->caption }}"
                                    loading="lazy"
                                    class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-300">
                                @if ($photo->caption)
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-black/70 backdrop-blur-sm text-white text-xs p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $photo->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="flex gap-3">
            <a href="{{ route('visits.index') }}"
                class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                <span class="material-icons text-sm">arrow_back</span>
                Kembali
            </a>
            @auth
                @if (Auth::user()->role === 'admin_pptk')
                    <a href="{{ route('admin.visits.edit', $visit) }}"
                        class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                        <span class="material-icons text-sm">edit</span>
                        Edit
                    </a>
                @elseif (Auth::user()->role === 'manajemen')
                    <a href="{{ route('manajemen.visits.edit', $visit) }}"
                        class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                        <span class="material-icons text-sm">edit</span>
                        Edit
                    </a>
                @endif
            @endauth
        </div>
    </div>
@endsection
