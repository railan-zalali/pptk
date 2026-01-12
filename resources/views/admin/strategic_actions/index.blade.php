@extends('layouts.admin')

@section('title', 'Kelola Aksi Strategis')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Aksi Strategis</h3>
        <a href="{{ route('admin.strategic-actions.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Aksi</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kebun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Utama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($actions as $action)
                        <tr>
                            <td class="px-6 py-4">{{ $action->year }}</td>
                            <td class="px-6 py-4">{{ $action->garden->kebun_name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $action->action_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @switch($action->action_type)
                                    @case('Pemupukan Akar')
                                        Dosis N: {{ $action->dosis_n_kg_ha }} kg/ha<br>
                                        Freq: {{ $action->application_frequency }}x
                                    @break

                                    @case('Pemupukan Daun')
                                        Target: {{ $action->coverage_target_percent }}%<br>
                                        Interval: {{ $action->application_interval }}
                                    @break

                                    @case('Penyiangan Gulma')
                                        Rotasi: {{ $action->rotation_per_year }}x/thn<br>
                                        Metode: {{ $action->method }}
                                    @break

                                    @case('Kultivator / Pengolahan Tanah')
                                        Target: {{ $action->coverage_target_percent }}%<br>
                                        Fokus: {{ $action->focus_area }}
                                    @break

                                    @case('Pemetikan')
                                        Sistem: {{ $action->picking_system }}<br>
                                        Konsistensi: {{ $action->cushion_consistency }}
                                    @break

                                    @case('Mesin Petik')
                                        Total: {{ $action->total_machine }} unit<br>
                                        Umur Rata2: {{ $action->avg_machine_age }} thn
                                    @break

                                    @case('Pengendalian OPT')
                                        Status: {{ $action->opt_status }}<br>
                                        Normalisasi: {{ $action->tp_normalization ? 'Ya' : 'Tidak' }}
                                    @break

                                    @default
                                        -
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.strategic-actions.edit', $action) }}"
                                    class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                                <form action="{{ route('admin.strategic-actions.destroy', $action) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Hapus aksi strategis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data aksi strategis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $actions->links() }}
        </div>
    @endsection
