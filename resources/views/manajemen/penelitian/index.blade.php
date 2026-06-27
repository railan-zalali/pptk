@extends('layouts.admin')

@section('title', 'Data Penelitian')
@section('breadcrumb', 'Informasi penelitian kebun model PPTK Gambung')

@section('content')

{{-- Tab Navigation --}}
<div class="flex items-center gap-2 mb-5 no-print">
    <a href="{{ route('manajemen.penelitian.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
               {{ request()->routeIs('manajemen.penelitian.*') ? 'bg-green-600 text-white shadow-md shadow-green-200 dark:shadow-green-900/40' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-green-700 dark:hover:text-green-400' }}">
        <span class="material-icons-outlined text-lg">science</span>
        Data Penelitian
    </a>
    <a href="{{ route('manajemen.community-services.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
               {{ request()->routeIs('manajemen.community-services.*') ? 'bg-green-600 text-white shadow-md shadow-green-200 dark:shadow-green-900/40' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-green-700 dark:hover:text-green-400' }}">
        <span class="material-icons-outlined text-lg">volunteer_activism</span>
        Data Pengabdian
    </a>
</div>

<div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="page-title flex items-center gap-2">
            <span class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined text-xl text-emerald-600 dark:text-emerald-400">science</span>
            </span>
            Data Penelitian
        </h2>
        <p class="page-subtitle">Informasi penelitian yang dilakukan di kebun model PPTK Gambung</p>
    </div>
    <div class="flex items-center gap-2 no-print">
        <a href="{{ route('manajemen.penelitian.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-blue-200 dark:shadow-none transition-all duration-150">
            <span class="material-icons-outlined text-lg">edit</span>
            Kelola Penelitian
        </a>
    </div>
</div>

@if ($page && !empty($page->meta))
    <!-- 1. Ringkasan Penelitian -->
    <div class="mb-6">
        <h3 class="text-base font-semibold text-gray-850 dark:text-gray-100 mb-4 flex items-center">
            <span class="material-icons mr-2 text-green-600">analytics</span> Ringkasan Penelitian
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Card 1: Jumlah Kegiatan -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase">Jumlah Kegiatan</p>
                        <p class="text-2xl font-bold text-gray-850 dark:text-gray-100 mt-1">
                            {{ number_format($page->meta['summary']['total_activities'] ?? 0) }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2.5 rounded-xl">
                        <span class="material-icons text-blue-600 dark:text-blue-400">science</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Anggaran -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase">Total Anggaran</p>
                        <p class="text-2xl font-bold text-gray-850 dark:text-gray-100 mt-1">
                            Rp {{ number_format($page->meta['summary']['total_budget'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 p-2.5 rounded-xl">
                        <span class="material-icons text-green-600 dark:text-green-400">payments</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Sisa Anggaran -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase">Sisa Anggaran</p>
                        <p class="text-2xl font-bold text-gray-850 dark:text-gray-100 mt-1">
                            Rp {{ number_format($page->meta['summary']['remaining_budget'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-2.5 rounded-xl">
                        <span class="material-icons text-purple-600 dark:text-purple-400">account_balance_wallet</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Informasi Penelitian -->
    <div class="mb-6">
        <h3 class="text-base font-semibold text-gray-850 dark:text-gray-100 mb-4 flex items-center">
            <span class="material-icons mr-2 text-green-600">description</span> Informasi Detail Penelitian
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5">
                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b dark:border-gray-800 pb-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded bg-emerald-500"></span> Riset Internal
                </h4>
                <p class="text-xs text-gray-600 dark:text-gray-450 leading-relaxed whitespace-pre-line">
                    {{ $page->meta['info']['internal_research'] ?? 'Belum ada informasi.' }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5">
                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b dark:border-gray-800 pb-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded bg-emerald-500"></span> Riset Eksternal
                </h4>
                <p class="text-xs text-gray-600 dark:text-gray-450 leading-relaxed whitespace-pre-line">
                    {{ $page->meta['info']['external_research'] ?? 'Belum ada informasi.' }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5">
                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b dark:border-gray-800 pb-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded bg-emerald-500"></span> Inkubasi Riset
                </h4>
                <p class="text-xs text-gray-600 dark:text-gray-450 leading-relaxed whitespace-pre-line">
                    {{ $page->meta['info']['incubation'] ?? 'Belum ada informasi.' }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5">
                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b dark:border-gray-800 pb-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded bg-amber-500"></span> Ringkasan RKAP (Anggaran)
                </h4>
                <p class="text-xs text-gray-600 dark:text-gray-450 leading-relaxed whitespace-pre-line">
                    {{ $page->meta['info']['rkap_budget'] ?? $page->meta['info']['rkap_notes'] ?? 'Belum ada informasi.' }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-5">
                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 border-b dark:border-gray-800 pb-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded bg-rose-500"></span> Ringkasan Pencairan
                </h4>
                <p class="text-xs text-gray-600 dark:text-gray-450 leading-relaxed whitespace-pre-line">
                    {{ $page->meta['info']['rkap_disbursement'] ?? 'Belum ada informasi.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- 3. Daftar Kegiatan Penelitian -->
    <div class="mb-6">
        <h3 class="text-base font-semibold text-gray-850 dark:text-gray-100 mb-4 flex items-center">
            <span class="material-icons mr-2 text-green-600">list_alt</span> Daftar Kegiatan Penelitian
        </h3>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-850 dark:text-gray-300">
                        <tr>
                            <th class="px-5 py-3 w-12 text-center">No</th>
                            <th class="px-5 py-3">Judul Penelitian</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3">Tahun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($page->meta['activities'] ?? [] as $activity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-5 py-3.5 text-center text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5 font-semibold text-gray-800 dark:text-gray-200">{{ $activity['title'] }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 inline-flex text-[10px] leading-5 font-bold rounded-full
                                    {{ $activity['type'] == 'Internal'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                        : ($activity['type'] == 'Eksternal'
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                        {{ $activity['type'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-550">{{ $activity['year'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">Belum ada data kegiatan penelitian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Dokumentasi & Laporan -->
    <div class="mb-6">
        <h3 class="text-base font-semibold text-gray-850 dark:text-gray-100 mb-4 flex items-center">
            <span class="material-icons mr-2 text-green-600">folder_open</span> Dokumentasi & Laporan
        </h3>
        @if (!empty($page->files))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($page->files as $file)
                    <div class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2xl p-4 hover:shadow-md transition-shadow flex items-start">
                        <div class="bg-gray-100 dark:bg-gray-850 p-2.5 rounded-xl mr-3 flex-shrink-0">
                            <span class="material-icons text-gray-600 dark:text-gray-400">description</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-850 dark:text-gray-200 truncate" title="{{ $file['name'] }}">
                                {{ $file['name'] }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                {{ round($file['size'] / 1024) }} KB •
                                {{ \Carbon\Carbon::parse($file['uploaded_at'] ?? now())->format('d M Y') }}
                            </p>
                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                class="text-xs text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-semibold mt-2 inline-block">
                                Download / Lihat
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 text-center border border-dashed border-gray-300 dark:border-gray-850">
                <span class="material-icons text-gray-400 text-3xl mb-2">folder_off</span>
                <p class="text-xs text-gray-500">Belum ada dokumen yang diunggah.</p>
            </div>
        @endif
    </div>

@else
    <div class="card">
        <div class="card-body">
            <div class="py-16 text-center">
                <div class="w-20 h-20 rounded-3xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons-outlined text-4xl text-emerald-500">science</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Belum Ada Data Penelitian</h3>
                <p class="text-sm text-gray-400 max-w-sm mx-auto mb-5">
                    Data penelitian belum diisi. Silakan tekan tombol di bawah ini untuk mengisi konten halaman penelitian.
                </p>
                <a href="{{ route('manajemen.penelitian.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all">
                    <span class="material-icons-outlined text-sm">edit</span>
                    Isi Konten Penelitian
                </a>
            </div>
        </div>
    </div>
@endif

@endsection
