@extends('layouts.pptk')

@section('title', $region->regional_name . ' - Strategic Action')

@section('content')
    @php
        $regionCover = $region->photo_path ?? optional($region->photos->first())->path;
        $totalArea = $region->gardens->sum('luas_total_ha');
        $strategicActionCount = $region->gardens->sum(fn ($garden) => $garden->strategicActions()->count());
    @endphp

    <section class="py-14 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="mb-6 text-sm text-green-100">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('strategic.index') }}" class="hover:text-white">Strategic Action</a>
                <span class="mx-2">/</span>
                <span class="font-semibold text-white">{{ $region->regional_name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="text-sm uppercase tracking-wider text-green-100 mb-3">{{ $region->regional_code }}</p>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $region->regional_name }}</h1>
                    <p class="text-lg text-green-50 max-w-2xl">
                        Ringkasan kebun model, luas lahan, dan aksi strategis yang berjalan di wilayah ini.
                    </p>
                </div>
                <img src="{{ $regionCover ? asset('storage/' . $regionCover) : asset('img/tea-placeholder.svg') }}"
                    alt="Kebun Teh {{ $region->regional_name }}" loading="lazy" decoding="async"
                    class="w-full h-72 object-cover rounded-lg shadow-lg border border-white/20">
            </div>
        </div>
    </section>

    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Kebun</p>
                    <p class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $region->gardens->count() }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Luas</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ number_format($totalArea, 1) }} ha</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Aksi Strategis</p>
                    <p class="text-3xl font-bold text-orange-700 dark:text-orange-400">{{ $strategicActionCount }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($region->gardens as $garden)
                    @php
                        $gardenCover = optional($garden->photos->first())->path ?? $garden->photo_path ?? $regionCover;
                        $currentYearRealizations = $garden->productionRealizations()->where('year', now()->year)->get();
                        $productionTotal = $currentYearRealizations->sum('wet_production_kg');
                    @endphp
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <img src="{{ $gardenCover ? asset('storage/' . $gardenCover) : asset('img/tea-placeholder.svg') }}"
                            alt="Kebun {{ $garden->kebun_name }}" loading="lazy" decoding="async"
                            class="w-full h-44 object-cover">
                        <div class="p-5">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $garden->kebun_name }}</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ $garden->location ?? 'Lokasi belum diisi' }}</p>
                            <dl class="grid grid-cols-2 gap-3 text-sm mb-5">
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Luas</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($garden->luas_total_ha, 1) }} ha</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Produksi YTD</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($productionTotal, 0) }} kg</dd>
                                </div>
                            </dl>
                            <a href="{{ route('strategic.garden', ['region' => $region, 'garden' => $garden]) }}"
                                class="inline-flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium">
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-300">Belum ada kebun untuk wilayah ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
