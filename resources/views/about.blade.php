@extends('layouts.pptk')

@section('content')
    <!-- Page Header -->
    <section class="py-16 bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $page->title ?? 'Tentang Kebun Model' }}</h1>
            <p class="text-xl opacity-90 max-w-3xl mx-auto">
                {{ $page->subtitle ?? 'Memahami peran penting kebun model teh dalam pengembangan pertanian berkelanjutan dan penelitian agrikultur' }}
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Overview Card -->
            <div class="pptk-card p-8 mb-12">
                <div class="flex items-center mb-6">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                        <span class="material-icons text-white text-2xl">park</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Overview Kebun Model Teh</h2>
                        <p class="text-gray-600">Pusat Penelitian Teh dan Kina (PPTK) Gambung</p>
                    </div>
                </div>
                @if (!empty($page?->overview_html))
                    <div class="prose max-w-none">{!! $page->overview_html !!}</div>
                @else
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kebun model teh merupakan unit penelitian dan pengembangan yang strategis dalam mendukung program
                        pemuliaan dan budidaya tanaman teh di Indonesia. PPTK Gambung mengelola beberapa kebun model
                        tersebar di berbagai wilayah untuk mendukung penelitian terapan dan demonstrasi teknologi.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Dengan pendekatan kebun model, PPTK Gambung dapat menguji berbagai teknologi budidaya, evaluasi
                        genetik, serta praktik pengelolaan kebun yang optimal sebelum diaplikasikan secara luas kepada
                        petani teh.
                    </p>
                @endif
            </div>

            <!-- Accordion Sections -->
            <div class="space-y-6">
                <!-- Sejarah Kebun Model -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button
                        class="accordion-header w-full px-6 py-4 text-left bg-gray-50 hover:bg-green-50 transition-colors duration-200 flex items-center justify-between"
                        onclick="toggleAccordion('sejarah')">
                        <div class="flex items-center">
                            <span class="material-icons text-green-600 mr-3">history</span>
                            <h3 class="text-lg font-semibold text-gray-800">Sejarah Kebun Model</h3>
                        </div>
                        <span class="material-icons text-gray-500 accordion-icon" id="sejarah-icon">expand_more</span>
                    </button>
                    <div class="accordion-content hidden px-6 py-4 bg-white" id="sejarah-content">
                        @if (!empty($page?->sejarah_html))
                            <div class="prose max-w-none text-gray-700">{!! $page->sejarah_html !!}</div>
                        @else
                            <div class="prose text-gray-700">
                                <p class="mb-4">
                                    Konsep kebun model teh di Indonesia dimulai sejak tahun 1980-an sebagai bagian dari
                                    program intensifikasi pertanian teh. PPTK Gambung mulai membangun kebun model pertama di
                                    kawasan Malabar, Bandung.
                                </p>
                                <ul class="list-disc pl-6 space-y-2 mb-4">
                                    <li><strong>1985:</strong> Pembangunan kebun model pertama di Malabar dengan luas 150
                                        hektar</li>
                                    <li><strong>1988:</strong> Pengembangan kebun model Sedep di Garut untuk uji varietas
                                        unggul</li>
                                    <li><strong>1990:</strong> Pembangunan kebun model Ranca Bali dengan sistem irigasi
                                        modern</li>
                                    <li><strong>1992:</strong> Ekspansi kebun model Kaligua di Brebes untuk dataran rendah
                                    </li>
                                    <li><strong>2000-an:</strong> Integrasi teknologi precision agriculture dan monitoring
                                        digital</li>
                                </ul>
                                <p>
                                    Perkembangan ini menunjukkan komitmen PPTK Gambung dalam mendukung modernisasi pertanian
                                    teh Indonesia melalui pendekatan berbasis penelitian dan demonstrasi.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tujuan PPTK -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button
                        class="accordion-header w-full px-6 py-4 text-left bg-gray-50 hover:bg-green-50 transition-colors duration-200 flex items-center justify-between"
                        onclick="toggleAccordion('tujuan')">
                        <div class="flex items-center">
                            <span class="material-icons text-green-600 mr-3">flag</span>
                            <h3 class="text-lg font-semibold text-gray-800">Tujuan PPTK Gambung</h3>
                        </div>
                        <span class="material-icons text-gray-500 accordion-icon" id="tujuan-icon">expand_more</span>
                    </button>
                    <div class="accordion-content hidden px-6 py-4 bg-white" id="tujuan-content">
                        @if (!empty($page?->tujuan_html))
                            <div class="prose max-w-none text-gray-700">{!! $page->tujuan_html !!}</div>
                        @else
                            <div class="prose text-gray-700">
                                <p class="mb-4">
                                    Pusat Penelitian Teh dan Kina (PPTK) Gambung memiliki peran strategis dalam pengembangan
                                    komoditas teh dan kina di Indonesia dengan tujuan utama:
                                </p>
                                <div class="grid md:grid-cols-2 gap-6 mb-4">
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-green-800 mb-2">Penelitian & Pengembangan</h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Pemuliaan tanaman teh unggul</li>
                                            <li>• Pengembangan teknologi budidaya</li>
                                            <li>• Studi genetik dan molekuler</li>
                                            <li>• Evaluasi adaptasi iklim</li>
                                        </ul>
                                    </div>
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-blue-800 mb-2">Diseminasi Teknologi</h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Demonstrasi kebun model</li>
                                            <li>• Pelatihan petani</li>
                                            <li>• Penyuluhan teknis</li>
                                            <li>• Publikasi ilmiah</li>
                                        </ul>
                                    </div>
                                    <div class="bg-purple-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-purple-800 mb-2">Monitoring & Evaluasi</h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Kinerja produksi kebun</li>
                                            <li>• Kualitas hasil panen</li>
                                            <li>• Parameter lingkungan</li>
                                            <li>• Analisis ekonomi</li>
                                        </ul>
                                    </div>
                                    <div class="bg-orange-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-orange-800 mb-2">Kerja Sama</h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Kemitraan dengan universitas</li>
                                            <li>• Kolaborasi internasional</li>
                                            <li>• Kemitraan industri</li>
                                            <li>• Jaringan peneliti</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Manfaat Kebun Model -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button
                        class="accordion-header w-full px-6 py-4 text-left bg-gray-50 hover:bg-green-50 transition-colors duration-200 flex items-center justify-between"
                        onclick="toggleAccordion('manfaat')">
                        <div class="flex items-center">
                            <span class="material-icons text-green-600 mr-3">emoji_events</span>
                            <h3 class="text-lg font-semibold text-gray-800">Manfaat Kebun Model</h3>
                        </div>
                        <span class="material-icons text-gray-500 accordion-icon" id="manfaat-icon">expand_more</span>
                    </button>
                    <div class="accordion-content hidden px-6 py-4 bg-white" id="manfaat-content">
                        @if (!empty($page?->manfaat_html))
                            <div class="prose max-w-none text-gray-700">{!! $page->manfaat_html !!}</div>
                        @else
                            <div class="prose text-gray-700">
                                <p class="mb-4">
                                    Kebun model teh memberikan berbagai manfaat strategis bagi pengembangan industri
                                    pertanian teh Indonesia:
                                </p>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <span class="material-icons text-green-600 mr-3 mt-1">science</span>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Validasi Teknologi</h4>
                                            <p class="text-gray-600">Menguji efektivitas teknologi budidaya baru sebelum
                                                diaplikasikan secara luas</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="material-icons text-blue-600 mr-3 mt-1">school</span>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Edukasi & Pelatihan</h4>
                                            <p class="text-gray-600">Menyediakan tempat praktik dan pembelajaran bagi
                                                petani, mahasiswa, dan peneliti</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="material-icons text-purple-600 mr-3 mt-1">trending_up</span>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Peningkatan Produktivitas</h4>
                                            <p class="text-gray-600">Demonstrasi praktik terbaik untuk meningkatkan hasil
                                                panen dan kualitas</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="material-icons text-orange-600 mr-3 mt-1">eco</span>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Keberlanjutan Lingkungan</h4>
                                            <p class="text-gray-600">Mengembangkan praktik pertanian berkelanjutan yang
                                                ramah lingkungan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="material-icons text-teal-600 mr-3 mt-1">attach_money</span>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Efisiensi Ekonomi</h4>
                                            <p class="text-gray-600">Mengoptimalkan penggunaan sumber daya dan biaya
                                                produksi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lokasi Kebun Model -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button
                        class="accordion-header w-full px-6 py-4 text-left bg-gray-50 hover:bg-green-50 transition-colors duration-200 flex items-center justify-between"
                        onclick="toggleAccordion('lokasi')">
                        <div class="flex items-center">
                            <span class="material-icons text-green-600 mr-3">map</span>
                            <h3 class="text-lg font-semibold text-gray-800">Lokasi Kebun Model</h3>
                        </div>
                        <span class="material-icons text-gray-500 accordion-icon" id="lokasi-icon">expand_more</span>
                    </button>
                    <div class="accordion-content hidden px-6 py-4 bg-white" id="lokasi-content">
                        @if (!empty($page?->lokasi_html))
                            <div class="prose max-w-none text-gray-700">{!! $page->lokasi_html !!}</div>
                        @else
                            <div class="prose text-gray-700">
                                <p class="mb-4">
                                    PPTK Gambung mengelola kebun model tersebar di berbagai wilayah Indonesia yang mewakili
                                    berbagai kondisi agroklimat:
                                </p>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="border border-green-200 rounded-lg p-4">
                                        <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                                            <span class="material-icons mr-2">location_on</span>
                                            Jawa Barat
                                        </h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li><strong>Malabar:</strong> Ketinggian 1.200-1.500 mdpl, luas 150 hektar</li>
                                            <li><strong>Ranca Bali:</strong> Ketinggian 1.000-1.300 mdpl, luas 120 hektar
                                            </li>
                                            <li><strong>Sedep:</strong> Ketinggian 800-1.100 mdpl, luas 95 hektar</li>
                                        </ul>
                                    </div>
                                    <div class="border border-blue-200 rounded-lg p-4">
                                        <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                            <span class="material-icons mr-2">location_on</span>
                                            Jawa Tengah
                                        </h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li><strong>Kaligua:</strong> Ketinggian 200-400 mdpl, luas 110 hektar</li>
                                            <li>Representasi dataran rendah</li>
                                            <li>Fokus pada adaptasi iklim panas</li>
                                        </ul>
                                    </div>
                                    <div class="border border-purple-200 rounded-lg p-4">
                                        <h4 class="font-semibold text-purple-800 mb-2 flex items-center">
                                            <span class="material-icons mr-2">location_on</span>
                                            Sumatra
                                        </h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li><strong>Pagar Alam:</strong> Ketinggian 1.400-1.700 mdpl, luas 180 hektar
                                            </li>
                                            <li>Kebun model terluas</li>
                                            <li>Ekosistem unik Sumatra</li>
                                        </ul>
                                    </div>
                                    <div class="border border-orange-200 rounded-lg p-4">
                                        <h4 class="font-semibold text-orange-800 mb-2 flex items-center">
                                            <span class="material-icons mr-2">insights</span>
                                            Signifikansi
                                        </h4>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Mewakili 3 zona agroklimat utama</li>
                                            <li>• Total luas lebih dari 650 hektar</li>
                                            <li>• Ribuan genotipe teh tersedia</li>
                                            <li>• Database iklim dan produksi lengkap</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@push('scripts')
    <script>
        function toggleAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            const header = content.previousElementSibling;

            // Toggle content visibility
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.textContent = 'expand_less';
                header.classList.add('bg-green-50');
                header.classList.remove('bg-gray-50');
            } else {
                content.classList.add('hidden');
                icon.textContent = 'expand_more';
                header.classList.remove('bg-green-50');
                header.classList.add('bg-gray-50');
            }

            // Close other accordions
            document.querySelectorAll('.accordion-content').forEach(otherContent => {
                if (otherContent.id !== id + '-content' && !otherContent.classList.contains('hidden')) {
                    otherContent.classList.add('hidden');
                    const otherIcon = otherContent.previousElementSibling.querySelector('.accordion-icon');
                    const otherHeader = otherContent.previousElementSibling;
                    otherIcon.textContent = 'expand_more';
                    otherHeader.classList.remove('bg-green-50');
                    otherHeader.classList.add('bg-gray-50');
                }
            });
        }
    </script>
@endpush
