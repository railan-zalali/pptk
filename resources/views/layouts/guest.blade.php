<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Material Design Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100 selection:bg-emerald-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-green-50 to-emerald-100 dark:from-gray-900 dark:to-emerald-950 relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-emerald-200/30 dark:bg-emerald-900/20 blur-3xl"></div>
                <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-green-200/40 dark:bg-green-900/20 blur-3xl"></div>
            </div>

            <div class="z-10 mb-6 text-center">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <x-application-logo class="w-28 h-auto drop-shadow-lg transition-transform duration-300 group-hover:scale-105" />
                    <h1 class="text-2xl font-bold text-emerald-800 dark:text-emerald-400 tracking-tight">PPTK</h1>
                    <p class="text-sm text-emerald-600 dark:text-emerald-300/80">Pusat Penelitian Teh dan Kina</p>
                </a>
            </div>

            <div class="z-10 w-full sm:max-w-md px-8 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-2xl border border-white/40 dark:border-gray-700/50">
                <div class="flex justify-end mb-4">
                    <button id="guestThemeToggle" class="p-2 rounded-full text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-gray-700 transition-colors shadow-sm bg-white dark:bg-gray-800 border border-emerald-100 dark:border-gray-700" title="Toggle tema">
                        <span class="material-icons text-sm" id="guestThemeIcon">dark_mode</span>
                    </button>
                </div>
                {{ $slot }}
            </div>
        </div>
        <script>
            (function(){var s=localStorage.getItem('theme');var d=window.matchMedia('(prefers-color-scheme: dark)').matches;var t=s?s:(d?'dark':'light');if(t==='dark'){document.documentElement.classList.add('dark')}})();
            (function(){var b=document.getElementById('guestThemeToggle');var i=document.getElementById('guestThemeIcon');function u(){i.textContent=document.documentElement.classList.contains('dark')?'light_mode':'dark_mode'}if(b&&i){u();b.addEventListener('click',function(){document.documentElement.classList.toggle('dark');localStorage.setItem('theme',document.documentElement.classList.contains('dark')?'dark':'light');u()})}})();
        </script>
    </body>
</html>
