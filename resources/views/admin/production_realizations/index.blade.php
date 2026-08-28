@extends('layouts.admin')

@section('title', 'Kelola Produksi & Realisasi')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">assessment</span>
            Data Realisasi Produksi & Monitoring
        </h3>
        <a href="{{ route('admin.production-realizations.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah Data
        </a>
    </div>


         <!-- PANEL: Algoritma Rule-Based Produktivitas (IF–THEN)
         Rule engine berjalan otomatis setiap kali data disimpan/diperbarui.
         Hasil disimpan ke tabel insights dan divisualisasikan sebagai badge
         pada kolom "Status Protas" di tabel di bawah ini. -->

    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20
                border border-green-200 dark:border-green-700 rounded-xl p-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-icons-outlined text-green-700 dark:text-green-400">smart_toy</span>
            <h4 class="font-semibold text-green-800 dark:text-green-300 text-sm">Algoritma Rule-Based — Status Produktivitas</h4>
            <span class="ml-auto text-xs bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300
                         px-2 py-0.5 rounded-full font-mono">Rule Engine Aktif</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            {{-- Produksi Basah --}}
            <div>
                <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">&#127807; Protas Basah (kg/ha)</p>
                <table class="w-full border-collapse text-xs">
                    <thead>
                        <tr class="bg-white/60 dark:bg-gray-800/60">
                            <th class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-400">Kondisi (IF)</th>
                            <th class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-400">Hasil (THEN)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-green-100 dark:divide-green-800">
                        <tr>
                            <td class="px-2 py-1 font-mono text-gray-700 dark:text-gray-300">protas &lt; 83</td>
                            <td class="px-2 py-1">
                                <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40
                                             text-red-700 dark:text-red-300 px-2 py-0.5 rounded-full font-semibold">
                                    &#128308; High Alert
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 py-1 font-mono text-gray-700 dark:text-gray-300">83 &le; protas &lt; 108</td>
                            <td class="px-2 py-1">
                                <span class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/40
                                             text-yellow-700 dark:text-yellow-400 px-2 py-0.5 rounded-full font-semibold">
                                    &#128993; Medium Alert
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 py-1 font-mono text-gray-700 dark:text-gray-300">protas &ge; 108</td>
                            <td class="px-2 py-1">
                                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40
                                             text-green-700 dark:text-green-300 px-2 py-0.5 rounded-full font-semibold">
                                    &#128994; Low Alert
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- Produksi Kering --}}
            <div>
                <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">&#9749; Protas Kering (kg/ha &times;22%)</p>
                <table class="w-full border-collapse text-xs">
                    <thead>
                        <tr class="bg-white/60 dark:bg-gray-800/60">
                            <th class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-400">Kondisi (IF)</th>
                            <th class="px-2 py-1 text-left font-semibold text-gray-600 dark:text-gray-400">Hasil (THEN)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-green-100 dark:divide-green-800">
                        <tr>
                            <td class="px-2 py-1 font-mono text-gray-700 dark:text-gray-300">protas_kering &lt; 18.3</td>
                            <td class="px-2 py-1">
                                <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40
                                             text-red-700 dark:text-red-300 px-2 py-0.5 rounded-full font-semibold">
                                    &#128308; High Alert
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 py-1 font-mono text-gray-700 dark:text-gray-300">18.3 &le; protas &lt; 23.8</td>
                            <td class="px-2 py-1">
                                <span class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/40
                                             text-yellow-700 dark:text-yellow-400 px-2 py-0.5 rounded-full font-semibold">
                                    &#128993; Medium Alert
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 py-1 font-mono text-gray-700 dark:text-gray-300">protas_kering &ge; 23.8</td>
                            <td class="px-2 py-1">
                                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40
                                             text-green-700 dark:text-green-300 px-2 py-0.5 rounded-full font-semibold">
                                    &#128994; Low Alert
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 italic">
            * Status dihitung otomatis saat data tersimpan. Protas kering menggunakan
            data aktual <code>dry_production_kg</code>; jika kosong, diestimasi 22% dari basah.
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Periode</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kebun</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Luas Efektif (Ha)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Prod. Basah (Kg)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Prod. Kering (Kg)</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status Protas Basah</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status Protas Kering</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kapasitas Pemetikan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Forecast</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                     <!-- Rule 1: IF protas_basah < 83  THEN High Alert
                                 Rule 2: IF 83 <= protas_basah < 108 THEN Medium Alert
                                 Rule 3: IF protas_basah >= 108 THEN Low Alert -->
                    @forelse ($productions as $production)
                        @php
                            $area = (float) $production->active_picking_area_ha;
                            $protasBasah = $area > 0
                                ? (float) $production->wet_production_kg / $area
                                : 0;
                            if ($protasBasah <= 0)         $statusBasah = ['label' => 'Belum Ada Data',  'bg' => 'gray',   'dot' => '&#9898;'];
                            elseif ($protasBasah < 83)     $statusBasah = ['label' => 'High Alert',      'bg' => 'red',    'dot' => '&#128308;'];
                            elseif ($protasBasah < 108)    $statusBasah = ['label' => 'Medium Alert',    'bg' => 'yellow', 'dot' => '&#128993;'];
                            else                           $statusBasah = ['label' => 'Low Alert',       'bg' => 'green',  'dot' => '&#128994;'];

                            // RULE-BASED: Protas Kering (IF–THEN)
                            // Rule 1: IF protas_kering < 18.3  THEN High Alert
                            // Rule 2: IF 18.3 <= protas_kering < 23.8 THEN Medium Alert
                            // Rule 3: IF protas_kering >= 23.8 THEN Low Alert
                            // Threshold = basah × 22% (konversi teh)

                            $dryKg = ($production->dry_production_kg !== null && (float)$production->dry_production_kg > 0)
                                ? (float) $production->dry_production_kg
                                : (float) $production->wet_production_kg * 0.22;
                            $protasKering = $area > 0 ? $dryKg / $area : 0;
                            if ($protasKering <= 0)        $statusKering = ['label' => 'Belum Ada Data', 'bg' => 'gray',   'dot' => '&#9898;'];
                            elseif ($protasKering < 18.3)  $statusKering = ['label' => 'High Alert',     'bg' => 'red',    'dot' => '&#128308;'];
                            elseif ($protasKering < 23.8)  $statusKering = ['label' => 'Medium Alert',   'bg' => 'yellow', 'dot' => '&#128993;'];
                            else                           $statusKering = ['label' => 'Low Alert',      'bg' => 'green',  'dot' => '&#128994;'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                                    {{ DateTime::createFromFormat('!m', $production->month)->format('F') }}
                                    {{ $production->year }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                                    {{ $production->garden->kebun_name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format($production->active_picking_area_ha, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                {{ number_format($production->wet_production_kg, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-600 dark:text-amber-400">
                                {{ $production->dry_production_kg !== null ? number_format($production->dry_production_kg, 0) : '-' }}
                            </td>
                            {{-- Badge Status Protas Basah (Rule-Based) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-0.5">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $statusBasah['bg'] === 'red'    ? 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300'       : '' }}
                                        {{ $statusBasah['bg'] === 'yellow' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300' : '' }}
                                        {{ $statusBasah['bg'] === 'green'  ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : '' }}
                                        {{ $statusBasah['bg'] === 'gray'   ? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'       : '' }}">
                                        {!! $statusBasah['dot'] !!} {{ $statusBasah['label'] }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-mono">
                                        {{ number_format($protasBasah, 0) }} kg/ha
                                    </span>
                                </div>
                            </td>
                            {{-- Badge Status Protas Kering (Rule-Based) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-0.5">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $statusKering['bg'] === 'red'    ? 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300'       : '' }}
                                        {{ $statusKering['bg'] === 'yellow' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300' : '' }}
                                        {{ $statusKering['bg'] === 'green'  ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : '' }}
                                        {{ $statusKering['bg'] === 'gray'   ? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'       : '' }}">
                                        {!! $statusKering['dot'] !!} {{ $statusKering['label'] }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-mono">
                                        {{ number_format($protasKering, 0) }} kg/ha
                                        @if($production->dry_production_kg === null)
                                            <span title="Estimasi 22% dari basah">*est.</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col gap-1">
                                    <span>Per Ha: {{ $production->capacity_per_ha }}</span>
                                    <span>Avg: {{ $production->avg_capacity }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col gap-1">
                                    <span>Est: {{ number_format($production->estimated_production, 0) }}</span>
                                    <span title="{{ $production->assumption_note }}">Note:
                                        {{ Str::limit($production->assumption_note, 10) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.production-realizations.edit', $production) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.production-realizations.destroy', $production) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data realisasi ini?')">
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
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-icons-outlined text-4xl text-gray-300">inbox</span>
                                    <p class="text-base">Belum ada data realisasi produksi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
        {{ $productions->links() }}
    </div>
@endsection
