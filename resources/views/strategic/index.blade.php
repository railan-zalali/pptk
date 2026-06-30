@extends('layouts.pptk')

@section('content')
    <!-- Page Header + Menu -->
    <section class="py-12 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-3">Strategic Action</h1>
                <p class="text-lg opacity-90 max-w-3xl mx-auto">
                    Navigasi wilayah dan aksi strategis untuk kebun model teh
                </p>
            </div>
            <div class="mt-8">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="#wilayah"
                        class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Wilayah</a>
                    <a href="#aksi"
                        class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Aksi
                        Strategis</a>
                    <a href="{{ route('dashboard.garden') }}"
                        class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Dashboard
                        Kebun</a>
                    <a href="{{ route('dashboard.research') }}"
                        class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Dashboard
                        Penelitian</a>
                    <a href="{{ route('visits.index') }}"
                        class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Kunjungan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Regional Navigation -->
    <section id="wilayah" class="py-16 bg-white dark:bg-gray-900 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4">Pilih Wilayah</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    PPTK Gambung mengelola kebun model teh di berbagai wilayah yang mewakili beragam kondisi agroklimat.
                </p>
            </div>

            <div x-data="{ q: '' }">
                <div class="max-w-xl mx-auto mb-8">
                    <label for="search-regions" class="sr-only">Cari Wilayah</label>
                    <div class="relative">
                        <input id="search-regions" type="text" x-model="q" placeholder="Cari wilayah..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-800 dark:text-gray-100"
                            aria-label="Cari wilayah">
                        <span class="material-icons absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">search</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($regions as $region)
                        <div class="pptk-card p-6 group hover:border-green-500 transition-all duration-300"
                            x-show="($el.dataset.name || '').toLowerCase().includes(q.toLowerCase())" x-transition
                            data-name="{{ $region->regional_name }}">
                            <div class="relative overflow-hidden rounded-lg mb-6">
                                @php
                                    $cover = $region->photo_path ?? optional($region->photos->first())->path;
                                @endphp
                                <img src="{{ $cover ? asset('storage/' . $cover) : 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=' . urlencode('Tea plantation landscape in ' . $region->regional_name . ', Indonesia, professional landscape photography, lush green tea garden') . '&image_size=landscape_4_3' }}"
                                    alt="Kebun Teh {{ $region->regional_name }}" loading="lazy" decoding="async" fetchpriority="low"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <h3 class="text-xl font-bold">{{ $region->regional_name }}</h3>
                                    <p class="text-sm opacity-90">{{ $region->gardens->count() }} Kebun Model</p>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <span class="material-icons text-green-600 dark:text-green-400 mr-2">location_on</span>
                                    <span>{{ $region->province }}</span>
                                </div>
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <span class="material-icons text-green-600 dark:text-green-400 mr-2">map</span>
                                    <span>{{ $region->coordinates ?? 'Koordinat tidak tersedia' }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Ringkasan Kebun:</h4>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    @foreach ($region->gardens->take(3) as $garden)
                                        <li>&bull; {{ $garden->kebun_name }} ({{ number_format($garden->luas_total_ha, 1) }} ha)</li>
                                    @endforeach
                                    @if ($region->gardens->count() > 3)
                                        <li>&bull; dan {{ $region->gardens->count() - 3 }} lainnya...</li>
                                    @endif
                                </ul>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('strategic.region', $region->id) }}"
                                    class="flex-1 text-center bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-lg font-medium hover:from-green-700 hover:to-green-800 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">
                                    Jelajahi Wilayah
                                </a>
                                <a href="{{ route('dashboard.garden') }}"
                                    class="px-4 py-3 rounded-lg border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-800 font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">
                                    Dashboard
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Strategic Overview -->
            <div id="aksi" class="mt-16 scroll-mt-24">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Strategic Action Overview</h3>
                    <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Pendekatan terintegrasi untuk meningkatkan produktivitas dan kualitas kebun model teh
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/50 rounded-lg">
                        <span class="material-icons text-blue-600 dark:text-blue-400 text-3xl mb-3">science</span>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Penelitian</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Pemuliaan varietas unggul dan pengembangan
                            teknologi budidaya</p>
                    </div>

                    <div
                        class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/50 rounded-lg">
                        <span class="material-icons text-green-600 dark:text-green-400 text-3xl mb-3">agriculture</span>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Demonstrasi</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Praktik terbaik dalam budidaya dan pengelolaan
                            kebun</p>
                    </div>

                    <div
                        class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/50 rounded-lg">
                        <span class="material-icons text-purple-600 dark:text-purple-400 text-3xl mb-3">groups</span>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Diseminasi</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Penyuluhan dan pelatihan bagi petani serta
                            praktisi</p>
                    </div>

                    <div
                        class="text-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/50 dark:to-orange-800/50 rounded-lg">
                        <span class="material-icons text-orange-600 dark:text-orange-400 text-3xl mb-3">analytics</span>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Monitoring</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Evaluasi kinerja dan analisis data produksi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
