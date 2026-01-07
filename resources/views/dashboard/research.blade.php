@extends('layouts.pptk')

@section('title', 'Dashboard Penelitian')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">Dashboard Penelitian</h1>
            <p class="text-gray-600 dark:text-gray-300">Analisis komparatif dan wawasan ilmiah untuk penelitian kebun model
                teh</p>
        </div>

        <!-- Research Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode Analisis</label>
                    <select id="periodFilter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <option value="6m">6 Bulan Terakhir</option>
                        <option value="1y" selected>1 Tahun Terakhir</option>
                        <option value="2y">2 Tahun Terakhir</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fokus Penelitian</label>
                    <select id="focusFilter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <option value="productivity" selected>Produktivitas</option>
                        <option value="quality">Kualitas</option>
                        <option value="sustainability">Keberlanjutan</option>
                        <option value="economics">Ekonomi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wilayah</label>
                    <select id="regionFilter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <option value="all" selected>Semua Wilayah</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="updateResearchDashboard()"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <i class="fas fa-sync-alt mr-2"></i>Update Analisis
                    </button>
                </div>
            </div>
        </div>

        <!-- Key Research Insights -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/50 rounded-lg shadow-lg p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-100">Temuan Utama</h3>
                    <div class="bg-green-200 dark:bg-green-800 p-2 rounded-full">
                        <i class="fas fa-microscope text-green-600 dark:text-green-300"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 dark:bg-green-400 rounded-full mt-2 mr-3"></div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Varietas <strong>{{ $topVariety }}</strong>
                            menunjukkan
                            produktivitas tertinggi</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 dark:bg-green-400 rounded-full mt-2 mr-3"></div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Elevasi {{ $optimalElevation }} mdpl optimal
                            untuk pertumbuhan</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 dark:bg-green-400 rounded-full mt-2 mr-3"></div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">pH tanah {{ $optimalPh }} memberikan hasil
                            terbaik</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/50 rounded-lg shadow-lg p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-100">Statistik Penelitian</h3>
                    <div class="bg-blue-200 dark:bg-blue-800 p-2 rounded-full">
                        <i class="fas fa-chart-bar text-blue-600 dark:text-blue-300"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                            {{ number_format($researchCount, 0) }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Penelitian Aktif</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                            {{ number_format($publicationCount, 0) }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Publikasi Ilmiah</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                            {{ number_format($collaborationCount, 0) }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Kolaborasi</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ number_format($datasetCount, 0) }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Dataset</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/50 rounded-lg shadow-lg p-6 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-100">Tren Terbaru</h3>
                    <div class="bg-purple-200 dark:bg-purple-800 p-2 rounded-full">
                        <i class="fas fa-trending-up text-purple-600 dark:text-purple-300"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Produktivitas</span>
                        <span
                            class="text-sm font-semibold text-{{ $productivityTrend >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $productivityTrend >= 0 ? 'green' : 'red' }}-400">
                            {{ $productivityTrend >= 0 ? '+' : '' }}{{ number_format($productivityTrend, 1) }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Kualitas Daun</span>
                        <span
                            class="text-sm font-semibold text-{{ $qualityTrend >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $qualityTrend >= 0 ? 'green' : 'red' }}-400">
                            {{ $qualityTrend >= 0 ? '+' : '' }}{{ number_format($qualityTrend, 1) }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Keberlanjutan</span>
                        <span
                            class="text-sm font-semibold text-{{ $sustainabilityTrend >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $sustainabilityTrend >= 0 ? 'green' : 'red' }}-400">
                            {{ $sustainabilityTrend >= 0 ? '+' : '' }}{{ number_format($sustainabilityTrend, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparative Analysis Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Productivity Comparison -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Perbandingan Produktivitas per
                        Varietas</h3>
                    <button onclick="exportChart('productivityComparison')"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="productivityComparisonChart"></canvas>
                </div>
            </div>

            <!-- Environmental Factors -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pengaruh Faktor Lingkungan</h3>
                    <button onclick="exportChart('environmentalFactors')"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="environmentalFactorsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Research Correlation Matrix -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Matriks Korelasi Faktor Penelitian</h3>
            <div class="grid grid-cols-5 gap-2 text-xs">
                <div></div>
                @foreach (['Produktivitas', 'Kualitas', 'pH Tanah', 'Curah Hujan', 'Elevasi'] as $factor)
                    <div class="text-center font-semibold text-gray-700 dark:text-gray-300 p-2">{{ $factor }}</div>
                @endforeach
                @foreach ($correlationMatrix as $rowFactor => $correlations)
                    <div class="font-semibold text-gray-700 dark:text-gray-300 p-2">{{ $rowFactor }}</div>
                    @foreach ($correlations as $colFactor => $correlation)
                        @php
                            $corrColor =
                                $correlation >= 0.6
                                    ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-100'
                                    : ($correlation >= 0.3
                                        ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-100'
                                        : ($correlation <= -0.3
                                            ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-100'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'));
                        @endphp
                        <div class="text-center p-2 rounded {{ $corrColor }}">
                            {{ number_format($correlation, 2) }}
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="mt-4 text-xs text-gray-600 dark:text-gray-400">
                <p><strong>Keterangan:</strong> Korelasi mendekati 1 menunjukkan hubungan positif kuat, mendekati -1
                    menunjukkan hubungan negatif kuat</p>
            </div>
        </div>

        <!-- Research Publications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Publikasi Penelitian Terbaru</h3>
                <a href="{{ route('admin.insights.index') }}"
                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @foreach ($recentPublications as $publication)
                    <div
                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ $publication->title }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $publication->authors }} •
                                    {{ $publication->journal }}</p>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach ($publication->keywords as $keyword)
                                        <span
                                            class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-100 rounded text-xs">{{ $keyword }}</span>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $publication->published_date->format('d M Y') }}</p>
                            </div>
                            <div class="mt-3 md:mt-0 md:ml-4 flex gap-2">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-download mr-1"></i>PDF
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Research Collaboration Network -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Jaringan Kolaborasi Penelitian</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Institusi Mitra</h4>
                    <div class="space-y-2">
                        @foreach ($collaborationInstitutions as $institution)
                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $institution->name }}</span>
                                <span
                                    class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-100 px-2 py-1 rounded">{{ $institution->projects }}
                                    proyek</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Peneliti Utama</h4>
                    <div class="space-y-2">
                        @foreach ($topResearchers as $researcher)
                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $researcher->name }}</span>
                                <span
                                    class="text-xs bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-100 px-2 py-1 rounded">{{ $researcher->publications }}
                                    publikasi</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Area Penelitian</h4>
                    <div class="space-y-2">
                        @foreach ($researchAreas as $area)
                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $area->name }}</span>
                                <span
                                    class="text-xs bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-100 px-2 py-1 rounded">{{ $area->studies }}
                                    studi</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart data from controller
        const chartData = @json($chartData);

        // Theme colors helper
        function getThemeColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                text: isDark ? '#e5e7eb' : '#374151', // gray-200 : gray-700
                grid: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                backdrop: isDark ? 'rgba(31, 41, 55, 0.8)' : 'rgba(255, 255, 255, 0.8)'
            };
        }

        const initialColors = getThemeColors();

        // Productivity Comparison Chart
        const productivityCtx = document.getElementById('productivityComparisonChart').getContext('2d');
        const productivityChart = new Chart(productivityCtx, {
            type: 'bar',
            data: {
                labels: chartData.productivityComparison.labels,
                datasets: [{
                    label: 'Produktivitas (kg/ha)',
                    data: chartData.productivityComparison.data,
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
                        title: {
                            display: true,
                            text: 'Produktivitas (kg/ha)',
                            color: initialColors.text
                        },
                        ticks: {
                            color: initialColors.text
                        },
                        grid: {
                            color: initialColors.grid
                        }
                    },
                    x: {
                        ticks: {
                            color: initialColors.text
                        },
                        grid: {
                            color: initialColors.grid
                        }
                    }
                }
            }
        });

        // Environmental Factors Chart
        const environmentalCtx = document.getElementById('environmentalFactorsChart').getContext('2d');
        const environmentalChart = new Chart(environmentalCtx, {
            type: 'radar',
            data: {
                labels: chartData.environmentalFactors.labels,
                datasets: [{
                    label: 'Faktor Optimal',
                    data: chartData.environmentalFactors.optimal,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.2)',
                    pointBackgroundColor: '#16a34a'
                }, {
                    label: 'Kondisi Aktual',
                    data: chartData.environmentalFactors.actual,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 200,
                animation: {
                    duration: 0
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        pointLabels: {
                            color: initialColors.text
                        },
                        grid: {
                            color: initialColors.grid
                        },
                        ticks: {
                            color: initialColors.text,
                            backdropColor: initialColors.backdrop
                        }
                    }
                }
            }
        });

        // Update charts when theme changes
        function updateCharts() {
            const colors = getThemeColors();

            // Update Productivity Chart
            productivityChart.options.scales.y.ticks.color = colors.text;
            productivityChart.options.scales.y.title.color = colors.text;
            productivityChart.options.scales.y.grid.color = colors.grid;
            productivityChart.options.scales.x.ticks.color = colors.text;
            productivityChart.options.scales.x.grid.color = colors.grid;
            productivityChart.update();

            // Update Environmental Chart
            environmentalChart.options.scales.r.pointLabels.color = colors.text;
            environmentalChart.options.scales.r.grid.color = colors.grid;
            environmentalChart.options.scales.r.ticks.color = colors.text;
            environmentalChart.options.scales.r.ticks.backdropColor = colors.backdrop;
            environmentalChart.update();
        }

        // Listen for theme changes using MutationObserver on html element
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    updateCharts();
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true
        });

        // Research dashboard functions
        function updateResearchDashboard() {
            const period = document.getElementById('periodFilter').value;
            const focus = document.getElementById('focusFilter').value;
            const region = document.getElementById('regionFilter').value;

            const url = new URL(window.location);
            url.searchParams.set('period', period);
            url.searchParams.set('focus', focus);
            url.searchParams.set('region', region);

            window.location.href = url.toString();
        }

        function exportChart(chartId) {
            const canvas = document.getElementById(chartId + 'Chart');
            const url = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = chartId + '_' + new Date().toISOString().slice(0, 10) + '.png';
            link.href = url;
            link.click();
        }

        // Auto-refresh every 10 minutes for research dashboard
        setInterval(function() {
            location.reload();
        }, 600000);
    </script>
@endpush
