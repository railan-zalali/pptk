<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(Auth::user()->role === 'admin_pptk')
            Admin PPTK •
        @else
            Manajemen •
        @endif
        {{ config('app.name', 'PPTK Gambung') }}
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        /* Print styles */
        @media print {

            aside,
            header,
            footer,
            button,
            .no-print,
            form,
            .btn,
            .nav-link,
            .nav-section,
            #adminThemeToggle,
            .w-px,
            .alert-success,
            .alert-error {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
                overflow: visible !important;
            }

            .flex,
            .grid {
                display: block !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                overflow: visible !important;
            }

            .card {
                border: 1px solid #e5e7eb !important;
                box-shadow: none !important;
                margin-bottom: 20px !important;
                page-break-inside: avoid !important;
            }

            .card-body,
            .p-6,
            .p-5 {
                padding: 15px !important;
            }

            tr {
                page-break-inside: avoid !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-full md:w-64 bg-white dark:bg-gray-800 shadow-lg hidden md:block flex-shrink-0 transition-all duration-300">
            <div class="p-4 border-b flex justify-between items-center">
                <div>
                    @if(Auth::user()->role === 'admin_pptk')
                        <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">Admin PPTK</h1>
                    @else
                        <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">Manajemen</h1>
                    @endif
                    <p class="text-xs text-gray-500 dark:text-gray-300">PPTK Gambung</p>
                </div>
                <button id="closeSidebar"
                    class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-5rem)]">

                {{-- ================================================ --}}
                {{-- MENU: Admin PPTK --}}
                {{-- ================================================ --}}
                @if(Auth::user()->role === 'admin_pptk')
                    <div class="pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Admin</div>

                    <a href="{{ route('admin.regions.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.regions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">map</span> Wilayah Kebun
                    </a>
                    <a href="{{ route('admin.gardens.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.gardens.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">park</span> Kebun
                    </a>
                    <a href="{{ route('admin.afdelings.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.afdelings.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">grid_view</span> Afdeling / Updeling
                    </a>
                    <a href="{{ route('admin.blocks.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.blocks.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">crop_square</span> Blok
                    </a>
                    <a href="{{ route('admin.production-realizations.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.production-realizations.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">precision_manufacturing</span> Realisasi Produksi
                    </a>
                    <a href="{{ route('admin.performance-targets.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.performance-targets.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">track_changes</span> Target Kinerja
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">manage_accounts</span> Manajemen Pengguna
                    </a>

                    <div class="pt-3 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('home') }}"
                            class="flex items-center px-3 py-2 rounded bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 font-medium">
                            <span class="material-icons mr-2 text-sm">public</span> Ke Halaman Publik
                        </a>
                    </div>
                @endif

                {{-- ================================================ --}}
                {{-- MENU: Manajemen Eksekutif --}}
                {{-- ================================================ --}}
                @if(Auth::user()->role === 'manajemen')
                    <div class="pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Manajemen</div>

                    <a href="{{ route('dashboard.garden') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard.garden') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">analytics</span> Dashboard Publik
                    </a>

                    <a href="{{ route('manajemen.programs.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.programs.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">list_alt</span> Program
                    </a>
                    <a href="{{ route('manajemen.strategic-actions.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.strategic-actions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">agriculture</span> Strategic Action
                    </a>

                    @php
                        $criticalInsightCount = \App\Models\Insight::where('alert_level', 'high')->count();
                    @endphp
                    <a href="{{ route('manajemen.insights.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.insights.*') ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="flex items-center">
                            <span class="material-icons mr-2">insights</span> Insight
                        </span>
                        @if($criticalInsightCount > 0)
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-xs font-bold leading-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping opacity-75"></span>
                                {{ $criticalInsightCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('manajemen.visits.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.visits.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">event</span> Kunjungan
                    </a>
                    <a href="{{ route('manajemen.penelitian.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.penelitian.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">science</span> Data Penelitian
                    </a>
                    <a href="{{ route('manajemen.community-services.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.community-services.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">volunteer_activism</span> Data Pengabdian Masyarakat
                    </a>
                    <a href="{{ route('about') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('about') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">info</span> Tentang
                    </a>
                    <a href="{{ route('manajemen.research-budgets.index') }}"
                        class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('manajemen.research-budgets.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                        <span class="material-icons mr-2">account_balance</span> Realisasi Anggaran
                    </a>

                    <div class="pt-3 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('home') }}"
                            class="flex items-center px-3 py-2 rounded bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 font-medium">
                            <span class="material-icons mr-2 text-sm">public</span> Ke Halaman Publik
                        </a>
                    </div>
                @endif

            </nav>
        </aside>

        <!-- Main -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
            <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button id="mobileMenuBtn"
                            class="mr-4 md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-300">
                            <span class="material-icons">menu</span>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">@yield('title', 'Panel')</h2>
                    </div>
                    <div class="flex items-center space-x-3">
                        {{-- Print Button (UC5) --}}
                        <button onclick="window.print()"
                            class="no-print p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-1"
                            title="Cetak Laporan">
                            <span class="material-icons">print</span>
                            <span class="text-xs font-semibold hidden md:inline">Cetak</span>
                        </button>

                        <button id="adminThemeToggle"
                            class="no-print p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                            title="Toggle tema">
                            <span class="material-icons" id="adminThemeIcon">dark_mode</span>
                        </button>
                        <div class="hidden sm:flex sm:flex-col sm:items-end">
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                {{ Auth::user()->role === 'admin_pptk' ? 'Admin PPTK' : 'Manajemen' }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="no-print">
                            @csrf
                            <button
                                class="px-3 py-1 text-sm rounded bg-gray-100 dark:bg-gray-700 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>
            <section class="max-w-7xl mx-auto p-4 min-h-[calc(100vh-5rem)]">
                @if (session('success'))
                    <div
                        class="mb-4 bg-green-50 border border-green-200 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-100 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="mb-4 bg-red-50 border border-red-200 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-100 px-4 py-3 rounded flex items-center">
                        <span class="material-icons mr-2 text-red-500">lock</span>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </section>
        </main>
    </div>
    <script>
        (function () {
            var s = localStorage.getItem('theme');
            var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var t = s ? s : (d ? 'dark' : 'light');
            if (t === 'dark') {
                document.documentElement.classList.add('dark')
            }
        })();
        (function () {
            var b = document.getElementById('adminThemeToggle');
            var i = document.getElementById('adminThemeIcon');

            function u() {
                i.textContent = document.documentElement.classList.contains('dark') ? 'light_mode' : 'dark_mode'
            }
            if (b && i) {
                u();
                b.addEventListener('click', function () {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' :
                        'light');
                    u();
                });
            }

            // Sidebar Toggle
            var sidebar = document.getElementById('sidebar');
            var mobileMenuBtn = document.getElementById('mobileMenuBtn');
            var closeSidebar = document.getElementById('closeSidebar');

            if (mobileMenuBtn && sidebar) {
                mobileMenuBtn.addEventListener('click', function () {
                    sidebar.classList.remove('hidden');
                    sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-50');
                });
            }

            if (closeSidebar && sidebar) {
                closeSidebar.addEventListener('click', function () {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-50');
                });
            }
        })();
    </script>
    @stack('scripts')
</body>

</html>