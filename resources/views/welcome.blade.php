<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPTK - Agricultural Strategic Action & Production Monitoring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="antialiased bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 font-sans">
    <div
        class="relative min-h-screen flex flex-col justify-center overflow-hidden bg-gray-50 dark:bg-gray-900 py-6 sm:py-12">
        <div
            class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))] dark:[mask-image:linear-gradient(180deg,rgba(255,255,255,0.1),rgba(255,255,255,0))] opacity-40">
        </div>
        <div
            class="relative bg-white dark:bg-gray-800 px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:max-w-lg sm:rounded-xl sm:px-10 transition-all hover:shadow-2xl dark:ring-gray-700/50">
            <div class="mx-auto max-w-md">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <span class="material-icons text-green-600 dark:text-green-400 text-3xl">spa</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">PPTK Kebun Model</h1>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="py-8 text-base leading-7 space-y-6 text-gray-600 dark:text-gray-300">
                        <p>Selamat datang di Sistem Monitoring Aksi Strategis & Produksi Pertanian.</p>
                        <p>Akses dashboard publik untuk melihat realisasi produksi, aksi strategis, dan indikator
                            kinerja utama.</p>

                        <div class="pt-4 flex flex-col space-y-4">
                            <a href="{{ route('dashboard.garden') }}"
                                class="group block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center shadow-lg shadow-green-600/20 hover:shadow-green-600/40 hover:-translate-y-0.5">
                                <span class="material-icons mr-2 group-hover:animate-pulse">insights</span>
                                Dashboard Publik
                            </a>

                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="group block w-full text-center border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center hover:border-gray-400 dark:hover:border-gray-500">
                                    <span
                                        class="material-icons mr-2 text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200">admin_panel_settings</span>
                                    Dashboard Admin
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="group block w-full text-center border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center hover:border-gray-400 dark:hover:border-gray-500">
                                    <span
                                        class="material-icons mr-2 text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200">login</span>
                                    Masuk Admin
                                </a>
                            @endauth
                        </div>
                    </div>
                    <div class="pt-8 text-base font-semibold leading-7">
                        <p class="text-gray-900 dark:text-white mb-4">Modul Sistem:</p>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Perencanaan
                                Strategis</span>
                            <span
                                class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 text-xs font-semibold px-3 py-1 rounded-full">Monitoring
                                Produksi</span>
                            <span
                                class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Realisasi</span>
                            <span
                                class="bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 text-xs font-semibold px-3 py-1 rounded-full">Analitik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} PPTK Gambung. All rights
                reserved.</p>
        </div>
    </div>
</body>

</html>
