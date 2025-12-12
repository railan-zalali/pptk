@extends('layouts.pptk')

@section('title', 'Dashboard Penelitian')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 mb-2">Dashboard Penelitian</h1>
            <p class="text-gray-600">Analisis komparatif dan wawasan ilmiah untuk penelitian kebun model teh</p>
        </div>

        <!-- Research Filters -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Analisis</label>
                    <select id="periodFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="6m">6 Bulan Terakhir</option>
                        <option value="1y" selected>1 Tahun Terakhir</option>
                        <option value="2y">2 Tahun Terakhir</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fokus Penelitian</label>
                    <select id="focusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="productivity" selected>Produktivitas</option>
                        <option value="quality">Kualitas</option>
                        <option value="sustainability">Keberlanjutan</option>
                        <option value="economics">Ekonomi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah</label>
                    <select id="regionFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-lg p-6 border border-green-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-green-800">Temuan Utama</h3>
                    <div class="bg-green-200 p-2 rounded-full">
                        <i class="fas fa-microscope text-green-600"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                        <p class="text-sm text-gray-700">Varietas <strong>{{ $topVariety }}</strong> menunjukkan
                            produktivitas tertinggi</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                        <p class="text-sm text-gray-700">Elevasi {{ $optimalElevation }} mdpl optimal untuk pertumbuhan</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                        <p class="text-sm text-gray-700">pH tanah {{ $optimalPh }} memberikan hasil terbaik</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-lg p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-blue-800">Statistik Penelitian</h3>
                    <div class="bg-blue-200 p-2 rounded-full">
                        <i class="fas fa-chart-bar text-blue-600"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($researchCount, 0) }}</p>
                        <p class="text-xs text-gray-600">Penelitian Aktif</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($publicationCount, 0) }}</p>
                        <p class="text-xs text-gray-600">Publikasi Ilmiah</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($collaborationCount, 0) }}</p>
                        <p class="text-xs text-gray-600">Kolaborasi</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($datasetCount, 0) }}</p>
                        <p class="text-xs text-gray-600">Dataset</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-lg p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-purple-800">Tren Terbaru</h3>
                    <div class="bg-purple-200 p-2 rounded-full">
                        <i class="fas fa-trending-up text-purple-600"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">Produktivitas</span>
                        <span class="text-sm font-semibold text-{{ $productivityTrend >= 0 ? 'green' : 'red' }}-600">
                            {{ $productivityTrend >= 0 ? '+' : '' }}{{ number_format($productivityTrend, 1) }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">Kualitas Daun</span>
                        <span class="text-sm font-semibold text-{{ $qualityTrend >= 0 ? 'green' : 'red' }}-600">
                            {{ $qualityTrend >= 0 ? '+' : '' }}{{ number_format($qualityTrend, 1) }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">Keberlanjutan</span>
                        <span class="text-sm font-semibold text-{{ $sustainabilityTrend >= 0 ? 'green' : 'red' }}-600">
                            {{ $sustainabilityTrend >= 0 ? '+' : '' }}{{ number_format($sustainabilityTrend, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparative Analysis Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Productivity Comparison -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Perbandingan Produktivitas per Varietas</h3>
                    <button onclick="exportChart('productivityComparison')" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="productivityComparisonChart"></canvas>
                </div>
            </div>

            <!-- Environmental Factors -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Pengaruh Faktor Lingkungan</h3>
                    <button onclick="exportChart('environmentalFactors')" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="environmentalFactorsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Research Correlation Matrix -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Matriks Korelasi Faktor Penelitian</h3>
            <div class="grid grid-cols-5 gap-2 text-xs">
                <div></div>
                @foreach (['Produktivitas', 'Kualitas', 'pH Tanah', 'Curah Hujan', 'Elevasi'] as $factor)
                    <div class="text-center font-semibold text-gray-700 p-2">{{ $factor }}</div>
                @endforeach
                @foreach ($correlationMatrix as $rowFactor => $correlations)
                    <div class="font-semibold text-gray-700 p-2">{{ $rowFactor }}</div>
                    @foreach ($correlations as $colFactor => $correlation)
                        @php
                            $corrColor =
                                $correlation >= 0.6
                                    ? 'bg-green-100 text-green-800'
                                    : ($correlation >= 0.3
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($correlation <= -0.3
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-gray-100 text-gray-800'));
                        @endphp
                        <div class="text-center p-2 rounded {{ $corrColor }}">
                            {{ number_format($correlation, 2) }}
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="mt-4 text-xs text-gray-600">
                <p><strong>Keterangan:</strong> Korelasi mendekati 1 menunjukkan hubungan positif kuat, mendekati -1
                    menunjukkan hubungan negatif kuat</p>
            </div>
        </div>

        <!-- Research Publications -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Publikasi Penelitian Terbaru</h3>
                <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @foreach ($recentPublications as $publication)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 mb-1">{{ $publication->title }}</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $publication->authors }} •
                                    {{ $publication->journal }}</p>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach ($publication->keywords as $keyword)
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">{{ $keyword }}</span>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500">{{ $publication->published_date->format('d M Y') }}</p>
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
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Jaringan Kolaborasi Penelitian</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Institusi Mitra</h4>
                    <div class="space-y-2">
                        @foreach ($collaborationInstitutions as $institution)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ $institution->name }}</span>
                                <span
                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $institution->projects }}
                                    proyek</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Peneliti Utama</h4>
                    <div class="space-y-2">
                        @foreach ($topResearchers as $researcher)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ $researcher->name }}</span>
                                <span
                                    class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">{{ $researcher->publications }}
                                    publikasi</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Area Penelitian</h4>
                    <div class="space-y-2">
                        @foreach ($researchAreas as $area)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ $area->name }}</span>
                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">{{ $area->studies }}
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
                            text: 'Produktivitas (kg/ha)'
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
                        max: 100
                    }
                }
            }
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
