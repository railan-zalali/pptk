@extends('layouts.admin')

@section('title', 'Penelitian')

@section('content')
    <div class="mb-4 flex items-center space-x-2">
        {{-- Tab navigasi: Penelitian / Pengabdian --}}
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
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Data Penelitian</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Informasi penelitian yang dilakukan di kebun model PPTK Gambung.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                <span class="material-icons text-sm mr-1">visibility</span> View Only
            </span>
        </div>
        <div class="p-6">
            @if ($page && $page->content)
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                    {!! $page->content !!}
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-icons text-5xl text-gray-300 dark:text-gray-600">science</span>
                    <p class="mt-4 text-gray-400 dark:text-gray-500">Belum ada data penelitian yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
