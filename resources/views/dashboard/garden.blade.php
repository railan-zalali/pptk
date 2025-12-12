@extends('layouts.pptk')

@section('title', 'Dashboard Kebun Model')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 mb-2">Dashboard Kebun Model</h1>
            <p class="text-gray-600">Monitoring dan analisis kinerja kebun model teh</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">RKAP Bulan Ini</p>
                        <p class="text-2xl font-bold text-teal-600">{{ number_format($rkapMonthly, 1) }}%</p>
                    </div>
                    <div class="bg-teal-100 p-3 rounded-full">
                        <i class="fas fa-percentage text-teal-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">YTD: {{ number_format($rkapYtd, 1) }}%</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Produksi Basah Bulanan</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($wetMonthlyTotal, 0) }} kg</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-tint text-indigo-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">Rata-rata: {{ number_format($wetMonthlyAvg, 1) }} kg</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Mutu Pucuk Bulanan</p>
                        <p class="text-2xl font-bold text-rose-600">{{ number_format($qualityMonthlyAvg, 1) }}</p>
                    </div>
                    <div class="bg-rose-100 p-3 rounded-full">
                        <i class="fas fa-seedling text-rose-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">Produksi Kering YTD: {{ number_format($dryProductionYtd, 0) }} kg</span>
                </div>
            </div>
        </div>

        <!-- Garden Selector -->
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-lg font-semibold text-gray-800">Pilih Kebun Model</h2>
                    <p class="text-sm text-gray-600">Pilih kebun untuk melihat detail monitoring</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <select id="gardenSelector" class="border border-gray-300 rounded-lg px-4 py-2 min-w-64 touch-friendly">
                        <option value="">Semua Kebun</option>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}"
                                {{ $selectedGarden && $selectedGarden->id == $garden->id ? 'selected' : '' }}>
                                {{ $garden->name }} - {{ $garden->region->name }}
                            </option>
                        @endforeach
                    </select>
                    <button onclick="updateDashboard()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center touch-friendly">
                        <i class="fas fa-sync-alt mr-2"></i>Update
                    </button>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Produksi</p>
                        <p class="text-2xl font-bold text-green-600" id="totalProduction">
                            {{ number_format($totalProduction, 0) }} kg</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-green-600 font-semibold">+{{ number_format($productionGrowth, 1) }}%</span>
                    <span class="text-gray-500">vs bulan lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Rata-rata Produktivitas</p>
                        <p class="text-2xl font-bold text-blue-600" id="avgProductivity">
                            {{ number_format($avgProductivity, 1) }} kg/ha</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-leaf text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-{{ $productivityTrend >= 0 ? 'green' : 'red' }}-600 font-semibold">
                        {{ $productivityTrend >= 0 ? '+' : '' }}{{ number_format($productivityTrend, 1) }}%
                    </span>
                    <span class="text-gray-500">trend</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Luas Area</p>
                        <p class="text-2xl font-bold text-purple-600" id="totalArea">{{ number_format($totalArea, 1) }} ha
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-map text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">{{ $gardenCount }} kebun model</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Kunjungan Bulan Ini</p>
                        <p class="text-2xl font-bold text-yellow-600" id="monthlyVisits">{{ $monthlyVisits }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <span class="text-yellow-600 font-semibold">{{ number_format($visitGrowth, 1) }}%</span>
                    <span class="text-gray-500">pertumbuhan</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 mb-8">
            <!-- Production Chart -->
            <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                    <h3 class="text-lg font-semibold text-gray-800">Tren Produksi</h3>
                    <div class="flex gap-1">
                        <button onclick="changeChartPeriod('production', '6m')"
                            class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded touch-friendly">6B</button>
                        <button onclick="changeChartPeriod('production', '1y')"
                            class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded touch-friendly">1T</button>
                        <button onclick="changeChartPeriod('production', 'all')"
                            class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded touch-friendly">All</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="productionChart" aria-label="Grafik Produksi Kebun Model per Bulan" role="img"></canvas>
                </div>
            </div>

            <!-- Productivity Chart -->
            <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                    <h3 class="text-lg font-semibold text-gray-800">Tren Produktivitas</h3>
                    <div class="flex gap-1">
                        <button onclick="changeChartPeriod('productivity', '6m')"
                            class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded touch-friendly">6B</button>
                        <button onclick="changeChartPeriod('productivity', '1y')"
                            class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded touch-friendly">1T</button>
                        <button onclick="changeChartPeriod('productivity', 'all')"
                            class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded touch-friendly">All</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="productivityChart" aria-label="Grafik Tren Produktivitas Kebun Model"
                        role="img"></canvas>
                </div>
            </div>
        </div>

        <!-- Regional Comparison -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan Regional</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div style="position: relative; height: 240px;">
                    <h4 class="text-md font-medium text-gray-700 mb-2">Produksi per Wilayah</h4>
                    <canvas id="regionalProductionChart" aria-label="Grafik Produksi per Wilayah"
                        role="img"></canvas>
                </div>
                <div style="position: relative; height: 240px;">
                    <h4 class="text-md font-medium text-gray-700 mb-2">Produktivitas per Wilayah</h4>
                    <canvas id="regionalProductivityChart" aria-label="Grafik Produktivitas per Wilayah"
                        role="img"></canvas>
                </div>
            </div>
        </div>

        <!-- Garden Performance Table -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Performa Kebun Model</h3>
                <div class="flex gap-2">
                    <button onclick="exportData()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                    <select id="sortBy" class="border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        onchange="sortTable()">
                        <option value="productivity">Urutkan: Produktivitas</option>
                        <option value="production">Urutkan: Produksi</option>
                        <option value="area">Urutkan: Luas Area</option>
                        <option value="name">Urutkan: Nama</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kebun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Wilayah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Luas
                                (ha)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produksi (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produktivitas (kg/ha)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="gardenTableBody">
                        @foreach ($gardens as $garden)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $garden->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $garden->tea_variety }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $garden->region->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($garden->area, 1) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($garden->productionData->sum('production'), 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ number_format($garden->productionData->avg('productivity') ?? 0, 1) }} kg/ha
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $garden->status == 'active' ? 'green' : ($garden->status == 'maintenance' ? 'yellow' : 'red') }}-100 text-{{ $garden->status == 'active' ? 'green' : ($garden->status == 'maintenance' ? 'yellow' : 'red') }}-800">
                                        {{ ucfirst($garden->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('strategic.garden', [$garden->region->id, $garden->id]) }}"
                                        class="text-green-600 hover:text-green-900 mr-3"
                                        title="Lihat detail kebun {{ $garden->name }}"
                                        aria-label="Lihat detail kebun {{ $garden->name }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.research', ['garden_id' => $garden->id]) }}"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Lihat analisis penelitian untuk {{ $garden->name }}"
                                        aria-label="Lihat analisis penelitian untuk {{ $garden->name }}">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart data from controller
        const productionData = @json($productionChartData);
        const productivityData = @json($productivityChartData);
        const regionalData = @json($regionalChartData);

        // Production Chart
        const productionCtx = document.getElementById('productionChart').getContext('2d');
        const productionChart = new Chart(productionCtx, {
            type: 'line',
            data: {
                labels: productionData.labels,
                datasets: [{
                    label: 'Produksi (kg)',
                    data: productionData.data,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    tension: 0.4,
                    fill: true
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' kg';
                            }
                        }
                    }
                }
            }
        });

        // Productivity Chart
        const productivityCtx = document.getElementById('productivityChart').getContext('2d');
        const productivityChart = new Chart(productivityCtx, {
            type: 'line',
            data: {
                labels: productivityData.labels,
                datasets: [{
                    label: 'Produktivitas (kg/ha)',
                    data: productivityData.data,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' kg/ha';
                            }
                        }
                    }
                }
            }
        });

        // Regional Production Chart
        const regionalProductionCtx = document.getElementById('regionalProductionChart').getContext('2d');
        const regionalProductionChart = new Chart(regionalProductionCtx, {
            type: 'doughnut',
            data: {
                labels: regionalData.production.labels,
                datasets: [{
                    data: regionalData.production.data,
                    backgroundColor: [
                        '#16a34a',
                        '#2563eb',
                        '#dc2626',
                        '#ca8a04',
                        '#9333ea'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 200,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Regional Productivity Chart
        const regionalProductivityCtx = document.getElementById('regionalProductivityChart').getContext('2d');
        const regionalProductivityChart = new Chart(regionalProductivityCtx, {
            type: 'bar',
            data: {
                labels: regionalData.productivity.labels,
                datasets: [{
                    label: 'Produktivitas (kg/ha)',
                    data: regionalData.productivity.data,
                    backgroundColor: '#16a34a',
                    borderColor: '#15803d',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 200,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' kg/ha';
                            }
                        }
                    }
                }
            }
        });

        // Dashboard functions
        function updateDashboard() {
            const gardenId = document.getElementById('gardenSelector').value;
            const url = new URL(window.location);

            if (gardenId) {
                url.searchParams.set('garden_id', gardenId);
            } else {
                url.searchParams.delete('garden_id');
            }

            window.location.href = url.toString();
        }

        function changeChartPeriod(chartType, period) {
            // Implement chart period change logic
            console.log(`Changing ${chartType} chart to ${period} period`);
        }

        function sortTable() {
            const sortBy = document.getElementById('sortBy').value;
            // Implement table sorting logic
            console.log(`Sorting table by ${sortBy}`);
        }

        function exportData() {
            // Implement data export functionality
            alert('Fitur export akan segera tersedia');
        }

        // Auto-refresh every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
@endpush
