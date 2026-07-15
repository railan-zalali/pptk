@extends('layouts.admin')

@section('title', 'Tambah Kebun')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 280px; border-radius: 8px; border: 1px solid #d1d5db; }
    .dark #map { border-color: #4b5563; }
    .field-error { @apply text-red-600 text-xs mt-1; }
</style>
@endpush

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Kebun Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi formulir berikut untuk menambahkan data kebun baru.</p>
        </div>

        {{-- Global error summary --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                <p class="text-sm font-medium text-red-700 dark:text-red-400 mb-2">Mohon perbaiki kesalahan berikut:</p>
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.gardens.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kebun Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kebun <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <input name="kebun_name" type="text" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('kebun_name') border-red-400 bg-red-50 dark:bg-red-900/10 @else border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 @enderror text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('kebun_name') }}" placeholder="Masukkan nama kebun">
                    </div>
                    @error('kebun_name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Regional -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wilayah <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">map</span>
                        </span>
                        <select name="regional_id" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('regional_id') border-red-400 bg-red-50 dark:bg-red-900/10 @else border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 @enderror text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Wilayah</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}" {{ old('regional_id') == $region->id ? 'selected' : '' }}>{{ $region->regional_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('regional_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Luas Total Ha -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Luas Total (ha) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">aspect_ratio</span>
                        </span>
                        <input name="luas_total_ha" type="number" step="0.01" min="0.01" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('luas_total_ha') border-red-400 bg-red-50 dark:bg-red-900/10 @else border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 @enderror text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('luas_total_ha') }}" placeholder="Contoh: 125.50">
                    </div>
                    @error('luas_total_ha')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kebun Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kebun <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">category</span>
                        </span>
                        <select name="kebun_type" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="Model" {{ old('kebun_type', 'Model') == 'Model' ? 'selected' : '' }}>Model</option>
                            <option value="Pengembangan" {{ old('kebun_type') == 'Pengembangan' ? 'selected' : '' }}>Pengembangan</option>
                        </select>
                    </div>
                </div>

                <!-- Lokasi: Provinsi + Kabupaten/Kota (Cascading) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Provinsi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">location_city</span>
                        </span>
                        <select name="province" id="province-select"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Provinsi</option>
                            @foreach (array_keys($provinces) as $prov)
                                <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kabupaten / Kota</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">place</span>
                        </span>
                        <select name="location" id="location-select"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('location') border-red-400 @else border-gray-300 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </div>
                    @error('location')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Established At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Berdiri</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input name="established_at" type="date"
                            max="{{ date('Y-m-d') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('established_at') border-red-400 bg-red-50 dark:bg-red-900/10 @else border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 @enderror text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            value="{{ old('established_at') }}">
                    </div>
                    @error('established_at')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                        placeholder="Deskripsi lengkap tentang kebun">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Agro Climate Note -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Agro Klimat</label>
                    <textarea name="agro_climate_note" rows="3"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                        placeholder="Informasi cuaca, tanah, curah hujan, dll">{{ old('agro_climate_note') }}</textarea>
                </div>

                <!-- Koordinat via Peta -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="material-icons-outlined text-sm align-middle">pin_drop</span>
                        Koordinat Lokasi
                        <span class="ml-1 text-xs text-gray-400 font-normal">(klik peta untuk menentukan lokasi)</span>
                    </label>
                    <div id="map" class="mb-3"></div>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-gray-400">Lat, Lng</span>
                            <input name="coordinates" id="coordinates-input" type="text"
                                class="w-full pl-16 pr-4 py-2.5 rounded-lg border @error('coordinates') border-red-400 bg-red-50 @else border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 @enderror text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors font-mono text-sm"
                                placeholder="-7.053, 107.641"
                                value="{{ old('coordinates') }}"
                                pattern="^-?\d{1,3}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$">
                        </div>
                        <button type="button" onclick="clearCoordinates()"
                            class="px-3 py-2 text-sm text-gray-500 hover:text-red-600 border border-gray-300 dark:border-gray-600 rounded-lg transition-colors">
                            <span class="material-icons-outlined text-sm">clear</span>
                        </button>
                    </div>
                    @error('coordinates')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Format: <code>latitude, longitude</code>. Contoh: <code>-7.053, 107.641</code></p>
                </div>

                <!-- Photo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Kebun (opsional)</label>
                    <input name="photo" type="file" accept="image/jpg,image/jpeg,image/png,image/webp"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-300">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, WebP. Maksimal 4MB.</p>
                    @error('photo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan
                </button>
                <a href="{{ route('admin.gardens.index') }}"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ===== Cascading Dropdown: Provinsi → Kab/Kota =====
    const provinceCities = @json($provinces);
    const provinceSelect  = document.getElementById('province-select');
    const locationSelect  = document.getElementById('location-select');
    const oldLocation     = "{{ old('location') }}";
    const oldProvince     = "{{ old('province') }}";

    function populateCities(province, selectedCity) {
        locationSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
        if (province && provinceCities[province]) {
            provinceCities[province].forEach(city => {
                const opt = document.createElement('option');
                opt.value = city;
                opt.textContent = city;
                if (city === selectedCity) opt.selected = true;
                locationSelect.appendChild(opt);
            });
        }
    }

    provinceSelect.addEventListener('change', () => populateCities(provinceSelect.value, ''));
    // Restore old values on validation fail
    if (oldProvince) {
        provinceSelect.value = oldProvince;
        populateCities(oldProvince, oldLocation);
    }

    // ===== Leaflet Map =====
    const defaultLat = -7.0534, defaultLng = 107.6418; // Gambung area
    const map = L.map('map').setView([defaultLat, defaultLng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    let marker = null;
    const coordInput = document.getElementById('coordinates-input');

    // Init marker if old value exists
    if (coordInput.value) {
        const parts = coordInput.value.split(',').map(s => parseFloat(s.trim()));
        if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
            marker = L.marker(parts).addTo(map);
            map.setView(parts, 14);
        }
    }

    map.on('click', e => {
        const { lat, lng } = e.latlng;
        const latStr = lat.toFixed(6), lngStr = lng.toFixed(6);
        coordInput.value = `${latStr}, ${lngStr}`;
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        marker.bindPopup(`<b>Lokasi Kebun</b><br>${latStr}, ${lngStr}`).openPopup();
    });

    // Sync manual input → map
    coordInput.addEventListener('change', () => {
        const parts = coordInput.value.split(',').map(s => parseFloat(s.trim()));
        if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
            if (marker) marker.setLatLng(parts); else marker = L.marker(parts).addTo(map);
            map.setView(parts, 14);
        }
    });

    function clearCoordinates() {
        coordInput.value = '';
        if (marker) { map.removeLayer(marker); marker = null; }
    }
</script>
@endpush
