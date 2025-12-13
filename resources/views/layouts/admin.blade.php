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
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg">
            <div class="p-4 border-b">
                <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">Admin Panel</h1>
                <p class="text-xs text-gray-500 dark:text-gray-300">PPTK Gambung</p>
            </div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">dashboard</span> Dashboard
                </a>
                <a href="{{ route('admin.regions.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.regions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">map</span> Wilayah
                </a>
                <a href="{{ route('admin.pages.about.edit') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.pages.about.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">info</span> Halaman Tentang
                </a>
                <a href="{{ route('admin.gardens.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.gardens.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">park</span> Kebun
                </a>
                <a href="{{ route('admin.production.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.production.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">precision_manufacturing</span> Produksi
                </a>
                <a href="{{ route('admin.insights.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.insights.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">insights</span> Insight
                </a>
                <a href="{{ route('admin.visits.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.visits.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="material-icons mr-2">event</span> Kunjungan
                </a>
                <a href="{{ route('dashboard.garden') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <span class="material-icons mr-2">analytics</span> Dashboard Publik
                </a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="flex-1">
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">@yield('title', 'Admin')</h2>
                    <div class="flex items-center space-x-3">
                        <button id="adminThemeToggle"
                            class="p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                            title="Toggle tema">
                            <span class="material-icons" id="adminThemeIcon">dark_mode</span>
                        </button>
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="px-3 py-1 text-sm rounded bg-gray-100 dark:bg-gray-700 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>
            <section class="max-w-7xl mx_auto p-4">
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
                    u()
                })
            }
        })();
    </script>
</body>

</html>
