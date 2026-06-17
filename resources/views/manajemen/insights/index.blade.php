@extends('layouts.admin')

@section('title', 'Insight')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Insight</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Insight dan analisis kebun model.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                <span class="material-icons text-sm mr-1">visibility</span> View Only
            </span>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($insights as $insight)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ $insight->title ?? 'Insight' }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $insight->description ?? $insight->content ?? '-' }}</p>
                        </div>
                        <p class="text-xs text-gray-400 ml-4 whitespace-nowrap">{{ optional($insight->created_at)->format('d M Y') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-gray-400">Belum ada data insight.</div>
            @endforelse
        </div>
        @if ($insights->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $insights->links() }}
            </div>
        @endif
    </div>
@endsection
