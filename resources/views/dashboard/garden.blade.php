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
                        <span class="material-icons text-orange-600 dark:text-orange-400 text-xl">agriculture</span>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                        <div class="bg-orange-600 h-2.5 rounded-full" style="width: {{ min($cultivatorProgress, 100) }}%">
                        </div>
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

        <!-- 3. Performance Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Highlights -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Highlights Kinerja</h3>

                @if (isset($bestPerformer))
                    <div
                        class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold uppercase text-green-600 dark:text-green-400">Best
                                Performer</span>
                            <span class="material-icons text-yellow-500">emoji_events</span>
                        </div>
                        <p class="font-bold text-gray-800 dark:text-gray-100">{{ $bestPerformer['name'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $bestPerformer['region'] }}</p>
                        <div class="mt-2 text-right">
                            <span
                                class="text-lg font-bold text-green-700 dark:text-green-300">{{ number_format($bestPerformer['protas_achievement'], 1) }}</span>
                            <span class="text-xs text-gray-500">Kg/Ha</span>
                        </div>
                    </div>
                @endif

                @if (isset($underPerformer))
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold uppercase text-red-600 dark:text-red-400">Needs
                                Improvement</span>
                            <span class="material-icons text-red-500 text-sm">error</span>
                        </div>
                        <p class="font-bold text-gray-800 dark:text-gray-100">{{ $underPerformer['name'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $underPerformer['region'] }}</p>
                        <div class="mt-2 text-right">
                            <span
                                class="text-lg font-bold text-red-700 dark:text-red-300">{{ number_format($underPerformer['protas_achievement'], 1) }}</span>
                            <span class="text-xs text-gray-500">Kg/Ha</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Regional Comparison -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Perbandingan Wilayah (Avg Protas)
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Wilayah</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jml Kebun</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata Protas
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($regionalComparison as $region)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $region['region'] }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $region['garden_count'] }}</td>
                                    <td class="px-4 py-2 text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ number_format($region['avg_protas'], 1) }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div class="bg-blue-600 h-2.5 rounded-full"
                                                style="width: {{ min(($region['avg_protas'] / 2000) * 100, 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Production Chart
                const prodCtx = document.getElementById('productionChart').getContext('2d');
                new Chart(prodCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($monthlyProduction->keys()),
                        datasets: [{
                            label: 'Produksi Basah (Kg)',
                            data: @json($monthlyProduction->values()),
                            backgroundColor: 'rgba(34, 197, 94, 0.6)',
                            borderColor: 'rgba(34, 197, 94, 1)',
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
                                    color: 'rgba(156, 163, 175, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // Productivity Chart
                const prodvCtx = document.getElementById('productivityChart').getContext('2d');
                new Chart(prodvCtx, {
                    type: 'line',
                    data: {
                        labels: @json($monthlyProductivity->keys()),
                        datasets: [{
                            label: 'Produktivitas (Kg/Ha)',
                            data: @json($monthlyProductivity->values()),
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
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
                                    color: 'rgba(156, 163, 175, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // Regional Chart
                const regCtx = document.getElementById('regionalChart').getContext('2d');
                new Chart(regCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($regionalChartData->pluck('region')),
                        datasets: [{
                            data: @json($regionalChartData->pluck('avg_protas')),
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.7)',
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(249, 115, 22, 0.7)',
                                'rgba(168, 85, 247, 0.7)'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });

                // Machine Chart
                const macCtx = document.getElementById('machineChart').getContext('2d');
                new Chart(macCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($machineChartData->pluck('garden')),
                        datasets: [{
                            label: 'Umur Mesin',
                            data: @json($machineChartData->pluck('avg_age')),
                            backgroundColor: 'rgba(107, 114, 128, 0.7)',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });

                // Fertilizer Chart
                const fertCtx = document.getElementById('fertilizerChart').getContext('2d');
                new Chart(fertCtx, {
                    type: 'radar',
                    data: {
                        labels: @json($fertilizerChartData->pluck('garden')),
                        datasets: [{
                            label: 'Dosis N',
                            data: @json($fertilizerChartData->pluck('dosage')),
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            r: {
                                angleLines: {
                                    color: 'rgba(156, 163, 175, 0.2)'
                                },
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.2)'
                                },
                                pointLabels: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });

            });
        </script>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- 3. Tabel Detail Kebun -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Detail Performa Kebun</h3>
                    <div class="mt-3 md:mt-0 md:ml-4 flex gap-2">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm flex items-center">
                            <span class="material-icons mr-1 text-sm">visibility</span>View
                        </button>
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm flex items-center">
                            <span class="material-icons mr-1 text-sm">download</span>PDF
                        </button>
                    </div>
                </div>
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
                                    Luas Area (Ha)</th>
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

            <!-- 4. Strategic Monitoring Widgets -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">Strategic Monitoring</h3>

                <!-- Fertilizer -->
                <div class="mb-8">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Penyerapan Pupuk</span>
                        <span
                            class="text-sm font-medium text-blue-700 dark:text-blue-400">{{ number_format($fertilizerProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($fertilizerProgress, 100) }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target vs Realisasi Aplikasi</p>
                </div>

                <!-- Soil Management (Cultivator) -->
                <div class="mb-8">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Soil Management
                            (Kultivator)</span>
                        <span
                            class="text-sm font-medium text-orange-700 dark:text-orange-400">{{ number_format($cultivatorProgress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-orange-500 h-2.5 rounded-full"
                            style="width: {{ min($cultivatorProgress, 100) }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Area Terolah</p>
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
