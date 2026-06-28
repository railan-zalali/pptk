@extends('layouts.admin')

@section('title', 'Detail Aksi Strategis')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('manajemen.strategic-actions.index') }}"
                class="inline-flex items-center text-sm font-semibold text-green-600 dark:text-green-400 hover:underline gap-1">
                <span class="material-icons-outlined text-sm">arrow_back</span>
                Kembali ke Daftar Strategic Action
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <!-- Header Card -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kebun Model</span>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mt-0.5">
                        <span class="material-icons-outlined text-green-600">agriculture</span>
                        {{ $strategicAction->garden->kebun_name ?? '-' }}
                        <span class="text-sm font-normal text-gray-500">({{ $strategicAction->garden->region->regional_name ?? '-' }})</span>
                    </h3>
                </div>
                <div>
                    @php
                        $statusColors = [
                            'planned' => 'blue',
                            'in_progress' => 'yellow',
                            'completed' => 'green'
                        ];
                        $statusLabels = [
                            'planned' => 'Direncanakan',
                            'in_progress' => 'Sedang Berjalan',
                            'completed' => 'Selesai'
                        ];
                        $status = $strategicAction->status ?? 'planned';
                        $color = $statusColors[$status] ?? 'gray';
                        $label = $statusLabels[$status] ?? $status;
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900/30 dark:text-{{ $color }}-400 capitalize">
                        {{ $label }}
                    </span>
                </div>
            </div>

            <!-- Detail Umum -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-500 block uppercase font-bold tracking-wider">Tipe Aksi</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block capitalize flex items-center gap-1">
                            @if($strategicAction->action_type == 'fertilizer_root')
                                <span class="material-icons text-green-600 text-sm">spa</span> Pemupukan Akar
                            @elseif($strategicAction->action_type == 'fertilizer_leaf')
                                <span class="material-icons text-green-600 text-sm">leaf_spark</span> Pemupukan Daun
                            @elseif($strategicAction->action_type == 'weed_control')
                                <span class="material-icons text-green-600 text-sm">grass</span> Pengendalian Gulma
                            @elseif($strategicAction->action_type == 'cultivator')
                                <span class="material-icons text-green-600 text-sm">agriculture</span> Kultivator
                            @elseif($strategicAction->action_type == 'picking')
                                <span class="material-icons text-green-600 text-sm">cut</span> Pemetikan
                            @elseif($strategicAction->action_type == 'machine')
                                <span class="material-icons text-green-600 text-sm">precision_manufacturing</span> Mesin Petik
                            @elseif($strategicAction->action_type == 'opt')
                                <span class="material-icons text-green-600 text-sm">bug_report</span> Pengendalian OPT
                            @else
                                <span class="material-icons text-green-600 text-sm">check_circle</span> {{ str_replace('_', ' ', $strategicAction->action_type) }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-500 block uppercase font-bold tracking-wider">Tahun Pelaksanaan</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">
                            {{ $strategicAction->year ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-500 block uppercase font-bold tracking-wider">Tanggal Realisasi</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">
                            {{ $strategicAction->realization_date ? $strategicAction->realization_date->format('d M Y') : 'Belum terealisasi' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detail Spesifik Tipe Aksi -->
            <div class="p-6">
                <h4 class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Parameter Spesifik Aksi</h4>

                @if($strategicAction->action_type == 'fertilizer_root')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Dosis Target Nitrogen (N)</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->dosis_n_kg_ha ?? '0.00' }} kg/ha</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Dosis Realisasi Nitrogen (N)</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->realized_dosis_n_kg_ha ?? '0.00' }} kg/ha</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Persentase thd Protas</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->n_protas_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Frekuensi & Jenis Pupuk</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">
                                {{ $strategicAction->application_frequency ?? '0' }}x setahun ({{ $strategicAction->fertilizer_type ?? 'N/A' }})
                            </span>
                        </div>
                    </div>
                    @if($strategicAction->technical_note)
                        <div class="mt-4 p-4 bg-yellow-50/50 dark:bg-yellow-900/10 rounded-lg border border-yellow-100/50 dark:border-yellow-900/20">
                            <span class="text-xs text-yellow-700 dark:text-yellow-400 block font-bold">Catatan Teknis</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $strategicAction->technical_note }}</p>
                        </div>
                    @endif

                @elseif($strategicAction->action_type == 'fertilizer_leaf')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Target Cakupan Areal</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->coverage_target_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Realisasi Cakupan Areal</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->realization_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Interval Aplikasi</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->application_interval ?? '-' }}</span>
                        </div>
                    </div>

                @elseif($strategicAction->action_type == 'weed_control')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Target Cakupan Areal</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->coverage_target_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Realisasi Cakupan Areal</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->realization_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Rotasi & Metode</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">
                                {{ $strategicAction->rotation_per_year ?? '0' }}x setahun ({{ $strategicAction->method ?? '-' }})
                            </span>
                        </div>
                    </div>

                @elseif($strategicAction->action_type == 'cultivator')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Target Cakupan Areal</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->coverage_target_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Realisasi Cakupan Areal</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->realization_percent ?? '0.00' }}%</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Metode Kultivasi</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->method ?? '-' }}</span>
                        </div>
                    </div>
                    @if($strategicAction->focus_area)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Fokus Area Kerja</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $strategicAction->focus_area }}</p>
                        </div>
                    @endif

                @elseif($strategicAction->action_type == 'picking')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Sistem Pemetikan</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->picking_system ?? '-' }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Konsistensi Cushion (Bantal)</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->cushion_consistency ?? '-' }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Risiko Kandas</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block flex items-center gap-1">
                                @if($strategicAction->kandas_risk)
                                    <span class="material-icons text-red-500 text-sm">warning</span> Berisiko Tinggi
                                @else
                                    <span class="material-icons text-green-500 text-sm">check_circle</span> Aman / Rendah
                                @endif
                            </span>
                        </div>
                    </div>

                @elseif($strategicAction->action_type == 'machine')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Total Unit Mesin</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->total_machine ?? '0' }} Unit</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Rata-rata Umur Mesin</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->avg_machine_age ?? '0.0' }} Tahun</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Status Peremajaan</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->renewal_status ?? '-' }}</span>
                        </div>
                    </div>

                @elseif($strategicAction->action_type == 'opt')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Status Serangan OPT</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $strategicAction->opt_status ?? '-' }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block">Normalisasi Tanaman Pelindung (TP)</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block flex items-center gap-1">
                                @if($strategicAction->tp_normalization)
                                    <span class="material-icons text-green-500 text-sm">check_circle</span> Sudah Dinormalisasi
                                @else
                                    <span class="material-icons text-red-500 text-sm">cancel</span> Belum Dinormalisasi
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($strategicAction->treatment_note)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 block font-bold">Catatan Penanganan OPT</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $strategicAction->treatment_note }}</p>
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-450 dark:text-gray-500">Tidak ada detail parameter tambahan untuk jenis aksi ini.</p>
                @endif

                @if($strategicAction->note)
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs text-gray-400 dark:text-gray-500 block uppercase font-bold tracking-wider mb-2">Catatan Umum / Keterangan</span>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $strategicAction->note }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
