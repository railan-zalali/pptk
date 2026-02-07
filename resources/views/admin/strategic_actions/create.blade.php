@extends('layouts.admin')

@section('title', 'Tambah Aksi Strategis')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_task</span>
                Tambah Aksi Strategis
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">Catat rencana aksi strategis kebun.</p>
        </div>

        <form action="{{ route('admin.strategic-actions.store') }}" method="POST">
            @csrf

            <!-- Common Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="kebun_id"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">-- Pilih Kebun --</option>
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}" {{ old('kebun_id') == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->kebun_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('kebun_id')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">event</span>
                        </span>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Tahun">
                    </div>
                    @error('year')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Aksi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">category</span>
                        </span>
                        <select name="action_type" id="action_type"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">-- Pilih Jenis Aksi --</option>
                            <option value="Pemupukan Akar" {{ old('action_type') == 'Pemupukan Akar' ? 'selected' : '' }}>
                                Pemupukan Akar</option>
                            <option value="Pemupukan Daun" {{ old('action_type') == 'Pemupukan Daun' ? 'selected' : '' }}>
                                Pemupukan Daun</option>
                            <option value="Penyiangan Gulma"
                                {{ old('action_type') == 'Penyiangan Gulma' ? 'selected' : '' }}>
                                Penyiangan Gulma</option>
                            <option value="Kultivator / Pengolahan Tanah"
                                {{ old('action_type') == 'Kultivator / Pengolahan Tanah' ? 'selected' : '' }}>Kultivator /
                                Pengolahan Tanah</option>
                            <option value="Pemetikan" {{ old('action_type') == 'Pemetikan' ? 'selected' : '' }}>Pemetikan
                            </option>
                            <option value="Mesin Petik" {{ old('action_type') == 'Mesin Petik' ? 'selected' : '' }}>Mesin
                                Petik
                            </option>
                            <option value="Pengendalian OPT"
                                {{ old('action_type') == 'Pengendalian OPT' ? 'selected' : '' }}>
                                Pengendalian OPT</option>
                        </select>
                    </div>
                    @error('action_type')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">flag</span>
                        </span>
                        <select name="status"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                            <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Realisasi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input type="date" name="realization_date" value="{{ old('realization_date') }}"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Specific Fields Container -->
            <div id="specific_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fields will be shown here via JS -->
            </div>

            <!-- Hidden Templates for Fields -->
            <div id="templates" class="hidden">
                <!-- Pemupukan Akar -->
                <div data-type="Pemupukan Akar" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dosis N
                            (kg/ha)</label>
                        <input type="number" step="0.01" name="dosis_n_kg_ha" value="{{ old('dosis_n_kg_ha') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Realisasi Dosis
                            (kg/ha)</label>
                        <input type="number" step="0.01" name="realized_dosis_n_kg_ha" value="{{ old('realized_dosis_n_kg_ha') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">% N thd
                            Protas</label>
                        <input type="number" step="0.01" name="n_protas_percent" value="{{ old('n_protas_percent') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Frekuensi
                            Aplikasi</label>
                        <input type="number" name="application_frequency" value="{{ old('application_frequency') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Pupuk</label>
                        <input type="text" name="fertilizer_type" value="{{ old('fertilizer_type') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan
                            Teknis</label>
                        <textarea name="technical_note" rows="2"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('technical_note') }}</textarea>
                    </div>
                </div>

                <!-- Pemupukan Daun -->
                <div data-type="Pemupukan Daun" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Target Cakupan
                            (%)</label>
                        <input type="number" step="0.01" name="coverage_target_percent"
                            value="{{ old('coverage_target_percent') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Realisasi Cakupan
                            (%)</label>
                        <input type="number" step="0.01" name="realization_percent"
                            value="{{ old('realization_percent') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interval
                            Aplikasi</label>
                        <input type="text" name="application_interval" value="{{ old('application_interval') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Contoh: 2 minggu sekali">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                        <textarea name="note" rows="2"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Penyiangan Gulma -->
                <div data-type="Penyiangan Gulma" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Target Cakupan
                            (%)</label>
                        <input type="number" step="0.01" name="coverage_target_percent"
                            value="{{ old('coverage_target_percent') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rotasi per
                            Tahun</label>
                        <input type="number" name="rotation_per_year" value="{{ old('rotation_per_year') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Metode</label>
                        <input type="text" name="method" value="{{ old('method') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Manual / Kimia / Mekanis">
                    </div>
                </div>

                <!-- Kultivator -->
                <div data-type="Kultivator / Pengolahan Tanah" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Target Cakupan
                            (%)</label>
                        <input type="number" step="0.01" name="coverage_target_percent"
                            value="{{ old('coverage_target_percent') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Area Fokus</label>
                        <input type="text" name="focus_area" value="{{ old('focus_area') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Metode</label>
                        <input type="text" name="method" value="{{ old('method') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                </div>

                <!-- Pemetikan -->
                <div data-type="Pemetikan" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sistem
                            Pemetikan</label>
                        <input type="text" name="picking_system" value="{{ old('picking_system') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konsistensi
                            Cushion</label>
                        <input type="text" name="cushion_consistency" value="{{ old('cushion_consistency') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Risiko
                            Kandas?</label>
                        <select name="kandas_risk"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                            <option value="0" {{ old('kandas_risk') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('kandas_risk') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                        <textarea name="note" rows="2"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Mesin Petik -->
                <div data-type="Mesin Petik" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Mesin
                            (Unit)</label>
                        <input type="number" name="total_machine" value="{{ old('total_machine') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rata-rata Umur
                            Mesin (Thn)</label>
                        <input type="number" step="0.1" name="avg_machine_age"
                            value="{{ old('avg_machine_age') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status
                            Peremajaan</label>
                        <input type="text" name="renewal_status" value="{{ old('renewal_status') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                        <textarea name="note" rows="2"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Pengendalian OPT -->
                <div data-type="Pengendalian OPT" class="contents">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status OPT</label>
                        <input type="text" name="opt_status" value="{{ old('opt_status') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Normalisasi
                            Pucuk?</label>
                        <select name="tp_normalization"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                            <option value="0" {{ old('tp_normalization') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('tp_normalization') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan
                            Perlakuan</label>
                        <textarea name="treatment_note" rows="2"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">{{ old('treatment_note') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.strategic-actions.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan Aksi
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('action_type');
            const specificContainer = document.getElementById('specific_fields');
            const templates = document.getElementById('templates');

            function updateFields() {
                const selectedType = typeSelect.value;
                specificContainer.innerHTML = ''; // Clear current fields

                if (selectedType) {
                    const template = templates.querySelector(`[data-type="${selectedType}"]`);
                    if (template) {
                        // Clone content to specificContainer
                        // Note: We use innerHTML to copy, but for inputs to work we might need to be careful with IDs if we had any.
                        // Since we use names, it should be fine.
                        // However, template.innerHTML returns string.
                        specificContainer.innerHTML = template.innerHTML;
                    }
                }
            }

            typeSelect.addEventListener('change', updateFields);

            // Initial run if old value exists
            if (typeSelect.value) {
                updateFields();
            }
        });
    </script>
@endsection
