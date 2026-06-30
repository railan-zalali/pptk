@extends('layouts.admin')

@section('title', 'Tambah Kunjungan Dinas')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Kunjungan Dinas
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi formulir berikut untuk mencatat kunjungan dinas
                baru.</p>
        </div>

        <form action="{{ route('manajemen.visits.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">title</span>
                        </span>
                        <input type="text" name="title"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required value="{{ old('title') }}" placeholder="Contoh: Kunjungan Rutin Q1">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kebun *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="garden_id"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="">Pilih Kebun</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}" {{ old('garden_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->kebun_name }} • {{ $garden->region->regional_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input type="date" name="visit_date"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required value="{{ old('visit_date', date('Y-m-d')) }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durasi (jam) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">schedule</span>
                        </span>
                        <input type="number" name="duration" min="1" max="24"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required value="{{ old('duration', 2) }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Peserta *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">group</span>
                        </span>
                        <input type="number" name="participants_count" min="1"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required value="{{ old('participants_count', 5) }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">info</span>
                        </span>
                        <select name="status"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none"
                            required>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Daftar Peserta</label>
                <div class="relative">
                    <span class="absolute top-3 left-3 pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">list</span>
                    </span>
                    <textarea name="participants_list" rows="3"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                        placeholder="Masukkan nama peserta, pisahkan dengan koma">{{ old('participants_list') }}</textarea>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi *</label>
                <div class="relative">
                    <span class="absolute top-3 left-3 pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">description</span>
                    </span>
                    <textarea name="description" rows="4"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                        required placeholder="Deskripsi lengkap kegiatan kunjungan">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tujuan</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">flag</span>
                        </span>
                        <textarea name="objectives" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('objectives') }}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Temuan</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">search</span>
                        </span>
                        <textarea name="findings" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('findings') }}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rekomendasi</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">lightbulb</span>
                        </span>
                        <textarea name="recommendations" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('recommendations') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">star</span>
                        </span>
                        <select name="rating"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            required>
                            <option value="">Pilih Rating</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">photo_camera</span>
                        </span>
                        <input type="file" name="photos[]" multiple accept="image/*"
                            class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-300">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, GIF. Maks: 2MB per file.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan
                </button>
                <a href="{{ route('manajemen.visits.index') }}"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
