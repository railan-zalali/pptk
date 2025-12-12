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
                <a href="#wilayah" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm">Wilayah</a>
                <a href="#aksi" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm">Aksi Strategis</a>
                <a href="{{ route('dashboard.garden') }}" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm">Dashboard Kebun</a>
                <a href="{{ route('dashboard.research') }}" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm">Dashboard Penelitian</a>
                <a href="{{ route('visits.index') }}" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-sm">Kunjungan</a>
            </div>
        </div>
    </div>
    </section>

<!-- Regional Navigation -->
<section id="wilayah" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Pilih Wilayah</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                PPTK Gambung mengelola kebun model teh di berbagai wilayah yang mewakili beragam kondisi agroklimat.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($regions as $region)
                <div class="pptk-card p-6 group hover:border-green-500 transition-all duration-300">
                    <div class="relative overflow-hidden rounded-lg mb-6">
                        <img src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt={{ urlencode('Tea plantation landscape in '.$region->name.', Indonesia, professional landscape photography, lush green tea garden') }}&image_size=landscape_4_3" 
                             alt="Kebun Teh {{ $region->name }}" 
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="text-xl font-bold">{{ $region->name }}</h3>
                            <p class="text-sm opacity-90">{{ $region->gardens->count() }} Kebun Model</p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-700">
                            <span class="material-icons text-green-600 mr-2">location_on</span>
                            <span>{{ $region->province }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <span class="material-icons text-green-600 mr-2">map</span>
                            <span>{{ $region->coordinates ?? 'Koordinat tidak tersedia' }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Ringkasan Kebun:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            @foreach ($region->gardens->take(3) as $garden)
                                <li>• {{ $garden->name }} ({{ number_format($garden->area_hectares, 1) }} ha)</li>
                            @endforeach
                            @if ($region->gardens->count() > 3)
                                <li>• dan {{ $region->gardens->count() - 3 }} lainnya...</li>
                            @endif
                        </ul>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('strategic.region', $region->id) }}" 
                           class="flex-1 text-center bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-lg font-medium hover:from-green-700 hover:to-green-800 transition-all duration-300">
                            Jelajahi Wilayah
                        </a>
                        <a href="{{ route('dashboard.garden') }}" 
                           class="px-4 py-3 rounded-lg border border-green-200 text-green-700 bg-green-50 hover:bg-green-100 font-medium">
                            Dashboard
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Strategic Overview -->
        <div id="aksi" class="mt-16">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Strategic Action Overview</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Pendekatan terintegrasi untuk meningkatkan produktivitas dan kualitas kebun model teh
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                    <span class="material-icons text-blue-600 text-3xl mb-3">science</span>
                    <h4 class="font-semibold text-gray-800 mb-2">Penelitian</h4>
                    <p class="text-sm text-gray-600">Pemuliaan varietas unggul dan pengembangan teknologi budidaya</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                    <span class="material-icons text-green-600 text-3xl mb-3">agriculture</span>
                    <h4 class="font-semibold text-gray-800 mb-2">Demonstrasi</h4>
                    <p class="text-sm text-gray-600">Praktik terbaik dalam budidaya dan pengelolaan kebun</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                    <span class="material-icons text-purple-600 text-3xl mb-3">groups</span>
                    <h4 class="font-semibold text-gray-800 mb-2">Diseminasi</h4>
                    <p class="text-sm text-gray-600">Penyuluhan dan pelatihan bagi petani serta praktisi</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg">
                    <span class="material-icons text-orange-600 text-3xl mb-3">analytics</span>
                    <h4 class="font-semibold text-gray-800 mb-2">Monitoring</h4>
                    <p class="text-sm text-gray-600">Evaluasi kinerja dan analisis data produksi</p>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
