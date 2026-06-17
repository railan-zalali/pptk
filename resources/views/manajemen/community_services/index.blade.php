@extends('layouts.admin')

@section('title', 'Pengabdian Masyarakat')

@section('content')
    <div class="mb-4 flex items-center space-x-2">
        <a href="{{ route('manajemen.penelitian.index') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('manajemen.penelitian.*') ? 'bg-green-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-700' }} shadow transition">
            <span class="material-icons align-middle text-base mr-1">science</span> Data Penelitian
        </a>
        <a href="{{ route('manajemen.community-services.index') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('manajemen.community-services.*') ? 'bg-green-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-700' }} shadow transition">
            <span class="material-icons align-middle text-base mr-1">volunteer_activism</span> Data Pengabdian Masyarakat
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Data Pengabdian Masyarakat</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Data kegiatan pengabdian masyarakat oleh PPTK Gambung.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                <span class="material-icons text-sm mr-1">visibility</span> View Only
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Judul Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($communityServices as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $loop->iteration + ($communityServices->currentPage() - 1) * $communityServices->perPage() }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $service->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                <p class="line-clamp-2">{{ $service->description ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ optional($service->created_at)->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">Belum ada data pengabdian masyarakat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($communityServices->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $communityServices->links() }}
            </div>
        @endif
    </div>
@endsection
