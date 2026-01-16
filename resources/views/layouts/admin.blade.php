<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin • {{ config('app.name', 'PPTK Gambung') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-full md:w-64 bg-white dark:bg-gray-800 shadow-lg hidden md:block flex-shrink-0 transition-all duration-300">
            <div class="p-4 border-b flex justify-between items-center">
                <div>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">Admin Panel</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-300">PPTK Gambung</p>
                </div>
                <button id="closeSidebar"
                    class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-5rem)]">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">dashboard</span> Dashboard
                </a>

                <div class="pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</div>

                <a href="{{ route('admin.regions.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.regions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">map</span> Wilayah
                </a>
                <a href="{{ route('admin.gardens.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.gardens.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">park</span> Kebun
                </a>
                <a href="{{ route('admin.afdelings.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.afdelings.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">grid_view</span> Afdeling
                </a>
                <a href="{{ route('admin.blocks.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.blocks.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">crop_square</span> Blok
                </a>

                <div class="pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Produksi</div>

                <a href="{{ route('admin.production-realizations.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.production-realizations.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">precision_manufacturing</span> Produksi Realisasi
                </a>
                <a href="{{ route('admin.performance-targets.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.performance-targets.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">track_changes</span> Target Kinerja
                </a>

                <div class="pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Strategis</div>

                <a href="{{ route('admin.programs.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.programs.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">list_alt</span> Program
                </a>
                <a href="{{ route('admin.strategic-actions.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.strategic-actions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">agriculture</span> Strategic Action
                </a>
                <a href="{{ route('admin.insights.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.insights.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">insights</span> Insight
                </a>

                <div class="pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Lainnya</div>

                <a href="{{ route('admin.visits.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.visits.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">event</span> Kunjungan
                </a>
                <a href="{{ route('admin.pages.about.edit') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.pages.about.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">info</span> Halaman Tentang
                </a>
                <a href="{{ route('admin.pages.research.edit') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.pages.research.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">science</span> Halaman Penelitian
                </a>
                <a href="{{ route('dashboard.garden') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <span class="material-icons mr-2">analytics</span> Dashboard Publik
                </a>
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
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">@yield('title', 'Admin')</h2>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button id="adminThemeToggle"
                            class="p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                            title="Toggle tema">
                            <span class="material-icons" id="adminThemeIcon">dark_mode</span>
                        </button>
                        <span
                            class="text-sm text-gray-600 dark:text-gray-300 hidden sm:inline-block">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
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
                @yield('content')
            </section>
        </main>
    </div>
    <script>
        (function() {
            var s = localStorage.getItem('theme');
            var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var t = s ? s : (d ? 'dark' : 'light');
            if (t === 'dark') {
                document.documentElement.classList.add('dark')
            }
        })();
        (function() {
            var b = document.getElementById('adminThemeToggle');
            var i = document.getElementById('adminThemeIcon');

            function u() {
                i.textContent = document.documentElement.classList.contains('dark') ? 'light_mode' : 'dark_mode'
            }
            if (b && i) {
                u();
                b.addEventListener('click', function() {
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
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.remove('hidden');
                    sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-50');
                });
            }

            if (closeSidebar && sidebar) {
                closeSidebar.addEventListener('click', function() {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-50');
                });
            }
        })();
    </script>
    @stack('scripts')
</body>

</html>
