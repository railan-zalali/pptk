@extends('layouts.pptk')

@section('title', $garden->kebun_name . ' - Aksi Strategis')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}"
                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">Beranda</a>
                </li>
                <li><span class="text-gray-400 dark:text-gray-600">/</span></li>
                <li><a href="{{ route('strategic.index') }}"
                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">Aksi
                        Strategis</a>
                </li>
                <li><span class="text-gray-400 dark:text-gray-600">/</span></li>
                <li><a href="{{ route('strategic.region', $garden->region->id) }}"
                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">{{ $garden->region->regional_name }}</a>
                </li>
                <li><span class="text-gray-400 dark:text-gray-600">/</span></li>
                <li class="text-gray-700 dark:text-gray-300">{{ $garden->kebun_name }}</li>
            </ol>
        </nav>

        <!-- Garden Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row items-start justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">{{ $garden->kebun_name }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mb-2 flex items-center">
                        <span class="material-icons text-green-600 dark:text-green-400 mr-2 text-base">location_on</span>
                        {{ $garden->location ?? '-' }}, {{ $garden->region->regional_name }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 flex items-center">
                        <span class="material-icons text-green-600 dark:text-green-400 mr-2 text-base">square_foot</span>
                        Luas Area: {{ number_format($garden->luas_total_ha, 2) }} hektar
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-100 rounded-full text-sm">
                            {{ ucfirst($garden->kebun_type) }}
                        </span>
                        <span
                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-100 rounded-full text-sm">
                            {{ number_format($garden->luas_total_ha, 1) }} ha
                        </span>
                        <span
                            class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-100 rounded-full text-sm">
                            {{ $garden->region->province ?? 'Wilayah' }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                        {{ number_format($latestProduction->productivity_wet ?? 0, 1) }} kg/ha
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Produktivitas Terakhir</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $latestProduction->month ?? 'Belum ada data' }}</div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('strategic.region', $garden->region->id) }}"
                class="pptk-btn text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Kembali
                ke Wilayah</a>
            <a href="{{ route('visits.index') }}"
                class="px-4 py-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Lihat
                Kunjungan</a>
            <div
                class="flex gap-2 sticky top-20 z-40 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md rounded-full px-2 py-1">
                <a href="#lokasi"
                    class="px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full hover:bg-green-100 dark:hover:bg-green-800 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Lokasi</a>
                <a href="#statistik"
                    class="px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full hover:bg-green-100 dark:hover:bg-green-800 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Statistik</a>
                <a href="#dokumentasi"
                    class="px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full hover:bg-green-100 dark:hover:bg-green-800 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Dokumentasi</a>
                <a href="#wawasan"
                    class="px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full hover:bg-green-100 dark:hover:bg-green-800 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Wawasan</a>
            </div>
        </div>

        <!-- Garden Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Map Section -->
            <div class="lg:col-span-2 scroll-mt-24" id="lokasi">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-green-800 dark:text-green-100 mb-4 flex items-center">
                        <span class="material-icons mr-2">map</span>Peta Lokasi
                    </h2>
                    <div id="map"
                        class="relative w-full h-80 md:h-96 lg:h-[28rem] rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-4">
                        <div id="mapSkeleton"
                            class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700 animate-pulse z-10">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <div class="w-32 h-4 bg-gray-200 dark:bg-gray-600 rounded mb-2"></div>
                                <div class="w-24 h-4 bg-gray-200 dark:bg-gray-600 rounded mx-auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Koordinat:</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">{{ $garden->region->coordinates ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Tipe Kebun:</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">{{ $garden->kebun_type }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Luas:</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">{{ number_format($garden->luas_total_ha, 1) }} ha</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Agroklimat:</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">{{ $garden->agro_climate_note ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6 scroll-mt-24" id="statistik">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-green-800 dark:text-green-100 mb-4">Statistik Cepat</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Rata-rata Produktivitas</span>
                            <span
                                class="font-bold text-green-600 dark:text-green-400">{{ number_format($avgProductivity, 1) }}
                                kg/ha</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Total Produksi</span>
                            <span
                                class="font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalProduction, 0) }}
                                kg</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Kunjungan Dinas</span>
                            <span class="font-bold text-purple-600 dark:text-purple-400">{{ $visitCount }} kali</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Status Kesehatan</span>
                            <span
                                class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-100 rounded-full text-xs">
                                Data tersedia
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-green-800 dark:text-green-100 mb-4">Informasi Tanah</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600 dark:text-gray-300">pH Tanah:</span>
                            <span
                                class="font-semibold ml-2 text-gray-800 dark:text-gray-200">{{ $garden->soil_ph ?? '7.0' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Jenis Tanah:</span>
                            <span
                                class="font-semibold ml-2 text-gray-800 dark:text-gray-200">{{ $garden->soil_type ?? 'Andosol' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Drainase:</span>
                            <span
                                class="font-semibold ml-2 text-gray-800 dark:text-gray-200">{{ $garden->drainage ?? 'Baik' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8 scroll-mt-24" id="dokumentasi">
            <h2 class="text-xl font-bold text-green-800 dark:text-green-100 mb-6 gradient-text flex items-center">
                <span class="material-icons mr-2">photo_camera</span>Dokumentasi Kebun
            </h2>
            @php
                $galleryPhotos = [];
                if ($garden->photo_path) {
                    $galleryPhotos[] = [
                        'title' => 'Foto Kebun',
                        'desc' => $garden->kebun_name,
                        'image' => asset('storage/' . $garden->photo_path),
                    ];
                }
                if ($garden->photos && $garden->photos->count()) {
                    $galleryPhotos = array_merge(
                        $galleryPhotos,
                        $garden->photos
                            ->map(function ($p, $idx) use ($garden) {
                                return [
                                    'title' => 'Galeri Kebun ' . ($idx + 1),
                                    'desc' => $garden->kebun_name,
                                    'image' => asset('storage/' . $p->path),
                                ];
                            })
                            ->toArray(),
                    );
                }
                if ($garden->region && $garden->region->photos) {
                    $galleryPhotos = array_merge(
                        $galleryPhotos,
                        $garden->region->photos
                            ->map(function ($p, $idx) {
                                return [
                                    'title' => 'Foto Wilayah ' . ($idx + 1),
                                    'desc' => '',
                                    'image' => asset('storage/' . $p->path),
                                ];
                            })
                            ->toArray(),
                    );
                }
            @endphp
            @if (count($galleryPhotos) === 0)
                <p class="text-gray-600 dark:text-gray-300 mb-4">Belum ada dokumentasi wilayah tersedia.</p>
            @endif
            <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
            <div class="text-center mt-6">
                <button id="loadMoreBtn"
                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2 rounded-full font-semibold">Muat
                    Lebih Banyak</button>
            </div>
            <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
                <button id="closeLightbox"
                    class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors">
                    <span class="material-icons text-2xl">close</span>
                </button>
                <img id="lightboxImage" src="" alt=""
                    class="max-w-full max-h-full rounded-lg shadow-2xl">
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-center">
                    <h3 id="lightboxTitle" class="text-xl font-bold mb-2"></h3>
                    <p id="lightboxDescription" class="text-sm opacity-90"></p>
                </div>
            </div>
        </div>

        <!-- Strategic Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-green-800 dark:text-green-100 mb-6 flex items-center">
                <span class="material-icons mr-2">precision_manufacturing</span>Aksi Strategis
            </h2>
            @if($garden->strategicActions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($garden->strategicActions as $action)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h3 class="font-bold text-green-700 dark:text-green-400 mb-3 flex items-center capitalize">
                                @if($action->action_type == 'fertilizer_root')
                                    <span class="material-icons mr-2">spa</span> Pemupukan
                                @elseif($action->action_type == 'cultivator')
                                    <span class="material-icons mr-2">agriculture</span> Kultivator
                                @elseif($action->action_type == 'weed_control')
                                    <span class="material-icons mr-2">grass</span> Pengendalian Gulma
                                @else
                                    <span class="material-icons mr-2">check_circle</span> {{ str_replace('_', ' ', $action->action_type) }}
                                @endif
                            </h3>
                            
                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                @if($action->action_type == 'fertilizer_root')
                                    <div class="flex justify-between">
                                        <span>Persen Protas:</span>
                                        <span class="font-semibold">{{ $action->n_protas_percent }}%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Realisasi:</span>
                                        <span class="font-semibold">{{ $action->dosis_n_kg_ha }} kg/ha</span>
                                    </div>
                                @elseif($action->action_type == 'cultivator')
                                    <div class="flex justify-between">
                                        <span>Area Fokus:</span>
                                        <span class="font-semibold">{{ $action->focus_area }}</span>
                                    </div>
                                @elseif($action->action_type == 'weed_control')
                                     <div class="flex justify-between">
                                        <span>Metode:</span>
                                        <span class="font-semibold">{{ $action->method }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Rotasi:</span>
                                        <span class="font-semibold">{{ $action->rotation_per_year }} / tahun</span>
                                    </div>
                                @else
                                    <p>{{ $action->note ?? 'Tidak ada detail tambahan.' }}</p>
                                @endif
                                @if($action->year)
                                    <div class="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                        Tahun: {{ $action->year }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="material-icons text-gray-400 text-4xl mb-2">assignment_off</span>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada data aksi strategis yang tercatat.</p>
                </div>
            @endif
        </div>

        <!-- Recent Insights -->
        @if ($insights->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 scroll-mt-24" id="wawasan">
                <h2 class="text-xl font-bold text-green-800 dark:text-green-100 mb-6 flex items-center">
                    <span class="material-icons mr-2 text-yellow-500">lightbulb</span>Wawasan Terbaru
                </h2>
                <div class="space-y-4">
                    @foreach ($insights as $insight)
                        <div
                            class="border-l-4 border-{{ $insight->alert_level == 'high' ? 'red' : ($insight->alert_level == 'medium' ? 'yellow' : 'green') }}-500 pl-4 py-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $insight->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">{{ $insight->description }}
                                    </p>
                                    <div class="mt-2">
                                        @php
                                            $recs = is_array($insight->recommendations)
                                                ? $insight->recommendations
                                                : json_decode($insight->recommendations, true) ?? [];
                                        @endphp
                                        @foreach ($recs as $recommendation)
                                            <span
                                                class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-xs mr-2 mb-1">
                                                {{ $recommendation }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                                    {{ $insight->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }

        .gallery-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .gallery-item:hover {
            transform: translateY(-10px);
        }

        .gallery-item img {
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        #lightbox {
            transition: opacity 0.3s ease;
        }

        #lightbox.show {
            opacity: 1;
        }

        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .lazy-load {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .lazy-load.loaded {
            opacity: 1;
        }


        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .gradient-text {
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9C3s=" crossorigin="">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const galleryData = @json($galleryPhotos);
        let itemsLoaded = 8;

        function renderGallery() {
            const galleryGrid = document.getElementById('galleryGrid');
            const items = galleryData.slice(0, itemsLoaded);
            galleryGrid.innerHTML = items.map(item => `
                <div class="gallery-item group relative overflow-hidden rounded-xl shadow-lg cursor-pointer bg-white hover-card"
                     onclick="openLightbox('${item.image}', '${item.title}', '${item.desc}')">
                    <div class="aspect-square overflow-hidden">
                        <img src="${item.image}" alt="${item.title}" loading="lazy" decoding="async" class="w-full h-full object-cover lazy-load" onload="this.classList.add('loaded')">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                            <h3 class="text-lg font-bold mb-1">${item.title}</h3>
                            <p class="text-sm opacity-90">${item.desc}</p>
                        </div>
                    </div>
                </div>
            `).join('');
            const images = galleryGrid.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', () => img.classList.add('loaded'));
            });
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            if (itemsLoaded >= galleryData.length) {
                loadMoreBtn.style.display = 'none';
            } else {
                loadMoreBtn.style.display = 'inline-block';
            }
        }

        function initLoadMore() {
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            loadMoreBtn.addEventListener('click', () => {
                itemsLoaded += 4;
                renderGallery();
                document.getElementById('galleryGrid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'end'
                });
            });
        }

        function initMap() {
            const lat = null;
            const lng = null;
            let centerLat = lat,
                centerLng = lng;
            @php $coordStr = $garden->region->coordinates ?? null; @endphp
            if (centerLat === null || centerLng === null) {
                const coordStr = @json($coordStr);
                if (coordStr && coordStr.includes(',')) {
                    const parts = coordStr.split(',').map(s => parseFloat(s.trim()));
                    if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                        centerLat = parts[0];
                        centerLng = parts[1];
                    }
                }
            }
            if (centerLat == null || centerLng == null) {
                centerLat = -7.1767;
                centerLng = 107.6459;
            }
            const map = L.map('map', {
                scrollWheelZoom: false,
                zoomControl: false,
                dragging: true
            }).setView([centerLat, centerLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map).on('load', () => {
                const sk = document.getElementById('mapSkeleton');
                if (sk) sk.classList.add('hidden');
            });
            L.control.zoom({
                position: 'topright'
            }).addTo(map);
            L.control.scale({
                imperial: false
            }).addTo(map);
            L.marker([centerLat, centerLng]).addTo(map)
                .bindPopup("<b>{{ $garden->kebun_name }}</b><br>{{ $garden->region->regional_name }}, Indonesia")
                .openPopup();
            L.circle([centerLat, centerLng], {
                radius: 800,
                color: '#16a34a',
                fillColor: '#16a34a',
                fillOpacity: 0.15
            }).addTo(map);
            setTimeout(() => map.invalidateSize(), 0);
            window.addEventListener('resize', () => map.invalidateSize());
        }

        function openLightbox(imageSrc, title, description) {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightboxImage');
            const t = document.getElementById('lightboxTitle');
            const d = document.getElementById('lightboxDescription');
            img.src = imageSrc;
            t.textContent = title;
            d.textContent = description;
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex', 'show');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex', 'show');
            document.body.style.overflow = 'auto';
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('closeLightbox').addEventListener('click', closeLightbox);
            document.getElementById('lightbox').addEventListener('click', function(e) {
                if (e.target === e.currentTarget) closeLightbox();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeLightbox();
            });
            renderGallery();
            initLoadMore();
            initMap();
        });
    </script>
@endpush
