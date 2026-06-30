@extends('layouts.admin')

@section('title', 'Kelola Aksi Strategis')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">lightbulb</span>
            Daftar Aksi Strategis
        </h3>
        <a href="{{ route('manajemen.strategic-actions.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah Aksi
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tahun</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kebun</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jenis Aksi</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Detail Utama</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($strategicActions as $action)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">event</span>
                                    {{ $action->year }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                                    {{ $action->garden->kebun_name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 flex items-center gap-1 w-fit">
                                    <span class="material-icons-outlined text-[14px]">
                                        @switch($action->action_type)
                                            @case('Pemupukan Akar')
                                                grass
                                            @break

                                            @case('Pemupukan Daun')
                                                spa
                                            @break

                                            @case('Penyiangan Gulma')
                                                cleaning_services
                                            @break

                                            @case('Kultivator / Pengolahan Tanah')
                                                agriculture
                                            @break

                                            @case('Pemetikan')
                                                touch_app
                                            @break

                                            @case('Mesin Petik')
                                                precision_manufacturing
                                            @break

                                            @case('Pengendalian OPT')
                                                pest_control
                                            @break

                                            @default
                                                category
                                        @endswitch
                                    </span>
                                    {{ $action->action_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                @switch($action->action_type)
                                    @case('Pemupukan Akar')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Dosis N:</span>
                                                {{ $action->dosis_n_kg_ha }} kg/ha</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Freq:</span>
                                                {{ $action->application_frequency }}x</span>
                                        </div>
                                    @break

                                    @case('Pemupukan Daun')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Target:</span>
                                                {{ $action->coverage_target_percent }}%</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Interval:</span>
                                                {{ $action->application_interval }}</span>
                                        </div>
                                    @break

                                    @case('Penyiangan Gulma')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Rotasi:</span>
                                                {{ $action->rotation_per_year }}x/thn</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Metode:</span>
                                                {{ $action->method }}</span>
                                        </div>
                                    @break

                                    @case('Kultivator / Pengolahan Tanah')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Target:</span>
                                                {{ $action->coverage_target_percent }}%</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Fokus:</span>
                                                {{ $action->focus_area }}</span>
                                        </div>
                                    @break

                                    @case('Pemetikan')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Sistem:</span>
                                                {{ $action->picking_system }}</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Konsistensi:</span>
                                                {{ $action->cushion_consistency }}</span>
                                        </div>
                                    @break

                                    @case('Mesin Petik')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Total:</span>
                                                {{ $action->total_machine }} unit</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Umur Rata2:</span>
                                                {{ $action->avg_machine_age }} thn</span>
                                        </div>
                                    @break

                                    @case('Pengendalian OPT')
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1"><span class="font-medium">Status:</span>
                                                {{ $action->opt_status }}</span>
                                            <span class="flex items-center gap-1"><span class="font-medium">Normalisasi:</span>
                                                {{ $action->tp_normalization ? 'Ya' : 'Tidak' }}</span>
                                        </div>
                                    @break

                                    @default
                                        <span class="text-gray-400">-</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('manajemen.strategic-actions.edit', $action) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('manajemen.strategic-actions.destroy', $action) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus aksi strategis ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg transition-colors focus:outline-none"
                                            title="Hapus">
                                            <span class="material-icons-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <span class="material-icons-outlined text-4xl text-gray-300">inbox</span>
                                        <p class="text-base">Belum ada data aksi strategis.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $strategicActions->links() }}
        </div>
    @endsection
