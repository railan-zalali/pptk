<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPTK - Agricultural Strategic Action & Production Monitoring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="antialiased bg-gray-50 text-gray-900">
    <div class="relative min-h-screen flex flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
        <div class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
        <div class="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:max-w-lg sm:rounded-lg sm:px-10">
            <div class="mx-auto max-w-md">
                <div class="flex items-center space-x-3 mb-6">
                    <i class="fas fa-leaf text-green-600 text-3xl"></i>
                    <h1 class="text-2xl font-bold text-green-900">PPTK Kebun Model</h1>
                </div>
                <div class="divide-y divide-gray-300/50">
                    <div class="py-8 text-base leading-7 space-y-6 text-gray-600">
                        <p>Welcome to the Agricultural Strategic Action & Production Monitoring System.</p>
                        <p>Access the public dashboard to view production realization, strategic actions, and performance indicators.</p>
                        
                        <div class="pt-4 flex flex-col space-y-4">
                            <a href="{{ route('dashboard.garden') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded transition duration-300">
                                <i class="fas fa-chart-line mr-2"></i> Public Dashboard
                            </a>
                            
                            @auth
                                <a href="{{ route('dashboard') }}" class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-4 rounded transition duration-300">
                                    <i class="fas fa-user-shield mr-2"></i> Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-4 rounded transition duration-300">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Admin Login
                                </a>
                            @endauth
                        </div>
                    </div>
                    <div class="pt-8 text-base font-semibold leading-7">
                        <p class="text-gray-900">Modules:</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Strategic Planning</span>
                            <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Production Monitoring</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Realization</span>
                            <span class="bg-purple-100 text-purple-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Analytics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
