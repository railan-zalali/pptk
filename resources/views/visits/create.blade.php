@extends('layouts.pptk')

@section('title', 'Tambah Kunjungan Dinas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 mb-2">Tambah Kunjungan Dinas</h1>
            <p class="text-gray-600">Dokumentasikan kunjungan ke kebun model teh</p>
        </div>

        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-green-600 hover:text-green-800">Beranda</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('visits.index') }}" class="text-green-600 hover:text-green-800">Kunjungan Dinas</a>
                </li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-700">Tambah Kunjungan</li>
            </ol>
        </nav>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('visits.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded flex items-center">
                        <span class="material-icons text-base mr-2">warning</span>
                        <div>
                            <div class="font-semibold mb-2">Terjadi kesalahan validasi</div>
                            <ul class="list-disc pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-green-800 mb-4">Informasi Dasar</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Kunjungan
                                *</label>
                            <input type="text" name="title" id="title" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: Kunjungan Monitoring Kebun Cikawao" value="{{ old('title') }}">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="garden_id" class="block text-sm font-medium text-gray-700 mb-2">Kebun Model
                                *</label>
                            <select name="garden_id" id="garden_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 appearance-none">
                                <option value="">Pilih Kebun</option>
                                @foreach ($gardens as $garden)
                                    <option value="{{ $garden->id }}"
                                        {{ old('garden_id') == $garden->id ? 'selected' : '' }}>
                                        {{ $garden->name }} - {{ $garden->region->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('garden_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kunjungan
                                *</label>
                            <input type="date" name="visit_date" id="visit_date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                value="{{ old('visit_date', date('Y-m-d')) }}">
                            @error('visit_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durasi (jam)
                                *</label>
                            <input type="number" name="duration" id="duration" required min="1" max="24"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                value="{{ old('duration', '2') }}">
                            @error('duration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-green-800 mb-4">Peserta Kunjungan</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="participants_count" class="block text-sm font-medium text-gray-700 mb-2">Jumlah
                                Peserta *</label>
                            <input type="number" name="participants_count" id="participants_count" required min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                value="{{ old('participants_count', '5') }}">
                            @error('participants_count')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="participants_list" class="block text-sm font-medium text-gray-700 mb-2">Daftar
                                Peserta</label>
                            <textarea name="participants_list" id="participants_list" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="Tuliskan nama-nama peserta (opsional)">{{ old('participants_list') }}</textarea>
                            @error('participants_list')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visit Details -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-green-800 mb-4">Detail Kunjungan</h2>

                    <div class="space-y-6">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi
                                Kunjungan *</label>
                            <textarea name="description" id="description" required rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="Jelaskan tujuan, kegiatan, dan hasil kunjungan">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="objectives" class="block text-sm font-medium text-gray-700 mb-2">Tujuan
                                Kunjungan</label>
                            <textarea name="objectives" id="objectives" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="Tuliskan tujuan spesifik kunjungan (opsional)">{{ old('objectives') }}</textarea>
                            @error('objectives')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="findings" class="block text-sm font-medium text-gray-700 mb-2">Temuan/Hasil</label>
                            <textarea name="findings" id="findings" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="Tuliskan temuan atau hasil kunjungan (opsional)">{{ old('findings') }}</textarea>
                            @error('findings')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="recommendations"
                                class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                            <textarea name="recommendations" id="recommendations" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="Tuliskan rekomendasi tindak lanjut (opsional)">{{ old('recommendations') }}</textarea>
                            @error('recommendations')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rating and Status -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-green-800 mb-4">Penilaian dan Status</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating Kunjungan
                                *</label>
                            <select name="rating" id="rating" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 appearance-none">
                                <option value="">Pilih Rating</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bintang
                                        {{ $i <= 2 ? ' (Buruk)' : ($i <= 3 ? ' (Cukup)' : ($i <= 4 ? ' (Baik)' : ' (Sangat Baik)')) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Kunjungan
                                *</label>
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 appearance-none">
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan
                                </option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-green-800 mb-4">Dokumentasi Foto</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">Unggah Foto</label>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            <p class="text-xs text-gray-500 mt-1">Unggah foto-foto dokumentasi kunjungan (maksimal 5 foto)
                            </p>
                            @error('photos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="photoPreview" class="grid grid-cols-3 gap-2">
                            <!-- Preview will be shown here -->
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-wrap gap-4">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                        <span class="material-icons text-base mr-2">save</span>
                        Simpan Kunjungan
                    </button>
                    <a href="{{ route('visits.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                        <span class="material-icons text-base mr-2">close</span>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Photo preview functionality
        document.getElementById('photos').addEventListener('change', function(e) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = '';

            if (e.target.files) {
                Array.from(e.target.files).slice(0, 5).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className =
                            'aspect-square bg-gray-200 rounded flex items-center justify-center overflow-hidden';
                        div.innerHTML =
                            `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview ${index + 1}">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['title', 'garden_id', 'visit_date', 'duration', 'participants_count',
                'description', 'rating', 'status'
            ];
            let isValid = true;

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi.');
            }
        });
    </script>
@endpush
