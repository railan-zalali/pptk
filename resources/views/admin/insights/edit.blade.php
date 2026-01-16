@extends('layouts.admin')

@section('title', 'Edit Insight')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 transition-all duration-300">
        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">edit</span>
                Form Edit Insight
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui informasi insight dan rekomendasi.</p>
        </div>

        <form action="{{ route('admin.insights.update', $insight) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Garden -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun <span
                            class="text-red-500">*</span></label>
                    <select name="garden_id" required
                        class="w-full border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ $insight->garden_id == $garden->id ? 'selected' : '' }}>
                                {{ $garden->kebun_name }} • {{ $garden->region->regional_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Insight Type -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Insight <span
                            class="text-red-500">*</span></label>
                    <input name="insight_type" type="text" required
                        class="w-full border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                        placeholder="Contoh: Produktivitas" value="{{ old('insight_type', $insight->insight_type) }}">
                </div>

                <!-- Title -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Judul <span
                            class="text-red-500">*</span></label>
                    <input name="title" type="text" required
                        class="w-full border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                        placeholder="Judul singkat insight" value="{{ old('title', $insight->title) }}">
                </div>

                <!-- Description -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                        placeholder="Penjelasan detail insight">{{ old('description', $insight->description) }}</textarea>
                </div>

                <!-- Message -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pesan <span
                            class="text-red-500">*</span></label>
                    <textarea name="message" rows="3" required
                        class="w-full border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                        placeholder="Pesan utama yang ingin disampaikan">{{ old('message', $insight->message) }}</textarea>
                </div>

                <!-- Alert Level -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Level Peringatan <span
                            class="text-red-500">*</span></label>
                    <select name="alert_level" required
                        class="w-full border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow appearance-none">
                        @foreach (['low', 'medium', 'high'] as $level)
                            <option value="{{ $level }}" {{ $insight->alert_level === $level ? 'selected' : '' }}>
                                {{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Recommendations -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rekomendasi</label>
                    <div id="recommendation-list" class="space-y-3">
                        @php($recs = $insight->recommendations ?? [])
                        @if (is_array($recs) && count($recs))
                            @foreach ($recs as $rec)
                                <div class="flex gap-2 group">
                                    <input name="recommendations[]" type="text"
                                        class="flex-1 border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                                        value="{{ $rec }}">
                                    <button type="button"
                                        class="px-3 py-2.5 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                                        onclick="this.parentElement.remove()" title="Hapus Rekomendasi">
                                        <span class="material-icons-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2 group">
                                <input name="recommendations[]" type="text"
                                    class="flex-1 border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                                    placeholder="Masukkan rekomendasi tindakan">
                                <button type="button"
                                    class="px-3 py-2.5 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                                    onclick="this.parentElement.remove()" title="Hapus Rekomendasi">
                                    <span class="material-icons-outlined text-lg">delete</span>
                                </button>
                            </div>
                        @endif

                        <!-- Button container attached to the list -->
                        <div class="pt-2">
                            <button type="button"
                                class="px-4 py-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-green-500 hover:text-green-600 dark:hover:text-green-400 transition-all w-full flex items-center justify-center gap-2"
                                onclick="addRecommendation()">
                                <span class="material-icons-outlined text-lg">add_circle_outline</span>
                                Tambah Rekomendasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('admin.insights.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-lg">arrow_back</span>
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all shadow-lg hover:shadow-green-500/30 flex items-center gap-2">
                    <span class="material-icons-outlined text-lg">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function addRecommendation() {
                const container = document.getElementById('recommendation-list');
                const buttonContainer = container.lastElementChild; // The button container

                const row = document.createElement('div');
                row.className = 'flex gap-2 group';
                row.innerHTML = `
            <input name="recommendations[]" type="text"
                class="flex-1 border dark:border-gray-600 rounded-lg px-3 py-2.5 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow"
                placeholder="Masukkan rekomendasi tindakan">
            <button type="button"
                class="px-3 py-2.5 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                onclick="this.parentElement.remove()"
                title="Hapus Rekomendasi">
                <span class="material-icons-outlined text-lg">delete</span>
            </button>
        `;

                // Insert before the button container
                container.insertBefore(row, buttonContainer);
            }
        </script>
    @endpush
@endsection
