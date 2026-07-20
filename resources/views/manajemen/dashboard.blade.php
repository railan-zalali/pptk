@extends('layouts.admin')

@section('title', 'Dashboard Manajemen')

@section('content')
    {{-- Welcome Banner --}}
    <div class="mb-6 bg-gradient-to-r from-green-700 to-green-500 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-1">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-green-100 text-sm">Dashboard Manajemen — Data bersifat informatif dan hanya dapat dibaca.</p>
            </div>
            <span class="material-icons text-6xl text-green-300 opacity-50">eco</span>
        </div>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center text-center">
            <span class="material-icons text-3xl text-green-500 mb-2">list_alt</span>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['programs'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Program</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center text-center">
            <span class="material-icons text-3xl text-blue-500 mb-2">agriculture</span>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['strategic_actions'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Strategic Action</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center text-center">
            <span class="material-icons text-3xl text-orange-500 mb-2">event</span>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['visits'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Kunjungan</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center text-center">
            <span class="material-icons text-3xl text-pink-500 mb-2">volunteer_activism</span>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['community_services'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Pengabdian</p>
        </div>
    </div>

    {{-- Grid 2 Kolom: Program & Strategic Action --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Program Terbaru --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <span class="material-icons mr-2 text-green-500">list_alt</span> Ringkasan Program
                </h3>
                <a href="{{ route('manajemen.programs.index') }}"
                    class="text-sm text-green-600 dark:text-green-400 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentPrograms as $program)
                    <div class="flex items-center justify-between border-b dark:border-gray-700 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $program->program_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $program->program_type }} • {{ $program->year }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $program->status ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $program->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada program.</p>
                @endforelse
            </div>
        </div>

        {{-- Strategic Action Terbaru --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <span class="material-icons mr-2 text-blue-500">agriculture</span> Strategic Action
                </h3>
                <a href="{{ route('manajemen.strategic-actions.index') }}"
                    class="text-sm text-green-600 dark:text-green-400 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentStrategicActions as $action)
                    <div class="flex items-center justify-between border-b dark:border-gray-700 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $action->action_type ?? '-' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $action->garden->kebun_name ?? '-' }} • {{ $action->year }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada strategic action.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Grid 2 Kolom: Kunjungan & Pengabdian --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Kunjungan Terbaru --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <span class="material-icons mr-2 text-orange-500">event</span> Kunjungan Terbaru
                </h3>
                <a href="{{ route('manajemen.visits.index') }}"
                    class="text-sm text-green-600 dark:text-green-400 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentVisits as $visit)
                    <div class="flex items-center justify-between border-b dark:border-gray-700 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $visit->title ?? 'Kunjungan' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $visit->garden->kebun_name ?? '-' }}</p>
                        </div>
                        <p class="text-xs text-gray-400">{{ optional($visit->visit_date)->format('d M Y') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada kunjungan.</p>
                @endforelse
            </div>
        </div>

        {{-- Pengabdian Masyarakat Terbaru --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <span class="material-icons mr-2 text-pink-500">volunteer_activism</span> Pengabdian Masyarakat
                </h3>
                <a href="{{ route('manajemen.community-services.index') }}"
                    class="text-sm text-green-600 dark:text-green-400 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentCommunityServices as $service)
                    <div class="flex items-center justify-between border-b dark:border-gray-700 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $service->activity_name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($service->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data pengabdian.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
