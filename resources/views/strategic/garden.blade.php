@extends('layouts.pptk')

@section('title', $garden->name . ' - Aksi Strategis')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-green-600 hover:text-green-800">Beranda</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('strategic.index') }}" class="text-green-600 hover:text-green-800">Aksi Strategis</a>
                </li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('strategic.region', $garden->region->id) }}"
                        class="text-green-600 hover:text-green-800">{{ $garden->region->name }}</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-700">{{ $garden->name }}</li>
            </ol>
        </nav>

        <!-- Garden Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row items-start justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-green-800 mb-2">{{ $garden->name }}</h1>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                        {{ $garden->address }}, {{ $garden->region->name }}
                    </p>
                    <p class="text-gray-600 mb-4">
                        <i class="fas fa-ruler-combined text-green-600 mr-2"></i>
                        Luas Area: {{ number_format($garden->area, 2) }} hektar
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            {{ ucfirst($garden->garden_type) }}
                        </span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ $garden->elevation }} mdpl
                        </span>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                            Varietas: {{ $garden->tea_variety }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600 mb-2">
                        {{ number_format($latestProduction->productivity ?? 0, 1) }} kg/ha
                    </div>
                    <div class="text-sm text-gray-600">Produktivitas Terakhir</div>
                    <div class="text-xs text-gray-500">{{ $latestProduction->month ?? 'Belum ada data' }}</div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('strategic.region', $garden->region->id) }}"
                class="pptk-btn text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Kembali
                ke Wilayah</a>
            <a href="{{ route('visits.index') }}"
                class="px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Lihat
                Kunjungan</a>
            <div class="flex gap-2 sticky top-20 z-40 bg-white/70 backdrop-blur-md rounded-full px-2 py-1">
                <a href="#lokasi"
                    class="px-3 py-1 bg-green-50 text-green-700 rounded-full hover:bg-green-100 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Lokasi</a>
                <a href="#statistik"
                    class="px-3 py-1 bg-green-50 text-green-700 rounded-full hover:bg-green-100 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Statistik</a>
                <a href="#dokumentasi"
                    class="px-3 py-1 bg-green-50 text-green-700 rounded-full hover:bg-green-100 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Dokumentasi</a>
                <a href="#wawasan"
                    class="px-3 py-1 bg-green-50 text-green-700 rounded-full hover:bg-green-100 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300">Wawasan</a>
            </div>
        </div>

        <!-- Garden Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Map Section -->
            <div class="lg:col-span-2 scroll-mt-24" id="lokasi">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-green-800 mb-4">
                        <i class="fas fa-map mr-2"></i>Peta Lokasi
                    </h2>
                    <div id="map"
                        class="relative w-full h-80 md:h-96 lg:h-[28rem] rounded-xl overflow-hidden border border-gray-200 mb-4">
                        <div id="mapSkeleton"
                            class="absolute inset-0 flex items-center justify-center bg-gray-100 animate-pulse z-10">
                            <div class="text-center text-gray-500">
                                <div class="w-32 h-4 bg-gray-200 rounded mb-2"></div>
                                <div class="w-24 h-4 bg-gray-200 rounded mx-auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-700">Latitude:</span>
                            <span class="text-gray-600 ml-2">{{ $garden->latitude ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Longitude:</span>
                            <span class="text-gray-600 ml-2">{{ $garden->longitude ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Ketinggian:</span>
                            <span class="text-gray-600 ml-2">{{ $garden->elevation }} mdpl</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Curah Hujan:</span>
                            <span class="text-gray-600 ml-2">{{ $garden->rainfall }} mm/tahun</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6 scroll-mt-24" id="statistik">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-green-800 mb-4">Statistik Cepat</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Rata-rata Produktivitas</span>
                            <span class="font-bold text-green-600">{{ number_format($avgProductivity, 1) }} kg/ha</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Produksi</span>
                            <span class="font-bold text-blue-600">{{ number_format($totalProduction, 0) }} kg</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Kunjungan Dinas</span>
                            <span class="font-bold text-purple-600">{{ $visitCount }} kali</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status Kesehatan</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                {{ ucfirst($garden->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-green-800 mb-4">Informasi Tanah</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">pH Tanah:</span>
                            <span class="font-semibold ml-2">{{ $garden->soil_ph ?? '7.0' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Jenis Tanah:</span>
                            <span class="font-semibold ml-2">{{ $garden->soil_type ?? 'Andosol' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Drainase:</span>
                            <span class="font-semibold ml-2">{{ $garden->drainage ?? 'Baik' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 scroll-mt-24" id="dokumentasi">
            <h2 class="text-xl font-bold text-green-800 mb-6 gradient-text">
                <i class="fas fa-camera mr-2"></i>Dokumentasi Kebun
            </h2>
            @php
                $galleryPhotos = [];
                if ($garden->photo_path) {
                    $galleryPhotos[] = [
                        'title' => 'Foto Kebun',
                        'desc' => $garden->name,
                        'image' => asset('storage/' . $garden->photo_path),
                    ];
                }
                if ($garden->photos && $garden->photos->count()) {
                    $galleryPhotos = array_merge(
                        $galleryPhotos,
                        $garden->photos
                            ->map(function ($p, $idx) {
                                return [
                                    'title' => 'Galeri Kebun ' . ($idx + 1),
                                    'desc' => $garden->name,
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
                <p class="text-gray-600 mb-4">Belum ada dokumentasi wilayah tersedia.</p>
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
                    <i class="fas fa-times text-2xl"></i>
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
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-green-800 mb-6">
                <i class="fas fa-cogs mr-2"></i>Aksi Strategis
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Production Optimization -->
                <div class="border border-green-200 rounded-lg p-4">
                    <h3 class="font-bold text-green-700 mb-3">Optimasi Produksi</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-leaf text-green-500 mt-1 mr-2"></i>
                            <span>Pemangkasan pohon secara teratur untuk meningkatkan pertumbuhan baru</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-seedling text-green-500 mt-1 mr-2"></i>
                            <span>Pemupukan berimbang sesuai analisis tanah</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-tint text-green-500 mt-1 mr-2"></i>
                            <span>Sistem irigasi efisien untuk menjaga kelembaban optimal</span>
                        </li>
                    </ul>
                </div>

                <!-- Quality Improvement -->
                <div class="border border-blue-200 rounded-lg p-4">
                    <h3 class="font-bold text-blue-700 mb-3">Peningkatan Kualitas</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-microscope text-blue-500 mt-1 mr-2"></i>
                            <span>Pemantauan kualitas daun secara berkala</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock text-blue-500 mt-1 mr-2"></i>
                            <span>Waktu panen optimal untuk memaksimalkan kandungan antioksidan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-thermometer-half text-blue-500 mt-1 mr-2"></i>
                            <span>Kontrol suhu dan kelembaban selama pengolahan</span>
                        </li>
                    </ul>
                </div>

                <!-- Sustainability -->
                <div class="border border-yellow-200 rounded-lg p-4">
                    <h3 class="font-bold text-yellow-700 mb-3">Keberlanjutan</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-recycle text-yellow-500 mt-1 mr-2"></i>
                            <span>Pemanfaatan limbah organik sebagai pupuk kompos</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-bug text-yellow-500 mt-1 mr-2"></i>
                            <span>Pengendalian hama terpadu (IPM) untuk mengurangi pestisida kimia</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-tree text-yellow-500 mt-1 mr-2"></i>
                            <span>Penanaman pohon pelindung untuk menjaga ekosistem</span>
                        </li>
                    </ul>
                </div>

                <!-- Technology Integration -->
                <div class="border border-purple-200 rounded-lg p-4">
                    <h3 class="font-bold text-purple-700 mb-3">Integrasi Teknologi</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-mobile-alt text-purple-500 mt-1 mr-2"></i>
                            <span>Aplikasi monitoring real-time untuk petani</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-satellite text-purple-500 mt-1 mr-2"></i>
                            <span>Pemanfaatan citra satelit untuk analisis pertumbuhan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-chart-line text-purple-500 mt-1 mr-2"></i>
                            <span>Sistem prediksi hasil berbasis data historis</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Insights -->
        @if ($insights->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 scroll-mt-24" id="wawasan">
                <h2 class="text-xl font-bold text-green-800 mb-6">
                    <i class="fas fa-lightbulb mr-2"></i>Wawasan Terbaru
                </h2>
                <div class="space-y-4">
                    @foreach ($insights as $insight)
                        <div
                            class="border-l-4 border-{{ $insight->alert_level == 'high' ? 'red' : ($insight->alert_level == 'medium' ? 'yellow' : 'green') }}-500 pl-4 py-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $insight->title }}</h3>
                                    <p class="text-gray-600 text-sm mt-1">{{ $insight->description }}</p>
                                    <div class="mt-2">
                                        @php
                                            $recs = is_array($insight->recommendations)
                                                ? $insight->recommendations
                                                : json_decode($insight->recommendations, true) ?? [];
                                        @endphp
                                        @foreach ($recs as $recommendation)
                                            <span
                                                class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs mr-2 mb-1">
                                                {{ $recommendation }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-right text-sm text-gray-500">
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
            const lat = {{ $garden->latitude ?? 'null' }};
            const lng = {{ $garden->longitude ?? 'null' }};
            let centerLat = lat,
                centerLng = lng;
            @php $coordStr = $garden->coordinates ?? null; @endphp
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
                .bindPopup("<b>{{ $garden->name }}</b><br>{{ $garden->region->name }}, Indonesia")
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
