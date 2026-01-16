@extends('layouts.admin')

@section('title', 'Tambah Insight')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
            <span class="material-icons-outlined text-green-600">add_circle</span>
            Tambah Insight Baru
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Buat insight baru untuk memberikan rekomendasi dan analisis
            kebun.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('admin.insights.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kebun -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="garden_id" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Kebun</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}" {{ old('garden_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->kebun_name }} • {{ $garden->region->regional_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('garden_id')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Jenis Insight -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Insight *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">category</span>
                        </span>
                        <input name="insight_type" type="text" required value="{{ old('insight_type') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: Produktivitas, Cuaca, Hama">
                    </div>
                    @error('insight_type')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Judul -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Judul *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">title</span>
                        </span>
                        <input name="title" type="text" required value="{{ old('title') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Masukkan judul insight">
                    </div>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">description</span>
                        </span>
                        <textarea name="description" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Deskripsi singkat (opsional)">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Pesan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pesan *</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">message</span>
                        </span>
                        <textarea name="message" rows="3" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Pesan detail insight">{{ old('message') }}</textarea>
                    </div>
                    @error('message')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Level Peringatan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Level Peringatan
                        *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">warning</span>
                        </span>
                        <select name="alert_level" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                            <option value="low" {{ old('alert_level') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('alert_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ old('alert_level') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    @error('alert_level')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Rekomendasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rekomendasi</label>
                    <div id="recommendation-list" class="space-y-3">
                        @if (old('recommendations'))
                            @foreach (old('recommendations') as $rec)
                                <div class="flex gap-2 relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="material-icons-outlined text-gray-400 text-sm">task_alt</span>
                                    </span>
                                    <input name="recommendations[]" type="text" value="{{ $rec }}"
                                        class="flex-1 pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                                        placeholder="Masukkan rekomendasi">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 transition-colors">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-icons-outlined text-gray-400 text-sm">task_alt</span>
                                </span>
                                <input name="recommendations[]" type="text"
                                    class="flex-1 pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                                    placeholder="Masukkan rekomendasi">
                                <button type="button" onclick="addRecommendation()"
                                    class="px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition-colors flex items-center gap-2">
                                    <span class="material-icons-outlined text-sm">add</span>
                                    Tambah
                                </button>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1">
                        <span class="material-icons-outlined text-[14px]">info</span>
                        Klik tombol tambah untuk menambah baris rekomendasi baru.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 mt-6">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan Data
                </button>
                <a href="{{ route('admin.insights.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-2.5 rounded-lg transition-all flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function addRecommendation() {
                const container = document.getElementById('recommendation-list');
                const row = document.createElement('div');
                row.className = 'flex gap-2 relative';
                row.innerHTML = `
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-gray-400 text-sm">task_alt</span>
                </span>
                <input name="recommendations[]" type="text"
                    class="flex-1 pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                    placeholder="Masukkan rekomendasi">
                <button type="button" onclick="this.parentElement.remove()"
                    class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 transition-colors">
                    <span class="material-icons-outlined">delete</span>
                </button>
            `;
                container.appendChild(row);
            }
        </script>
    @endpush
@endsection
