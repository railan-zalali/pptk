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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
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
                        <span class="material-icons text-green-600 dark:text-green-400 text-xl">show_chart</span>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Target: {{ number_format($targetYtdProrated, 0) }}
                        Kg</span>
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
                        <span class="material-icons text-blue-600 dark:text-blue-400 text-xl">spa</span>
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
                        <span class="material-icons text-purple-600 dark:text-purple-400 text-xl">groups</span>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Efisiensi Tenaga Kerja</span>
                </div>
            </div>

            <!-- Quality Score -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Skor Kualitas</p>
                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                            {{ number_format($qualityScore, 1) }}
                        </p>
                    </div>
                    <div class="bg-teal-100 dark:bg-teal-900/50 p-3 rounded-full">
                        <span class="material-icons text-teal-600 dark:text-teal-400 text-xl">grade</span>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Rata-rata (Skala 0-10)</span>
                </div>
            </div>

            <!-- Protas Progress (Replaces Cultivator) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Pencapaian Protas</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                            {{ number_format($protasProgress, 1) }}%
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900/50 p-3 rounded-full">
                        <span class="material-icons text-orange-600 dark:text-orange-400 text-xl">trending_up</span>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                        <div class="bg-orange-600 h-2.5 rounded-full" style="width: {{ min($protasProgress, 100) }}%">
                        </div>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400 text-xs mt-1 block">
                        Target N Tahunan Terpenuhi
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

        <!-- 3. Performance Analysis (Merged) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Regional Comparison & Garden Details -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Analisis Wilayah & Performa</h3>
                    <div class="flex gap-2 mt-2 md:mt-0">
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm flex items-center">
                            <span class="material-icons mr-1 text-sm">download</span>PDF
                        </button>
                    </div>
                </div>

                <!-- Regional Summary -->
                <div class="mb-8">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                        Perbandingan Wilayah</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Wilayah</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jml Kebun
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata
                                        Protas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($regionalComparison as $region)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $region['region'] }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $region['garden_count'] }}</td>
                                        <td class="px-4 py-2 text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($region['avg_protas'], 1) }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                <div class="bg-blue-600 h-2.5 rounded-full"
                                                    style="width: {{ min(($region['avg_protas'] / 2000) * 100, 100) }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Garden Details -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Detail
                        Kebun</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kebun</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Wilayah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Luas (Ha)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Realisasi (Kg)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Protas (Kg/Ha)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($gardenDetails as $garden)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $garden['name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $garden['region'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($garden['tm_area'], 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-bold">
                                            {{ number_format($garden['production_realization'], 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ number_format($garden['protas_achievement'], 1) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. Strategic Monitoring Widgets -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">Strategic Monitoring</h3>

                <!-- Fertilizer -->
                <div class="mb-8">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Penyerapan Pupuk</span>
                        <div class="text-right">
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-400 block">{{ number_format($fertilizerProgress, 1) }}% (Protas: {{ number_format($fertilizerProtas, 1) }}%)</span>
                            <span class="text-xs text-gray-500 block">Realisasi: {{ number_format($fertilizerRealization, 0) }} kg/ha</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($fertilizerProgress, 100) }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target vs Realisasi Aplikasi</p>
                </div>

                <!-- Kultivator (Moved from Header) -->
                <div class="mb-8">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kultivator</span>
                        <span
                            class="text-sm font-medium text-purple-700 dark:text-purple-400">{{ number_format($cultivatorProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-purple-500 h-2.5 rounded-full" style="width: {{ min($cultivatorProgress, 100) }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Efisiensi Penggunaan Alat</p>
                </div>

                <!-- Weed Control -->
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pengendalian Gulma</span>
                        <span
                            class="text-sm font-medium text-red-700 dark:text-red-400">{{ number_format($weedControlProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ min($weedControlProgress, 100) }}%">
                        </div>
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
            return date.toLocaleString('default', {
                month: 'short'
            });
        });
        const monthlyProductionData = {!! json_encode(array_values($monthlyProduction->toArray())) !!};

        const monthlyProductivityLabels = {!! json_encode(array_keys($monthlyProductivity->toArray())) !!}.map(m => {
            const date = new Date();
            date.setMonth(m - 1);
            return date.toLocaleString('default', {
                month: 'short'
            });
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
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
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
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                }
            }
        });
    </script>
@endpush
