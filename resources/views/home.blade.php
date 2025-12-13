@extends('layouts.pptk')

@section('content')
<!-- Hero Section -->
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ $heroPhotoUrl ?? 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=Beautiful%20tea%20plantation%20landscape%20with%20rolling%20hills%2C%20lush%20green%20tea%20bushes%2C%20morning%20mist%2C%20professional%20photography%2C%20serene%20atmosphere&image_size=landscape_16_9' }}" 
             alt="Kebun Teh PPTK Gambung" 
             loading="lazy" decoding="async"
             class="w-full h-full object-cover">
        <div class="hero-overlay absolute inset-0"></div>
    </div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight animate-fade-in">
            Selamat Datang di Portal PPTK Gambung
        </h1>
        <p class="text-lg md:text-xl mb-8 leading-relaxed opacity-90">
            Sistem ini untuk mengintegrasikan data produksi dan monitoring kinerja kebun model teh secara real-time. 
            Portal informasi terpadu untuk mendukung penelitian dan pengembangan pertanian berkelanjutan.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('dashboard.garden') }}" class="pptk-btn text-lg px-8 py-4 inline-flex items-center justify-center">
                <span class="material-icons mr-2">dashboard</span>
                Akses Dashboard
            </a>
            <a href="{{ route('about') }}" class="bg-white/20 backdrop-blur-sm border-2 border-white text-white px-8 py-4 rounded-lg font-medium hover:bg-white/30 transition-all duration-300 inline-flex items-center justify-center">
                <span class="material-icons mr-2">info</span>
                Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <span class="material-icons text-3xl">expand_more</span>
    </div>
</section>

<!-- Quick Links Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Menu Utama Portal
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Akses cepat ke fitur-fitur utama portal monitoring kebun model teh
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Tentang Kebun Model -->
            <a href="{{ route('about') }}" class="pptk-card p-6 group">
                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl mb-4 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-icons text-white text-2xl">park</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Tentang Kebun Model</h3>
                <p class="text-gray-600 mb-4">Informasi lengkap tentang kebun model teh yang dikelola PPTK Gambung, sejarah, manfaat, dan peran dalam penelitian.</p>
                <div class="flex items-center text-green-600 font-medium group-hover:text-green-700">
                    <span>Selengkapnya</span>
                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>
            
            <!-- Strategic Action -->
            <a href="{{ route('strategic.index') }}" class="pptk-card p-6 group">
                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl mb-4 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-icons text-white text-2xl">map</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Strategic Action - Kebun Wilayah</h3>
                <p class="text-gray-600 mb-4">Navigasi berbasis wilayah untuk mengakses informasi detail setiap kebun model di Jawa Barat, Jawa Tengah, dan Sumatra.</p>
                <div class="flex items-center text-blue-600 font-medium group-hover:text-blue-700">
                    <span>Jelajahi Wilayah</span>
                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>
            
            <!-- Kunjungan Dinas -->
            <a href="{{ route('visits.index') }}" class="pptk-card p-6 group">
                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl mb-4 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-icons text-white text-2xl">event</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Kunjungan Dinas</h3>
                <p class="text-gray-600 mb-4">Kelola dan pantau jadwal kunjungan dinas ke kebun model. Lihat timeline kunjungan dan jadwalkan kunjungan baru.</p>
                <div class="flex items-center text-purple-600 font-medium group-hover:text-purple-700">
                    <span>Lihat Jadwal</span>
                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>
            
            <!-- Dashboard Kebun Model -->
            <a href="{{ route('dashboard.garden') }}" class="pptk-card p-6 group">
                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl mb-4 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-icons text-white text-2xl">analytics</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Dashboard Kebun Model</h3>
                <p class="text-gray-600 mb-4">Dashboard interaktif dengan chart produktivitas, filter per kebun, dan parameter pendukung untuk monitoring kinerja.</p>
                <div class="flex items-center text-orange-600 font-medium group-hover:text-orange-700">
                    <span>Akses Dashboard</span>
                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>
            
            <!-- Dashboard Penelitian -->
            <a href="{{ route('dashboard.research') }}" class="pptk-card p-6 group">
                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl mb-4 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-icons text-white text-2xl">science</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Dashboard Bagian Penelitian</h3>
                <p class="text-gray-600 mb-4">Dashboard khusus penelitian dengan analisis komparatif antar kebun dan rule-based insights untuk pengambilan keputusan.</p>
                <div class="flex items-center text-teal-600 font-medium group-hover:text-teal-700">
                    <span>Lihat Analisis</span>
                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>
            
            <!-- Login/Register -->
            @guest
                <a href="{{ route('login') }}" class="pptk-card p-6 group">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl mb-4 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-icons text-white text-2xl">login</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Masuk / Daftar</h3>
                    <p class="text-gray-600 mb-4">Akses dashboard dan fitur eksklusif dengan masuk ke akun Anda atau daftar sebagai pengguna baru.</p>
                    <div class="flex items-center text-gray-600 font-medium group-hover:text-gray-700">
                        <span>Masuk Sekarang</span>
                        <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </a>
            @else
                <div class="pptk-card p-6">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl mb-4">
                        <span class="material-icons text-white text-2xl">person</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 mb-4">Anda telah login sebagai {{ Auth::user()->role }}. Akses dashboard untuk memantau kinerja kebun model.</p>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('dashboard.garden') }}" class="pptk-btn text-center text-sm">Dashboard Kebun Model</a>
                        <a href="{{ route('dashboard.research') }}" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-center text-sm hover:bg-green-200 transition-colors">Dashboard Penelitian</a>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</section>

<!-- Features Overview -->
<section class="py-16 bg-gradient-to-br from-green-50 to-green-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Fitur Unggulan Portal
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Teknologi modern untuk monitoring dan analisis kebun model teh
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-3xl">monitoring</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Monitoring Real-time</h3>
                <p class="text-gray-600">Pantau kinerja kebun model secara real-time dengan dashboard interaktif</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-3xl">analytics</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Analisis Cerdas</h3>
                <p class="text-gray-600">Rule-based algorithm untuk memberikan insight dan rekomendasi</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-3xl">devices</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Responsive Design</h3>
                <p class="text-gray-600">Akses portal dari berbagai perangkat dengan tampilan optimal</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-3xl">security</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Aman & Terpercaya</h3>
                <p class="text-gray-600">Sistem keamanan berlapis dengan autentikasi Laravel Breeze</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-gradient-to-r from-green-600 to-green-700 text-white">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Siap Memulai Monitoring Kebun Model?
        </h2>
        <p class="text-xl mb-8 opacity-90">
            Bergabunglah dengan komunitas peneliti dan praktisi pertanian teh untuk meningkatkan produktivitas dan kualitas kebun model.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" class="bg-white text-green-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                    <span class="material-icons mr-2">person_add</span>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-700 transition-colors inline-flex items-center justify-center">
                    <span class="material-icons mr-2">login</span>
                    Masuk
                </a>
            @else
                <a href="{{ route('dashboard.garden') }}" class="bg-white text-green-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                    <span class="material-icons mr-2">dashboard</span>
                    Ke Dashboard
                </a>
            @endguest
        </div>
    </div>
</section>
@stop

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
    
    .pptk-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(34, 139, 34, 0.1);
    }
    
    .pptk-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(34, 139, 34, 0.2);
    }
</style>
@endpush
