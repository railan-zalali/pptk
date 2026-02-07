@extends('layouts.pptk')

@section('title', $page->title ?? 'Dashboard Penelitian')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-green-800 text-white">
        @if ($page->hero_photo_path)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $page->hero_photo_path) }}" alt="Hero"
                    class="w-full h-full object-cover opacity-30">
            </div>
        @endif
        <div class="relative container mx-auto px-4 py-16">
            <h1 class="text-4xl font-bold mb-4">{{ $page->title ?? 'Dashboard Penelitian' }}</h1>
            <p class="text-xl max-w-2xl text-green-100">
                {{ $page->subtitle ?? 'Analisis komparatif dan wawasan ilmiah untuk penelitian kebun model teh' }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- 1. Ringkasan Penelitian -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons mr-2 text-green-600">analytics</span> Ringkasan Penelitian
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Jumlah Kegiatan -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase">Jumlah Kegiatan</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ number_format($page->meta['summary']['total_activities'] ?? 0) }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <span class="material-icons text-blue-600">science</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-4">Total penelitian terdaftar</p>
                </div>

                <!-- Card 2: Total Anggaran -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase">Total Anggaran</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                Rp {{ number_format($page->meta['summary']['total_budget'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <span class="material-icons text-green-600">payments</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-4">Alokasi dana penelitian</p>
                </div>

                <!-- Card 3: Sisa Anggaran -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase">Sisa Anggaran</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                Rp {{ number_format($page->meta['summary']['remaining_budget'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <span class="material-icons text-purple-600">account_balance_wallet</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-4">Dana tersisa</p>
                </div>
            </div>
        </div>

        <!-- 2. Informasi Penelitian -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons mr-2 text-green-600">description</span> Informasi Penelitian
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Riset Internal</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $page->meta['info']['internal_research'] ?? 'Belum ada informasi.' }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Riset Eksternal</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $page->meta['info']['external_research'] ?? 'Belum ada informasi.' }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Inkubasi Riset</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $page->meta['info']['incubation'] ?? 'Belum ada informasi.' }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Ringkasan RKAP & Pencairan</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $page->meta['info']['rkap_notes'] ?? 'Belum ada informasi.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. Daftar Kegiatan Penelitian -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons mr-2 text-green-600">list_alt</span> Daftar Kegiatan Penelitian
            </h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                    No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul Penelitian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tahun</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($page->meta['activities'] ?? [] as $index => $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $activity['title'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $activity['type'] == 'Internal'
                                            ? 'bg-green-100 text-green-800'
                                            : ($activity['type'] == 'Eksternal'
                                                ? 'bg-blue-100 text-blue-800'
                                                : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $activity['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity['year'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data
                                        kegiatan penelitian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4. Dokumentasi & Laporan -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons mr-2 text-green-600">folder_open</span> Dokumentasi & Laporan
            </h2>
            @if (!empty($page->files))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($page->files as $file)
                        <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow flex items-start">
                            <div class="bg-gray-100 p-2 rounded mr-3">
                                <span class="material-icons text-gray-600">description</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate" title="{{ $file['name'] }}">
                                    {{ $file['name'] }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ round($file['size'] / 1024) }} KB •
                                    {{ \Carbon\Carbon::parse($file['uploaded_at'] ?? now())->format('d M Y') }}
                                </p>
                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                    class="text-xs text-green-600 hover:text-green-800 font-medium mt-2 inline-block">
                                    Download / Lihat
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg p-8 text-center border border-dashed border-gray-300">
                    <span class="material-icons text-gray-400 text-4xl mb-2">folder_off</span>
                    <p class="text-gray-500">Belum ada dokumen yang diunggah.</p>
                </div>
            @endif
        </div>

        <!-- 5. Analisis Statistik Otomatis -->
        <div class="mt-16 pt-8 border-t border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="material-icons mr-2 text-blue-600">trending_up</span> Analisis Statistik Otomatis
            </h2>

            <!-- Filters -->
            <form action="{{ route('dashboard.research') }}" method="GET"
                class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <select name="period"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="6m" {{ request('period') == '6m' ? 'selected' : '' }}>6 Bulan Terakhir
                            </option>
                            <option value="1y" {{ request('period', '1y') == '1y' ? 'selected' : '' }}>1 Tahun Terakhir
                            </option>
                            <option value="2y" {{ request('period') == '2y' ? 'selected' : '' }}>2 Tahun Terakhir
                            </option>
                            <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua Data</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fokus Analisis</label>
                        <select name="focus"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="productivity"
                                {{ request('focus', 'productivity') == 'productivity' ? 'selected' : '' }}>Produktivitas
                            </option>
                            <option value="quality" {{ request('focus') == 'quality' ? 'selected' : '' }}>Kualitas
                            </option>
                            <option value="sustainability" {{ request('focus') == 'sustainability' ? 'selected' : '' }}>
                                Keberlanjutan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                        <select name="region"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all">Semua Wilayah</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ request('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                            <span class="material-icons text-sm mr-2">filter_list</span> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Research Count -->
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Total Riset</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $researchCount }}</div>
                    <div class="text-xs text-gray-400 mt-1">Project aktif</div>
                </div>
                <!-- Publication Count -->
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-pink-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Publikasi</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $publicationCount }}</div>
                    <div class="text-xs text-gray-400 mt-1">Jurnal & Artikel</div>
                </div>
                <!-- Dataset Count -->
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-teal-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Dataset</div>
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($datasetCount) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Poin data terkumpul</div>
                </div>
                <!-- Collaboration Count -->
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-orange-500">
                    <div class="text-gray-500 text-xs uppercase font-bold">Kolaborasi</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $collaborationCount }}</div>
                    <div class="text-xs text-gray-400 mt-1">Mitra eksternal</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Left: Correlation Matrix -->
                <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Matriks Korelasi Faktor</h3>
                    <p class="text-sm text-gray-500 mb-4">Hubungan antara faktor lingkungan dengan
                        {{ request('focus', 'productivity') == 'productivity' ? 'Produktivitas' : (request('focus') == 'quality' ? 'Kualitas' : 'Keberlanjutan') }}.
                    </p>

                    <div class="space-y-4">
                        @foreach ($correlationMatrix as $factor => $value)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="capitalize text-gray-700">{{ $factor }}</span>
                                    <span
                                        class="font-medium {{ $value > 0.5 ? 'text-green-600' : ($value < -0.5 ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ number_format($value, 2) }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <!-- Normalize -1 to 1 range to 0 to 100% width -->
                                    <div class="h-2 rounded-full {{ $value > 0 ? 'bg-blue-500' : 'bg-red-500' }}"
                                        style="width: {{ abs($value) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 p-3 bg-gray-50 rounded text-xs text-gray-500">
                        <p><strong>Catatan:</strong> Nilai mendekati 1.00 menunjukkan korelasi positif kuat, -1.00 korelasi
                            negatif kuat.</p>
                    </div>
                </div>

                <!-- Right: Comparative Analysis -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Analisis Komparatif Kebun</h3>
                        <div class="flex space-x-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Elevasi Optimal:
                                {{ $optimalElevation }} mdpl</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">pH Optimal:
                                {{ $optimalPh }}</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kebun</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lokasi</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ request('focus', 'productivity') == 'productivity' ? 'Produktivitas (kg/ha)' : (request('focus') == 'quality' ? 'Skor Kualitas' : 'RKAP (%)') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($comparativeData as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $data['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data['location'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono text-gray-900">
                                            {{ number_format($data['productivity'], 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($data['productivity'] > 0)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    No Data
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada
                                            data untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Trends Overview -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Performa Global</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="p-3 bg-blue-100 rounded-full mr-4">
                            <span class="material-icons text-blue-600">trending_up</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Tren Produktivitas</p>
                            <p
                                class="text-lg font-bold {{ $productivityTrend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $productivityTrend >= 0 ? '+' : '' }}{{ number_format($productivityTrend, 1) }}%
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-green-50 rounded-lg">
                        <div class="p-3 bg-green-100 rounded-full mr-4">
                            <span class="material-icons text-green-600">verified</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Tren Kualitas</p>
                            <p class="text-lg font-bold {{ $qualityTrend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $qualityTrend >= 0 ? '+' : '' }}{{ number_format($qualityTrend, 1) }}%
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-purple-50 rounded-lg">
                        <div class="p-3 bg-purple-100 rounded-full mr-4">
                            <span class="material-icons text-purple-600">eco</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Tren Keberlanjutan</p>
                            <p
                                class="text-lg font-bold {{ $sustainabilityTrend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $sustainabilityTrend >= 0 ? '+' : '' }}{{ number_format($sustainabilityTrend, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
