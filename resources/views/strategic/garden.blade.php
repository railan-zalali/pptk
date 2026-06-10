@extends('layouts.pptk')

@section('title', $garden->kebun_name . ' - Aksi Strategis')

@section('content')
    @php
        $cover = $garden->photo_path ?? optional($garden->photos->first())->path ?? optional($garden->region->photos->first())->path;
        $latestMonth = $latestProduction ? DateTime::createFromFormat('!m', $latestProduction->month)->format('F') . ' ' . $latestProduction->year : 'Belum ada data';
        $statusClasses = [
            'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        ];
        $alertClasses = [
            'low' => 'border-green-500 bg-green-50 dark:bg-green-900/20',
            'medium' => 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20',
            'high' => 'border-red-500 bg-red-50 dark:bg-red-900/20',
        ];
    @endphp

    <section class="py-12 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="mb-6 text-sm text-green-100">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('strategic.index') }}" class="hover:text-white">Strategic Action</a>
                <span class="mx-2">/</span>
                <a href="{{ route('strategic.region', $region) }}" class="hover:text-white">{{ $region->regional_name }}</a>
                <span class="mx-2">/</span>
                <span class="font-semibold text-white">{{ $garden->kebun_name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="text-sm uppercase tracking-wider text-green-100 mb-3">{{ $garden->kebun_type }}</p>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $garden->kebun_name }}</h1>
                    <p class="text-lg text-green-50 mb-5">{{ $garden->location ?? 'Lokasi belum diisi' }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-white/15 rounded-full text-sm">{{ number_format($garden->luas_total_ha, 1) }} ha</span>
                        <span class="px-3 py-1 bg-white/15 rounded-full text-sm">{{ $region->regional_name }}</span>
                        <span class="px-3 py-1 bg-white/15 rounded-full text-sm">Data {{ now()->year }}</span>
                    </div>
                </div>
                <img src="{{ $cover ? asset('storage/' . $cover) : asset('img/tea-placeholder.svg') }}"
                    alt="Kebun {{ $garden->kebun_name }}" loading="lazy" decoding="async"
                    class="w-full h-72 object-cover rounded-lg shadow-lg border border-white/20">
            </div>
        </div>
    </section>

    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Produksi YTD</p>
                    <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ number_format($totalProduction, 0) }} kg</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Produktivitas</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ number_format($avgProductivity, 1) }} kg/ha</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Periode Terakhir</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $latestMonth }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kunjungan</p>
                    <p class="text-2xl font-bold text-orange-700 dark:text-orange-400">{{ $visitCount }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <section class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Aksi Strategis</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse ($garden->strategicActions as $action)
                                <article class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $action->year }}</p>
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 capitalize mb-2">{{ str_replace('_', ' ', $action->action_type) }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ $action->technical_note ?? $action->note ?? 'Belum ada catatan teknis.' }}</p>
                                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                        @if (! is_null($action->coverage_target_percent))
                                            <p>Target cakupan: {{ number_format($action->coverage_target_percent, 1) }}%</p>
                                        @endif
                                        @if (! is_null($action->realization_percent))
                                            <p>Realisasi: {{ number_format($action->realization_percent, 1) }}%</p>
                                        @endif
                                        @if (! is_null($action->realized_dosis_n_kg_ha))
                                            <p>Dosis realisasi: {{ number_format($action->realized_dosis_n_kg_ha, 1) }} kg/ha</p>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <p class="md:col-span-2 text-gray-600 dark:text-gray-300">Belum ada aksi strategis untuk kebun ini.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Dokumentasi</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @forelse ($garden->photos as $photo)
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="Dokumentasi {{ $garden->kebun_name }}"
                                    loading="lazy" decoding="async" class="aspect-square w-full object-cover rounded-lg">
                            @empty
                                <img src="{{ $cover ? asset('storage/' . $cover) : asset('img/tea-placeholder.svg') }}"
                                    alt="Dokumentasi {{ $garden->kebun_name }}" class="aspect-square w-full object-cover rounded-lg">
                            @endforelse
                        </div>
                    </section>
                </div>

                <aside class="space-y-8">
                    <section class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Profil Kebun</h2>
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Wilayah</dt>
                                <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $region->regional_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Tipe Kebun</dt>
                                <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $garden->kebun_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400">Agroklimat</dt>
                                <dd class="text-gray-700 dark:text-gray-300">{{ $garden->agro_climate_note ?? 'Belum diisi' }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Kunjungan Terbaru</h2>
                        <div class="space-y-3">
                            @forelse ($garden->visits->sortByDesc('visit_date')->take(5) as $visit)
                                <a href="{{ route('visits.show', $visit) }}" class="block border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $visit->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $visit->visit_date->format('d M Y') }}</p>
                                    <span class="inline-flex mt-2 px-2 py-1 rounded-full text-xs {{ $statusClasses[$visit->status] ?? $statusClasses['scheduled'] }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </a>
                            @empty
                                <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada kunjungan.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Insight</h2>
                        <div class="space-y-3">
                            @forelse ($insights as $insight)
                                <article class="border-l-4 {{ $alertClasses[$insight->alert_level] ?? $alertClasses['low'] }} rounded-r-lg p-3">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $insight->title ?? $insight->insight_type }}</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $insight->description ?? $insight->message }}</p>
                                </article>
                            @empty
                                <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada insight.</p>
                            @endforelse
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </section>
@endsection
