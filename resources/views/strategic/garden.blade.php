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

        <!-- Garden Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Map Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-green-800 mb-4">
                        <i class="fas fa-map mr-2"></i>Peta Lokasi
                    </h2>
                    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">Peta Interaktif</p>
                            <p class="text-sm text-gray-400">Koordinat: {{ $garden->coordinates ?? 'Belum tersedia' }}</p>
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
            <div class="space-y-6">
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

        <!-- Photos Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-green-800 mb-6">
                <i class="fas fa-camera mr-2"></i>Dokumentasi Kebun
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @for ($i = 1; $i <= 8; $i++)
                    <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                            <p class="text-xs text-gray-500">Foto {{ $i }}</p>
                        </div>
                    </div>
                @endfor
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
            <div class="bg-white rounded-lg shadow-lg p-6">
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
                                        @php($recs = is_array($insight->recommendations) ? $insight->recommendations : json_decode($insight->recommendations, true) ?? [])
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

@section('scripts')
    <script>
        // Add any interactive JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Garden detail page loaded for: {{ $garden->name }}');
        });
    </script>
@endsection
