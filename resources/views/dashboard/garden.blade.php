@extends('layouts.pptk')

@section('title', 'Dashboard Strategic Kebun Model')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">Executive Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-300">Monitoring Strategis & Operasional Kebun Model</p>
        </div>

        <!-- 1. Header Scorecard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Production YTD -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Total Produksi (YTD)</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($productionYtd, 0) }} Kg
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                        <i class="fas fa-chart-line text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Target: {{ number_format($targetYtdProrated, 0) }} Kg</span>
                    @php
                        $achievement = $targetYtdProrated > 0 ? ($productionYtd / $targetYtdProrated) * 100 : 0;
                        $color = $achievement >= 90 ? 'green' : ($achievement >= 70 ? 'yellow' : 'red');
                    @endphp
                    <span class="text-{{ $color }}-600 font-bold ml-2">({{ number_format($achievement, 1) }}%)</span>
                </div>
            </div>

            <!-- Avg Productivity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Rata-rata Produktivitas</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($avgProductivity, 1) }} Kg/Ha
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                        <i class="fas fa-leaf text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Performa Agronomis</span>
                </div>
            </div>

            <!-- Picking Capacity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Kapasitas Petik</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            {{ number_format($pickingCapacity, 1) }} Kg/HK
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                        <i class="fas fa-users text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Efisiensi Tenaga Kerja</span>
                </div>
            </div>

            <!-- Cultivator Progress -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Progress Kultivator</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                            {{ number_format($cultivatorProgress, 1) }}%
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900/50 p-3 rounded-full">
                        <i class="fas fa-tractor text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                        <div class="bg-orange-600 h-2.5 rounded-full" style="width: {{ min($cultivatorProgress, 100) }}%"></div>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400 text-xs mt-1 block">
                        Real: {{ number_format($cultivatorRealization, 0) }} / {{ number_format($cultivatorTarget, 0) }} Ha
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Visualisasi Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Bar Chart: Production -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Produksi Basah Bulanan (Kg)</h3>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="productionChart"></canvas>
                </div>
            </div>

            <!-- Line Chart: Productivity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Tren Produktivitas (Kg/Ha)</h3>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="productivityChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- 3. Tabel Detail Afdeling -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Detail Performa Afdeling</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Afdeling</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Luas TM (Ha)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas Blok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Realisasi (Kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Protas (Kg/Ha)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($afdelings as $afdeling)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $afdeling['name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($afdeling['tm_area'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $afdeling['block_class'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-bold">
                                        {{ number_format($afdeling['production_realization'], 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($afdeling['protas_achievement'], 1) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Strategic Monitoring Widgets -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">Strategic Monitoring</h3>
                
                <!-- Fertilizer -->
                <div class="mb-8">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Penyerapan Pupuk</span>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-400">{{ number_format($fertilizerProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($fertilizerProgress, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target vs Realisasi Aplikasi</p>
                </div>

                <!-- Soil Management (Cultivator) -->
                <div class="mb-8">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Soil Management (Kultivator)</span>
                        <span class="text-sm font-medium text-orange-700 dark:text-orange-400">{{ number_format($cultivatorProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-orange-500 h-2.5 rounded-full" style="width: {{ min($cultivatorProgress, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Area Terolah</p>
                </div>

                <!-- Weed Control (Dummy for visual balance) -->
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pengendalian Gulma</span>
                        <span class="text-sm font-medium text-red-700 dark:text-red-400">85.0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-red-500 h-2.5 rounded-full" style="width: 85%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Sanitasi Kebun</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Data passed from controller
        const monthlyProductionLabels = {!! json_encode(array_keys($monthlyProduction->toArray())) !!}.map(m => {
            const date = new Date();
            date.setMonth(m - 1);
            return date.toLocaleString('default', { month: 'short' });
        });
        const monthlyProductionData = {!! json_encode(array_values($monthlyProduction->toArray())) !!};

        const monthlyProductivityLabels = {!! json_encode(array_keys($monthlyProductivity->toArray())) !!}.map(m => {
            const date = new Date();
            date.setMonth(m - 1);
            return date.toLocaleString('default', { month: 'short' });
        });
        const monthlyProductivityData = {!! json_encode(array_values($monthlyProductivity->toArray())) !!};

        // Theme check
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#e5e7eb' : '#374151';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

        // 1. Production Chart
        new Chart(document.getElementById('productionChart'), {
            type: 'bar',
            data: {
                labels: monthlyProductionLabels,
                datasets: [{
                    label: 'Produksi Basah (Kg)',
                    data: monthlyProductionData,
                    backgroundColor: 'rgba(34, 197, 94, 0.6)', // Green-500
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    }
                },
                plugins: {
                    legend: { labels: { color: textColor } }
                }
            }
        });

        // 2. Productivity Chart
        new Chart(document.getElementById('productivityChart'), {
            type: 'line',
            data: {
                labels: monthlyProductivityLabels,
                datasets: [{
                    label: 'Produktivitas (Kg/Ha)',
                    data: monthlyProductivityData,
                    borderColor: 'rgb(59, 130, 246)', // Blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    }
                },
                plugins: {
                    legend: { labels: { color: textColor } }
                }
            }
        });
    </script>
@endpush
