@extends('layouts.admin')

@section('title', 'Strategic Action')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Strategic Action</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Data tindakan strategis per kebun.</p>
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Kebun</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Wilayah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Tipe Aksi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Tahun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($strategicActions as $action)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $loop->iteration + ($strategicActions->currentPage() - 1) * $strategicActions->perPage() }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $action->garden->kebun_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $action->garden->region->regional_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $action->action_type ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $action->year }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">Belum ada data strategic action.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($strategicActions->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $strategicActions->links() }}
            </div>
        @endif
    </div>
@endsection
