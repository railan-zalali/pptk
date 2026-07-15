@extends('layouts.pptk')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[100dvh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0 bg-gray-900">
            <img src="{{ $heroPhotoUrl ?? asset('images/gambar_1.jpg') }}"
                alt="Kebun Teh PPTK Gambung" loading="lazy" decoding="async" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>
        </div>

        <div class="relative z-10 text-center text-white px-4 max-w-5xl mx-auto py-20">
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 flex items-center justify-center animate-fade-in-up drop-shadow-2xl">
                    <img src="{{ asset('images/pptk.png') }}" class="w-full h-auto drop-shadow-2xl" alt="Logo PPTK" />
                </div>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight animate-fade-in text-shadow-lg">
                Selamat Datang di <span class="text-green-400">Portal PPTK Gambung</span>
            </h1>
            <p class="text-lg md:text-xl mb-10 leading-relaxed opacity-90 max-w-3xl mx-auto text-shadow">
                Sistem integrasi data produksi dan monitoring kinerja kebun model teh secara real-time.
                Mendukung penelitian dan pengembangan pertanian berkelanjutan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up delay-200">
                <a href="{{ route('login') }}"
                    class="pptk-btn px-8 py-4 rounded-full bg-green-600 hover:bg-green-700 text-white font-semibold shadow-lg hover:shadow-green-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <span class="material-icons">login</span>
                    <span>Masuk Portal</span>
                </a>
                <a href="{{ route('visits.index') }}"
                    class="px-8 py-4 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold border border-white/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <span class="material-icons">calendar_month</span>
                    <span>Jadwal Kunjungan</span>
                </a>
            </div>
        </div>

        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce hidden md:block">
            <a href="#about" class="text-white opacity-80 hover:opacity-100 transition-opacity">
                <span class="material-icons text-4xl">keyboard_arrow_down</span>
            </a>
        </div>
    </section>

    <!-- About Section (Merged) -->
    <section id="about" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6">Tentang Kebun Model</h2>
                    <div class="h-1 w-20 bg-green-500 rounded-full mb-6"></div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                        Pusat Penelitian Teh dan Kina (PPTK) Gambung mengembangkan konsep Kebun Model sebagai
                        laboratorium lapangan yang mengintegrasikan praktik agrikultur terbaik dengan teknologi terkini.
                        Tujuan utama kami adalah meningkatkan produktivitas dan kualitas teh nasional melalui riset
                        aplikatif dan pengembangan varietas unggul.
                    </p>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-8">
                        Dengan luas lahan lebih dari 1000 hektar, kami mengelola berbagai klon teh unggulan dan
                        menerapkan sistem monitoring digital untuk memastikan setiap langkah budidaya terukur dan efisien.
                    </p>
                    <a href="{{ route('about') }}"
                        class="text-green-600 font-semibold hover:text-green-700 inline-flex items-center">
                        Baca Selengkapnya
                        <span class="material-icons ml-1">arrow_forward</span>
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-green-100 dark:bg-green-900/30 rounded-xl transform rotate-3"></div>
                    <img src="{{ asset('images/gambar_2.jpg') }}"
                        alt="Gedung PPTK Gambung" class="relative rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-4">Fitur Utama</h2>
                <div class="h-1 w-20 bg-green-500 mx-auto rounded-full"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Platform komprehensif untuk pengelolaan dan monitoring aktivitas perkebunan teh secara digital dan
                    terintegrasi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div
                    class="pptk-card p-8 hover:shadow-xl transition-all duration-300 group dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-icons text-3xl text-green-600 dark:text-green-400">trending_up</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">Monitoring Produksi</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Pantau data produksi teh harian, mingguan, dan bulanan dengan visualisasi grafik yang interaktif dan
                        informatif.
                    </p>
                </div>

                <!-- Card 2 -->
                <div
                    class="pptk-card p-8 hover:shadow-xl transition-all duration-300 group dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-icons text-3xl text-blue-600 dark:text-blue-400">map</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">Pemetaan Kebun</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Visualisasi lokasi kebun, afdeling, dan blok dalam peta digital untuk memudahkan manajemen aset
                        lahan.
                    </p>
                </div>

                <!-- Card 3 -->
                <div
                    class="pptk-card p-8 hover:shadow-xl transition-all duration-300 group dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-icons text-3xl text-orange-600 dark:text-orange-400">groups</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">Manajemen Kunjungan</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Jadwalkan dan kelola kunjungan tamu atau peneliti dengan sistem booking yang terintegrasi dan
                        transparan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Peta Lokasi Kebun -->
    <section id="peta-kebun" class="py-20 bg-white dark:bg-gray-800 border-t dark:border-gray-700 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-4">Peta Lokasi Kebun</h2>
                <div class="h-1 w-20 bg-green-500 mx-auto rounded-full"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Sebaran kebun model dan pengembangan yang dikelola PPTK Gambung.
                </p>
            </div>

            @if (count($gardensForMap) > 0)
                <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white dark:border-gray-700" style="height: 450px;">
                    <div id="home-map" style="height: 100%; width: 100%;"></div>
                </div>
                <div class="mt-4 flex flex-wrap gap-4 justify-center text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Kebun Model
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span> Kebun Pengembangan
                    </span>
                </div>
            @else
                <div class="text-center py-16 text-gray-400 dark:text-gray-500">
                    <span class="material-icons text-5xl mb-3 block">map</span>
                    <p>Koordinat kebun belum diatur. Silakan tambahkan koordinat di panel Admin.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Stats Preview Section -->
    <section class="py-20 bg-white dark:bg-gray-800 transition-colors duration-300 border-t dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white dark:border-gray-700">
                        <img src="{{ $statsPhotoUrl ?? asset('images/gambar_3.jpg') }}"
                            alt="Dashboard Analytics"
                            class="w-full h-auto transform hover:scale-105 transition-transform duration-700">
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-6">
                        Data Real-time untuk <br>
                        <span class="text-green-600 dark:text-green-400">Keputusan yang Lebih Baik</span>
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                        Dapatkan wawasan mendalam tentang performa kebun melalui dashboard analitik kami.
                        Pantau pencapaian target, identifikasi tren produksi, dan optimalkan strategi operasional.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <span class="material-icons text-green-500 mr-3">check_circle</span>
                            <span>Update data harian dari lapangan</span>
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <span class="material-icons text-green-500 mr-3">check_circle</span>
                            <span>Laporan kinerja otomatis</span>
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                            <span class="material-icons text-green-500 mr-3">check_circle</span>
                            <span>Akses mudah dari berbagai perangkat</span>
                        </li>
                    </ul>
                    <a href="{{ route('about') }}"
                        class="inline-flex items-center text-green-600 dark:text-green-400 font-semibold hover:text-green-700 dark:hover:text-green-300 transition-colors">
                        Pelajari Lebih Lanjut
                        <span class="material-icons ml-2">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-green-600 dark:bg-green-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle, #ffffff 2px, transparent 2px); background-size: 30px 30px;">
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap untuk Memulai?</h2>
            <p class="text-xl mb-10 opacity-90">
                Bergabunglah dengan transformasi digital PPTK Gambung untuk masa depan perkebunan teh yang lebih maju.
            </p>
            <a href="{{ route('login') }}"
                class="bg-white text-green-700 hover:bg-gray-100 font-bold py-4 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 inline-flex items-center gap-2">
                <span class="material-icons">account_circle</span>
                <span>Masuk ke Dashboard</span>
            </a>
        </div>
    </section>
@endsection

@push('styles')
@if (count($gardensForMap) > 0)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endif
@endpush

@push('scripts')
@if (count($gardensForMap) > 0)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const gardensData = @json($gardensForMap);

    // Custom icon maker
    function makeIcon(color) {
        return L.divIcon({
            html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.4)"></div>`,
            className: '',
            iconSize: [14, 14],
            iconAnchor: [7, 7],
        });
    }

    const map = L.map('home-map', { scrollWheelZoom: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    const bounds = [];
    gardensData.forEach(garden => {
        const parts = garden.coordinates.split(',').map(s => parseFloat(s.trim()));
        if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
            const color  = garden.type === 'Model' ? '#22c55e' : '#3b82f6';
            const marker = L.marker(parts, { icon: makeIcon(color) }).addTo(map);
            marker.bindPopup(
                `<b>${garden.name}</b><br>` +
                `<span style="color:#6b7280;font-size:12px;">${garden.type}${garden.location ? ' — ' + garden.location : ''}</span>`
            );
            bounds.push(parts);
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [40, 40] });
    }
</script>
@endif
@endpush
