@extends('layouts.pptk')

@section('content')
<!-- Page Header -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Strategic Action - Kebun Wilayah</h1>
        <p class="text-xl opacity-90 max-w-3xl mx-auto">
            Navigasi wilayah untuk mengakses informasi detail setiap kebun model teh di berbagai daerah Indonesia
        </p>
    </div>
</section>

<!-- Regional Navigation -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Pilih Wilayah</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                PPTK Gambung mengelola kebun model teh di tiga wilayah utama Indonesia yang mewakili berbagai kondisi agroklimat
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Jawa Barat -->
            <div class="pptk-card p-6 group hover:border-blue-500 transition-all duration-300">
                <div class="relative overflow-hidden rounded-lg mb-6">
                    <img src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=Beautiful%20tea%20plantation%20in%20West%20Java%2C%20Indonesia%2C%20rolling%20hills%20covered%20with%20tea%20bushes%2C%20morning%20mist%2C%20professional%20landscape%20photography&image_size=landscape_4_3" 
                         alt="Kebun Teh Jawa Barat" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <h3 class="text-xl font-bold">Jawa Barat</h3>
                        <p class="text-sm opacity-90">3 Kebun Model</p>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-blue-600 mr-2">location_on</span>
                        <span>Bandung, Garut</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-blue-600 mr-2">terrain</span>
                        <span>Ketinggian 800-1.500 mdpl</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-blue-600 mr-2">eco</span>
                        <span>Iklim tropis pegunungan</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Kebun Model:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Malabar (150 ha)</li>
                        <li>• Ranca Bali (120 ha)</li>
                        <li>• Sedep (95 ha)</li>
                    </ul>
                </div>
                
                <a href="{{ route('strategic.region', ['region' => 'jawa-barat']) }}" 
                   class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-300">
                    <span class="material-icons mr-2">arrow_forward</span>
                    Jelajahi Wilayah
                </a>
            </div>
            
            <!-- Jawa Tengah -->
            <div class="pptk-card p-6 group hover:border-green-500 transition-all duration-300">
                <div class="relative overflow-hidden rounded-lg mb-6">
                    <img src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=Tea%20plantation%20in%20Central%20Java%2C%20Indonesia%2C%20lowland%20tea%20garden%2C%20flat%20terrain%2C%20tropical%20agriculture%2C%20professional%20photography&image_size=landscape_4_3" 
                         alt="Kebun Teh Jawa Tengah" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <h3 class="text-xl font-bold">Jawa Tengah</h3>
                        <p class="text-sm opacity-90">1 Kebun Model</p>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-green-600 mr-2">location_on</span>
                        <span>Brebes</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-green-600 mr-2">terrain</span>
                        <span>Ketinggian 200-400 mdpl</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-green-600 mr-2">eco</span>
                        <span>Iklim dataran rendah</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Kebun Model:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Kaligua (110 ha)</li>
                    </ul>
                </div>
                
                <div class="bg-green-50 p-3 rounded-lg mb-4">
                    <p class="text-sm text-green-800">
                        <strong>Signifikansi:</strong> Representasi kebun teh dataran rendah untuk adaptasi iklim panas
                    </p>
                </div>
                
                <a href="{{ route('strategic.region', ['region' => 'jawa-tengah']) }}" 
                   class="block w-full text-center bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-lg font-medium hover:from-green-700 hover:to-green-800 transition-all duration-300">
                    <span class="material-icons mr-2">arrow_forward</span>
                    Jelajahi Wilayah
                </a>
            </div>
            
            <!-- Sumatra -->
            <div class="pptk-card p-6 group hover:border-purple-500 transition-all duration-300">
                <div class="relative overflow-hidden rounded-lg mb-6">
                    <img src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=Tea%20plantation%20in%20Sumatra%2C%20Indonesia%2C%20highland%20tea%20garden%2C%20volcanic%20soil%2C%20misty%20mountains%2C%20professional%20landscape%20photography&image_size=landscape_4_3" 
                         alt="Kebun Teh Sumatra" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <h3 class="text-xl font-bold">Sumatra</h3>
                        <p class="text-sm opacity-90">1 Kebun Model</p>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-purple-600 mr-2">location_on</span>
                        <span>Pagar Alam, South Sumatra</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-purple-600 mr-2">terrain</span>
                        <span>Ketinggian 1.400-1.700 mdpl</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="material-icons text-purple-600 mr-2">eco</span>
                        <span>Tanah vulkanik subur</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Kebun Model:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Pagar Alam (180 ha)</li>
                    </ul>
                </div>
                
                <div class="bg-purple-50 p-3 rounded-lg mb-4">
                    <p class="text-sm text-purple-800">
                        <strong>Keunikan:</strong> Tanah vulkanik yang sangat subur dan iklim khas pegunungan Sumatra
                    </p>
                </div>
                
                <a href="{{ route('strategic.region', ['region' => 'sumatra']) }}" 
                   class="block w-full text-center bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-lg font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-300">
                    <span class="material-icons mr-2">arrow_forward</span>
                    Jelajahi Wilayah
                </a>
            </div>
        </div>
        
        <!-- Strategic Overview -->
        <div class="mt-16">
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