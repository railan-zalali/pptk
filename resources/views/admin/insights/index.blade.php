@extends('layouts.admin')

@section('title', 'Kelola Insight')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">lightbulb</span>
            Daftar Insight
        </h3>
        <a href="{{ route('admin.insights.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah Insight
        </a>
    </div>

    <form method="GET"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                    </span>
                    <select name="garden_id"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none transition-colors">
                        <option value="">Semua Kebun</option>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ request('garden_id') == $garden->id ? 'selected' : '' }}>
                                {{ $garden->kebun_name }} • {{ $garden->region->regional_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Level Peringatan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">warning</span>
                    </span>
                    <select name="alert_level"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none transition-colors">
                        <option value="">Semua Level</option>
                        <option value="low" {{ request('alert_level') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('alert_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('alert_level') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>
            <div class="flex items-end">
                <button
                    class="w-full bg-gray-800 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-500 text-white px-4 py-2.5 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-icons-outlined text-sm">filter_list</span>
                    Terapkan Filter
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kebun & Wilayah</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Judul Insight</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Level</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($insights as $insight)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                                    {{ $insight->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $insight->garden->kebun_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-0.5">
                                    <span class="material-icons-outlined text-[10px]">place</span>
                                    {{ $insight->garden->region->regional_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800 dark:text-gray-100">
                                {{ Str::limit($insight->title, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                @if ($insight->alert_level === 'high') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($insight->alert_level === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                    <span class="material-icons-outlined text-[14px]">
                                        @if ($insight->alert_level === 'high')
                                            error
                                        @elseif($insight->alert_level === 'medium')
                                            warning
                                        @else
                                            check_circle
                                        @endif
                                    </span>
                                    {{ ucfirst($insight->alert_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.insights.edit', $insight) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.insights.destroy', $insight) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus insight ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg transition-colors"
                                            title="Hapus">
                                            <span class="material-icons-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <div
                                        class="h-16 w-16 bg-gray-100 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                                        <span
                                            class="material-icons-outlined text-3xl text-gray-400">lightbulb_outline</span>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada insight
                                    </h3>
                                    <p class="text-sm">Mulai dengan menambahkan insight baru untuk kebun.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($insights->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $insights->links() }}
            </div>
        @endif
    </div>
@endsection
