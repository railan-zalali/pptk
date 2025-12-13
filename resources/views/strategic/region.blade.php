@extends('layouts.pptk')

@section('content')
    <!-- Page Header -->
    <section
        class="py-16 bg-gradient-to-r {{ $region->name == 'Jawa Barat' ? 'from-blue-600 to-blue-700' : ($region->name == 'Jawa Tengah' ? 'from-green-600 to-green-700' : 'from-purple-600 to-purple-700') }} text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Strategic Action - {{ $region->name }}</h1>
                    <p class="text-xl opacity-90 max-w-3xl">
                        Informasi detail kebun model teh di wilayah {{ $region->name }} dengan strategi produksi yang
                        optimal
                    </p>
                </div>
                <div class="hidden md:block">
                    <span
                        class="material-icons text-6xl opacity-20">{{ $region->name == 'Jawa Barat' ? 'terrain' : ($region->name == 'Jawa Tengah' ? 'agriculture' : 'park') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="py-4 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-green-600">Beranda</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('strategic.index') }}" class="text-gray-500 hover:text-green-600">Strategic Action</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-medium">{{ $region->name }}</span>
            </nav>
        </div>
    </section>

    <!-- Regional Overview -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Overview Wilayah {{ $region->name }}</h2>
                    <div class="space-y-4 text-gray-700">
                        <p class="leading-relaxed">
                            @if ($region->name == 'Jawa Barat')
                                Wilayah Jawa Barat merupakan pusat utama pengembangan kebun model teh dengan tiga lokasi
                                strategis yang mewakili berbagai ketinggian dan kondisi mikroklimat. Kawasan ini dikenal
                                dengan tanah vulkanik yang subur dan curah hujan tinggi yang ideal untuk budidaya tanaman
                                teh.
                            @elseif($region->name == 'Jawa Tengah')
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
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-1">Total Kebun</h4>
                                <p class="text-2xl font-bold text-green-600">{{ $region->gardens->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-1">Total Luas</h4>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ number_format($region->gardens->sum('area_hectares'), 1) }} ha</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    @php
                        $regionCover = $region->photo_path ?? optional($region->photos->first())->path;
                    @endphp
                    <img src="{{ $regionCover ? asset('storage/' . $regionCover) : 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=' . urlencode($region->name . ' tea plantation landscape, beautiful scenery, professional photography, lush green tea gardens') . '&image_size=landscape_4_3' }}"
                        alt="Kebun Teh {{ $region->name }}" loading="lazy" decoding="async" fetchpriority="low"
                        class="w-full h-80 object-cover rounded-xl shadow-lg">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strategic Actions -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Strategic Actions {{ $region->name }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Strategi produksi yang diterapkan untuk meningkatkan produktivitas dan kualitas kebun model
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if ($region->name == 'Jawa Barat')
                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-blue-600 text-2xl mr-3">science</span>
                            <h3 class="text-lg font-semibold text-gray-800">Pemuliaan Varietas Unggul</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Pengembangan varietas toleran terhadap penyakit blister blight</li>
                            <li>• Seleksi klon unggul produktivitas tinggi (>2.000 kg/ha/tahun)</li>
                            <li>• Karakterisasi genetik untuk ketahanan terhadap stres lingkungan</li>
                            <li>• Uji adaptasi di berbagai ketinggian dan mikroklimat</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-green-600 text-2xl mr-3">agriculture</span>
                            <h3 class="text-lg font-semibold text-gray-800">Teknologi Budidaya Modern</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Sistem irigasi tetes untuk efisiensi air 30%</li>
                            <li>• Pemangkasan mekanis dengan mesin harvester</li>
                            <li>• Aplikasi pupuk precision farming berbasis IoT</li>
                            <li>• Integrasi sistem monitoring cuaca otomatis</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-purple-600 text-2xl mr-3">pest_control</span>
                            <h3 class="text-lg font-semibold text-gray-800">Pengendalian Hama Terpadu</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Monitoring populasi hama secara berkala</li>
                            <li>• Penggunaan pestisida biologis ramah lingkungan</li>
                            <li>• Konservasi predator alami untuk kendali biologis</li>
                            <li>• Rotasi pestisida untuk menghindari resistensi</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-orange-600 text-2xl mr-3">water_drop</span>
                            <h3 class="text-lg font-semibold text-gray-800">Manajemen Air & Tanah</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Konservasi tanah dengan mulsa organik</li>
                            <li>• Drainase terkendali untuk menghindari genangan</li>
                            <li>• Pengukuran kelembaban tanah real-time</li>
                            <li>• Aplikasi kompos untuk meningkatkan kesuburan</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-teal-600 text-2xl mr-3">analytics</span>
                            <h3 class="text-lg font-semibold text-gray-800">Monitoring & Evaluasi</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Sistem monitoring produktivitas berbasis digital</li>
                            <li>• Analisis kualitas hasil panen laboratorium</li>
                            <li>• Pemetaan variabilitas hasil per blok</li>
                            <li>• Evaluasi ekonomi dan efisiensi usahatani</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-red-600 text-2xl mr-3">groups</span>
                            <h3 class="text-lg font-semibold text-gray-800">Pemberdayaan Petani</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Pelatihan teknologi budidaya modern</li>
                            <li>• Pendampingan oleh tenaga ahli PPTK</li>
                            <li>• Kemitraan dengan kelompok tani</li>
                            <li>• Transfer teknologi melalui demo plot</li>
                        </ul>
                    </div>
                @elseif($region->name == 'Jawa Tengah')
                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-blue-600 text-2xl mr-3">thermostat</span>
                            <h3 class="text-lg font-semibold text-gray-800">Adaptasi Perubahan Iklim</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Pengembangan varietas toleran suhu tinggi</li>
                            <li>• Teknik budidaya untuk kondisi stres air</li>
                            <li>• Penyesuaian jadwal pemupukan dan panen</li>
                            <li>• Sistem irigasi efisien untuk kekeringan</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-green-600 text-2xl mr-3">spa</span>
                            <h3 class="text-lg font-semibold text-gray-800">Varietas Toleran Panas</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Seleksi klon adaptif suhu tinggi</li>
                            <li>• Karakterisasi fisiologi tanaman stres panas</li>
                            <li>• Evaluasi kualitas hasil pada suhu tinggi</li>
                            <li>• Pengembangan indikator seleksi cepat</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-purple-600 text-2xl mr-3">water</span>
                            <h3 class="text-lg font-semibold text-gray-800">Efisiensi Air</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Teknik konservasi air tanah dalam</li>
                            <li>• Mulsa organik untuk retensi kelembaban</li>
                            <li>• Penjadwalan irigasi berbasis cuaca</li>
                            <li>• Penggunaan air greywater untuk irigasi</li>
                        </ul>
                    </div>
                @else
                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-blue-600 text-2xl mr-3">volcano</span>
                            <h3 class="text-lg font-semibold text-gray-800">Tanah Vulkanik Eksotis</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Pemanfaatan kesuburan tanah vulkanik alami</li>
                            <li>• Studi kandungan mineral untuk pertumbuhan teh</li>
                            <li>• Manajemen drainase pada tanah berpasir</li>
                            <li>• Konservasi tanah tererosi oleh hujan</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-green-600 text-2xl mr-3">diversity_3</span>
                            <h3 class="text-lg font-semibold text-gray-800">Diversifikasi Genetik</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Koleksi genetik lokal Sumatra yang unik</li>
                            <li>• Karakterisasi adaptasi khusus pegunungan Sumatra</li>
                            <li>• Evaluasi resistensi terhadap patogen lokal</li>
                            <li>• Pengembangan varietas khas Sumatra</li>
                        </ul>
                    </div>

                    <div class="pptk-card p-6">
                        <div class="flex items-center mb-4">
                            <span class="material-icons text-purple-600 text-2xl mr-3">compare</span>
                            <h3 class="text-lg font-semibold text-gray-800">Studi Komparatif</h3>
                        </div>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <li>• Perbandingan performa Jawa vs Sumatra</li>
                            <li>• Analisis perbedaan kualitas hasil panen</li>
                            <li>• Evaluasi preferensi pasar lokal dan ekspor</li>
                            <li>• Studi daya saing komparatif</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Gardens in Region -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Kebun Model di {{ $region->name }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Daftar kebun model teh yang dikelola PPTK Gambung di wilayah {{ $region->name }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($region->gardens as $garden)
                    <div
                        class="pptk-card p-6 group hover:border-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-500 transition-all duration-300">
                        <div class="relative overflow-hidden rounded-lg mb-4">
                            @php
                                $gardenCover =
                                    optional($garden->photos->first())->path ?? ($garden->photo_path ?? $regionCover);
                            @endphp
                            <img src="{{ $gardenCover ? asset('storage/' . $gardenCover) : 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=' . urlencode($garden->name . ' tea garden, beautiful plantation, professional photography, lush green tea bushes') . '&image_size=landscape_4_3' }}"
                                alt="Kebun {{ $garden->name }}" loading="lazy" decoding="async" fetchpriority="low"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-lg"></div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $garden->name }}</h3>
                        <p class="text-gray-600 mb-4 text-sm">{{ $garden->description }}</p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-700">
                                <span
                                    class="material-icons text-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-600 mr-2 text-sm">location_on</span>
                                <span class="text-sm">{{ $garden->location }}</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <span
                                    class="material-icons text-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-600 mr-2 text-sm">straighten</span>
                                <span class="text-sm">{{ number_format($garden->area_hectares, 1) }} hektar</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <span
                                    class="material-icons text-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-600 mr-2 text-sm">calendar_today</span>
                                <span class="text-sm">Didirikan
                                    {{ \Carbon\Carbon::parse($garden->established_at)->format('Y') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('strategic.garden', ['region' => $region->id, 'garden' => $garden->id]) }}"
                            class="block w-full text-center bg-gradient-to-r from-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-600 to-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-700 text-white py-2 rounded-lg font-medium hover:from-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-700 hover:to-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-800 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-{{ $region->name == 'Jawa Barat' ? 'blue' : ($region->name == 'Jawa Tengah' ? 'green' : 'purple') }}-300">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@stop
