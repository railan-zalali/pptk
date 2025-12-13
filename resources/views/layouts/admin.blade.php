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

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h1 class="text-lg font-bold text-gray-800">Admin Panel</h1>
                <p class="text-xs text-gray-500">PPTK Gambung</p>
            </div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-700' }}">
                    <span class="material-icons mr-2">dashboard</span> Dashboard
                </a>
                <a href="{{ route('admin.regions.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 {{ request()->routeIs('admin.regions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700' }}">
                    <span class="material-icons mr-2">map</span> Wilayah
                </a>
                <a href="{{ route('admin.pages.about.edit') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 {{ request()->routeIs('admin.pages.about.*') ? 'bg-green-100 text-green-800' : 'text-gray-700' }}">
                    <span class="material-icons mr-2">info</span> Halaman Tentang
                </a>
                <a href="{{ route('admin.gardens.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 {{ request()->routeIs('admin.gardens.*') ? 'bg-green-100 text-green-800' : 'text-gray-700' }}">
                    <span class="material-icons mr-2">park</span> Kebun
                </a>
                <a href="{{ route('admin.production.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 {{ request()->routeIs('admin.production.*') ? 'bg-green-100 text-green-800' : 'text-gray-700' }}">
                    <span class="material-icons mr-2">precision_manufacturing</span> Produksi
                </a>
                <a href="{{ route('admin.insights.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 {{ request()->routeIs('admin.insights.*') ? 'bg-green-100 text-green-800' : 'text-gray-700' }}">
                    <span class="material-icons mr-2">insights</span> Insight
                </a>
                <a href="{{ route('visits.index') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 text-gray-700">
                    <span class="material-icons mr-2">event</span> Kunjungan
                </a>
                <a href="{{ route('dashboard.garden') }}"
                    class="flex items-center px-3 py-2 rounded hover:bg-green-50 text-gray-700">
                    <span class="material-icons mr-2">analytics</span> Dashboard Publik
                </a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="flex-1">
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Admin')</h2>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-3 py-1 text-sm rounded bg-gray-100 hover:bg-gray-200">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>
            <section class="max-w-7xl mx-auto p-4">
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </section>
        </main>
    </div>
</body>

</html>
