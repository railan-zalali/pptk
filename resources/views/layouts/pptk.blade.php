
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal PPTK Gambung') }} - {{ $title ?? 'Portal Informasi dan Monitoring Kinerja Multi-Kebun Model Teh' }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Portal Informasi dan Monitoring Kinerja Multi-Kebun Model Teh PPTK Gambung - Sistem monitoring real-time untuk kebun model teh">
    <meta name="keywords" content="PPTK Gambung, Monitoring Kebun Teh, Kebun Model, Teh Indonesia, Penelitian Teh">
    <meta name="author" content="Pusat Penelitian Teh dan Kina Gambung">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Material Design Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-green: #228B22;
            --secondary-green: #32CD32;
            --accent-green: #90EE90;
            --background-beige: #F5F5DC;
            --earth-brown: #8B4513;
            --tea-green: #9ACD32;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, var(--background-beige) 0%, #ffffff 100%);
        }

        .pptk-primary {
            background-color: var(--primary-green);
        }

        .pptk-secondary {
            background-color: var(--secondary-green);
        }

        .pptk-accent {
            color: var(--primary-green);
        }

        .pptk-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .pptk-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .pptk-btn {
            background: linear-gradient(45deg, var(--primary-green), var(--secondary-green));
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pptk-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 139, 34, 0.3);
        }

        .hero-overlay {
            background: linear-gradient(135deg, rgba(34, 139, 34, 0.8) 0%, rgba(50, 205, 50, 0.6) 100%);
        }

        .tea-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23228B22' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm-20-15c8.284 0 15 6.716 15 15s-6.716 15-15 15-15-6.716-15-15 6.716-15 15-15z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-green) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 50%;
            background-color: var(--primary-green);
            transition: all 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        @media (max-width: 768px) {
            .mobile-menu {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
            }

            /* Mobile responsive improvements */
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            /* Responsive tables */
            .responsive-table {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Responsive cards */
            .responsive-card {
                margin-bottom: 1rem;
            }

            /* Responsive charts */
            .chart-container {
                position: relative;
                height: 300px;
                width: 100%;
            }

            /* Mobile navigation improvements */
            .mobile-nav-item {
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                margin-bottom: 0.25rem;
            }

            /* Touch-friendly buttons */
            .touch-friendly {
                min-height: 44px;
                min-width: 44px;
            }

            /* Responsive typography */
            .responsive-text {
                font-size: clamp(0.875rem, 2.5vw, 1rem);
            }

            /* Mobile grid improvements */
            .mobile-grid {
                display: grid;
                gap: 1rem;
                grid-template-columns: 1fr;
            }

            @media (min-width: 640px) {
                .mobile-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (min-width: 768px) {
                .mobile-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
        }

        /* Additional responsive utilities */
        @media (max-width: 640px) {
            .mobile-hidden {
                display: none !important;
            }

            .mobile-full {
                width: 100% !important;
            }

            .mobile-stack {
                flex-direction: column !important;
            }

            .mobile-gap-2 {
                gap: 0.5rem !important;
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .print-break {
                page-break-before: always;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .pptk-card {
                border: 2px solid #000;
            }

            .pptk-btn {
                border: 2px solid #000;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .pptk-card {
                transition: none;
            }

            .pptk-btn {
                transition: none;
            }

            .nav-link {
                transition: none;
            }

            .nav-link::after {
                transition: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased tea-pattern">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-400 rounded-full flex items-center justify-center">
                                <span class="material-icons text-white text-lg">eco</span>
                            </div>
                            <div class="hidden md:block">
                                <h1 class="text-xl font-bold text-gray-800">Portal PPTK Gambung</h1>
                                <p class="text-xs text-gray-600">Multi-Kebun Model Teh</p>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="nav-link text-gray-700 hover:text-green-600 font-medium">Beranda</a>
                        <a href="{{ route('about') }}" class="nav-link text-gray-700 hover:text-green-600 font-medium">Tentang</a>
                        <a href="{{ route('strategic.index') }}" class="nav-link text-gray-700 hover:text-green-600 font-medium">Strategic Action</a>
                        <a href="{{ route('visits.index') }}" class="nav-link text-gray-700 hover:text-green-600 font-medium">Kunjungan</a>

                        @auth
                            <div class="relative group">
                                <button class="nav-link text-gray-700 hover:text-green-600 font-medium flex items-center">
                                    Dashboard
                                    <span class="material-icons ml-1 text-sm">expand_more</span>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <a href="{{ route('dashboard.garden') }}" class="block px-4 py-2 text-gray-700 hover:bg-green-50 rounded-t-lg">Dashboard Kebun Model</a>
                                    <a href="{{ route('dashboard.research') }}" class="block px-4 py-2 text-gray-700 hover:bg-green-50 rounded-b-lg">Dashboard Penelitian</a>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="hidden lg:block">
                            <div class="relative">
                                <input type="text" placeholder="Cari kebun atau indikator..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <span class="material-icons absolute left-3 top-2.5 text-gray-400">search</span>
                            </div>
                        </div>

                        <!-- Auth Links -->
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 font-medium">Masuk</a>
                            <a href="{{ route('register') }}" class="pptk-btn text-sm">Daftar</a>
                        @else
                            <div class="relative group">
                                <button class="flex items-center space-x-2 text-gray-700 hover:text-green-600">
                                    <span class="material-icons">account_circle</span>
                                    <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                    <span class="material-icons text-sm">expand_more</span>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-green-50 rounded-t-lg">Profil</a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-green-50 rounded-b-lg">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        @endguest

                        <!-- Mobile menu button -->
                        <button class="md:hidden p-2 rounded-md text-gray-700 hover:text-green-600 hover:bg-gray-100 touch-friendly" onclick="toggleMobileMenu()">
                            <span class="material-icons">menu</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden mobile-menu border-t">
                <div class="px-4 py-2 space-y-1">
                    <a href="{{ route('home') }}" class="mobile-nav-item block text-gray-700 hover:text-green-600 hover:bg-green-50">Beranda</a>
                    <a href="{{ route('about') }}" class="mobile-nav-item block text-gray-700 hover:text-green-600 hover:bg-green-50">Tentang</a>
                    <a href="{{ route('strategic.index') }}" class="mobile-nav-item block text-gray-700 hover:text-green-600 hover:bg-green-50">Strategic Action</a>
                    <a href="{{ route('visits.index') }}" class="mobile-nav-item block text-gray-700 hover:text-green-600 hover:bg-green-50">Kunjungan</a>
                    @auth
                        <div class="border-t pt-2 mt-2">
                            <p class="px-3 py-2 text-sm font-medium text-gray-500">Dashboard</p>
                            <a href="{{ route('dashboard.garden') }}" class="mobile-nav-item block text-gray-700 hover:text-green-600 hover:bg-green-50 ml-4">Dashboard Kebun Model</a>
                            <a href="{{ route('dashboard.research') }}" class="mobile-nav-item block text-gray-700 hover:text-green-600 hover:bg-green-50 ml-4">Dashboard Penelitian</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white/90 backdrop-blur-sm shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $header }}
                    </h2>
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-green-800 to-green-600 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Portal PPTK Gambung</h3>
                        <p class="text-green-100">Sistem monitoring kinerja multi-kebun model teh untuk mendukung penelitian dan pengembangan pertanian berkelanjutan.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                        <div class="space-y-2 text-green-100">
                            <p class="flex items-center"><span class="material-icons mr-2">location_on</span> Pusat Penelitian Teh dan Kina Gambung</p>
                            <p class="flex items-center"><span class="material-icons mr-2">phone</span> +62 22 278 1234</p>
                            <p class="flex items-center"><span class="material-icons mr-2">email</span> info@pptk-gambung.id</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('about') }}" class="block text-green-100 hover:text-white transition-colors">Tentang Kebun Model</a>
                            <a href="{{ route('strategic.index') }}" class="block text-green-100 hover:text-white transition-colors">Strategic Action</a>
                            <a href="{{ route('dashboard.garden') }}" class="block text-green-100 hover:text-white transition-colors">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-green-700 mt-8 pt-8 text-center text-green-100">
                    <p>&copy; 2025 Pusat Penelitian Teh dan Kina Gambung. Hak cipta dilindungi.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');

            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>