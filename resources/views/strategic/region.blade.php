@extends('layouts.pptk')

@section('content')
    <!-- Page Header -->
    <section
        class="py-16 bg-gradient-to-r {{ $region->province == 'Jawa Barat' ? 'from-blue-600 to-blue-700' : ($region->province == 'Jawa Tengah' ? 'from-green-600 to-green-700' : 'from-purple-600 to-purple-700') }} text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Strategic Action - {{ $region->regional_name }}</h1>
                    <p class="text-xl opacity-90 max-w-3xl">
                        Informasi detail kebun model teh di {{ $region->regional_name }} dengan strategi produksi yang
                        optimal
                    </p>
                </div>
                <div class="hidden md:block">
                    <span
                        class="material-icons text-6xl opacity-20">{{ $region->province == 'Jawa Barat' ? 'terrain' : ($region->province == 'Jawa Tengah' ? 'agriculture' : 'park') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="py-4 bg-gray-100 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}"
                    class="text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">Beranda</a>
                <span class="text-gray-400 dark:text-gray-600">/</span>
                <a href="{{ route('strategic.index') }}"
                    class="text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">Strategic
                    Action</a>
                <span class="text-gray-400 dark:text-gray-600">/</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $region->regional_name }}</span>
            </nav>
        </div>
    </section>

    <!-- Regional Overview -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6">Overview
                        {{ $region->regional_name }}</h2>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p class="leading-relaxed">
                            @if ($region->province == 'Jawa Barat')
                                Wilayah Jawa Barat merupakan pusat utama pengembangan kebun model teh dengan tiga lokasi
                                strategis yang mewakili berbagai ketinggian dan kondisi mikroklimat. Kawasan ini dikenal
                                dengan tanah vulkanik yang subur dan curah hujan tinggi yang ideal untuk budidaya tanaman
                                teh.
                            @elseif($region->province == 'Jawa Tengah')
                                Jawa Tengah melalui kebun model Kaligua menyediakan representasi unik untuk budidaya teh di
                                dataran rendah. Wilayah ini penting untuk studi adaptasi perubahan iklim dan pengembangan
                                varietas toleran panas.
                            @else
                                Sumatra Selatan dengan kebun model Pagar Alam menawarkan ekosistem pegunungan yang unik
                                dengan tanah vulkanik eksotis. Wilayah ini menjadi penting untuk diversifikasi genetik dan
                                studi komparatif dengan Jawa.
                            @endif
                        </p>
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">Total Kebun</h4>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ $region->gardens->count() }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">Total Luas</h4>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($region->gardens->sum('luas_total_ha'), 1) }} ha</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    @php
                        $regionCover = $region->photo_path ?? optional($region->photos->first())->path;
                    @endphp
                    <img src="{{ $regionCover ? asset('storage/' . $regionCover) : 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=' . urlencode($region->regional_name . ' tea plantation landscape, beautiful scenery, professional photography, lush green tea gardens') . '&image_size=landscape_4_3' }}"
                        alt="Kebun Teh {{ $region->regional_name }}" loading="lazy" decoding="async" fetchpriority="low"
                        class="w-full h-80 object-cover rounded-xl shadow-lg">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strategic Actions -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4">Strategic Actions {{ $region->regional_name }}
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Strategi produksi yang diterapkan untuk meningkatkan produktivitas dan kualitas kebun model
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if ($region->province == 'Jawa Barat')
                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-blue-600 dark:text-blue-400 text-2xl mr-3">science</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pemuliaan Varietas Unggul
                            </h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Pengembangan varietas toleran terhadap penyakit blister blight</li>
                            <li>&bull; Seleksi klon unggul produktivitas tinggi (>2.000 kg/ha/tahun)</li>
                            <li>&bull; Karakterisasi genetik untuk ketahanan terhadap stres lingkungan</li>
                            <li>&bull; Uji adaptasi di berbagai ketinggian dan mikroklimat</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-green-600 dark:text-green-400 text-2xl mr-3">agriculture</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Teknologi Budidaya Modern
                            </h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Sistem irigasi tetes untuk efisiensi air 30%</li>
                            <li>&bull; Pemangkasan mekanis dengan mesin harvester</li>
                            <li>&bull; Aplikasi pupuk precision farming berbasis IoT</li>
                            <li>&bull; Integrasi sistem monitoring cuaca otomatis</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span
                                class="material-icons text-purple-600 dark:text-purple-400 text-2xl mr-3">pest_control</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pengendalian Hama Terpadu
                            </h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Monitoring populasi hama secara berkala</li>
                            <li>&bull; Penggunaan pestisida biologis ramah lingkungan</li>
                            <li>&bull; Konservasi predator alami untuk kendali biologis</li>
                            <li>&bull; Rotasi pestisida untuk menghindari resistensi</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span
                                class="material-icons text-orange-600 dark:text-orange-400 text-2xl mr-3">water_drop</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Manajemen Air & Tanah</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Konservasi tanah dengan mulsa organik</li>
                            <li>&bull; Drainase terkendali untuk menghindari genangan</li>
                            <li>&bull; Pengukuran kelembaban tanah real-time</li>
                            <li>&bull; Aplikasi kompos untuk meningkatkan kesuburan</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-teal-600 dark:text-teal-400 text-2xl mr-3">analytics</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Monitoring & Evaluasi</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Sistem monitoring produktivitas berbasis digital</li>
                            <li>&bull; Analisis kualitas hasil panen laboratorium</li>
                            <li>&bull; Pemetaan variabilitas hasil per blok</li>
                            <li>&bull; Evaluasi ekonomi dan efisiensi usahatani</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-red-600 dark:text-red-400 text-2xl mr-3">groups</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pemberdayaan Petani</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Pelatihan teknologi budidaya modern</li>
                            <li>&bull; Pendampingan oleh tenaga ahli PPTK</li>
                            <li>&bull; Kemitraan dengan kelompok tani</li>
                            <li>&bull; Transfer teknologi melalui demo plot</li>
                        </ul>
                    </div>
                @elseif($region->province == 'Jawa Tengah')
                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-blue-600 dark:text-blue-400 text-2xl mr-3">thermostat</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Adaptasi Perubahan Iklim</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Pengembangan varietas toleran suhu tinggi</li>
                            <li>&bull; Teknik budidaya untuk kondisi stres air</li>
                            <li>&bull; Penyesuaian jadwal pemupukan dan panen</li>
                            <li>&bull; Sistem irigasi efisien untuk kekeringan</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-green-600 dark:text-green-400 text-2xl mr-3">spa</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Varietas Toleran Panas</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Seleksi klon adaptif suhu tinggi</li>
                            <li>&bull; Karakterisasi fisiologi tanaman stres panas</li>
                            <li>&bull; Evaluasi kualitas hasil pada suhu tinggi</li>
                            <li>&bull; Pengembangan indikator seleksi cepat</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-purple-600 dark:text-purple-400 text-2xl mr-3">water</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Efisiensi Air</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Teknik konservasi air tanah dalam</li>
                            <li>&bull; Mulsa organik untuk retensi kelembaban</li>
                            <li>&bull; Penjadwalan irigasi berbasis cuaca</li>
                            <li>&bull; Penggunaan air greywater untuk irigasi</li>
                        </ul>
                    </div>
                @else
                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-blue-600 dark:text-blue-400 text-2xl mr-3">volcano</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tanah Vulkanik Eksotis</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Pemanfaatan kesuburan tanah vulkanik alami</li>
                            <li>&bull; Studi kandungan mineral untuk pertumbuhan teh</li>
                            <li>&bull; Manajemen drainase pada tanah berpasir</li>
                            <li>&bull; Konservasi tanah tererosi oleh hujan</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-green-600 dark:text-green-400 text-2xl mr-3">diversity_3</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Diversifikasi Genetik</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Koleksi genetik lokal Sumatra yang unik</li>
                            <li>&bull; Karakterisasi adaptasi khusus pegunungan Sumatra</li>
                            <li>&bull; Evaluasi resistensi terhadap patogen lokal</li>
                            <li>&bull; Pengembangan varietas khas Sumatra</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-purple-600 dark:text-purple-400 text-2xl mr-3">compare</span>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Studi Komparatif</h3>
                        </div>
                        <ul class="text-gray-600 dark:text-gray-400 space-y-2 text-sm">
                            <li>&bull; Perbandingan performa Jawa vs Sumatra</li>
                            <li>&bull; Analisis perbedaan kualitas hasil panen</li>
                            <li>&bull; Evaluasi preferensi pasar lokal dan ekspor</li>
                            <li>&bull; Studi daya saing komparatif</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Gardens in Region -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4">Kebun Model di {{ $region->regional_name }}
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Daftar kebun model teh yang dikelola PPTK Gambung di {{ $region->regional_name }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($region->gardens as $garden)
                    <div
                        class="pptk-card p-6 group hover:border-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-500 transition-all duration-300">
                        <div class="relative overflow-hidden rounded-lg mb-4">
                            @php
                                $gardenCover =
                                    optional($garden->photos->first())->path ?? ($garden->photo_path ?? $regionCover);
                            @endphp
                            <img src="{{ $gardenCover ? asset('storage/' . $gardenCover) : 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=' . urlencode($garden->kebun_name . ' tea garden, beautiful plantation, professional photography, lush green tea bushes') . '&image_size=landscape_4_3' }}"
                                alt="Kebun {{ $garden->kebun_name }}" loading="lazy" decoding="async" fetchpriority="low"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-lg"></div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $garden->kebun_name }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">{{ $garden->description }}</p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <span
                                    class="material-icons text-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-600 dark:text-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-400 mr-2 text-sm">location_on</span>
                                <span class="text-sm">{{ $garden->location }}</span>
                            </div>
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <span
                                    class="material-icons text-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-600 dark:text-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-400 mr-2 text-sm">straighten</span>
                                <span class="text-sm">{{ number_format($garden->luas_total_ha, 1) }} hektar</span>
                            </div>
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <span
                                    class="material-icons text-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-600 dark:text-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-400 mr-2 text-sm">calendar_today</span>
                                <span class="text-sm">Didirikan
                                    {{ \Carbon\Carbon::parse($garden->established_at)->format('Y') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('strategic.garden', ['region' => $region->id, 'garden' => $garden->id]) }}"
                            class="block w-full text-center bg-gradient-to-r from-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-600 to-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-700 text-white py-2 rounded-lg font-medium hover:from-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-700 hover:to-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-800 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-{{ $region->province == 'Jawa Barat' ? 'blue' : ($region->province == 'Jawa Tengah' ? 'green' : 'purple') }}-300">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@stop
