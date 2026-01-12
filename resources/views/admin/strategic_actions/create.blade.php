@extends('layouts.admin')

@section('title', 'Tambah Aksi Strategis')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Tambah Aksi Strategis</h3>
            <p class="text-sm text-gray-600">Catat rencana aksi strategis kebun.</p>
        </div>

        <form action="{{ route('admin.strategic-actions.store') }}" method="POST">
            @csrf

            <!-- Common Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kebun</label>
                    <select name="kebun_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Kebun --</option>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ old('kebun_id') == $garden->id ? 'selected' : '' }}>
                                {{ $garden->kebun_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('kebun_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aksi</label>
                    <select name="action_type" id="action_type"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Jenis Aksi --</option>
                        <option value="Pemupukan Akar" {{ old('action_type') == 'Pemupukan Akar' ? 'selected' : '' }}>
                            Pemupukan Akar</option>
                        <option value="Pemupukan Daun" {{ old('action_type') == 'Pemupukan Daun' ? 'selected' : '' }}>
                            Pemupukan Daun</option>
                        <option value="Penyiangan Gulma" {{ old('action_type') == 'Penyiangan Gulma' ? 'selected' : '' }}>
                            Penyiangan Gulma</option>
                        <option value="Kultivator / Pengolahan Tanah"
                            {{ old('action_type') == 'Kultivator / Pengolahan Tanah' ? 'selected' : '' }}>Kultivator /
                            Pengolahan Tanah</option>
                        <option value="Pemetikan" {{ old('action_type') == 'Pemetikan' ? 'selected' : '' }}>Pemetikan
                        </option>
                        <option value="Mesin Petik" {{ old('action_type') == 'Mesin Petik' ? 'selected' : '' }}>Mesin Petik
                        </option>
                        <option value="Pengendalian OPT" {{ old('action_type') == 'Pengendalian OPT' ? 'selected' : '' }}>
                            Pengendalian OPT</option>
                    </select>
                    @error('action_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dosis N (kg/ha)</label>
                        <input type="number" step="0.01" name="dosis_n_kg_ha" value="{{ old('dosis_n_kg_ha') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">% N thd Protas</label>
                        <input type="number" step="0.01" name="n_protas_percent" value="{{ old('n_protas_percent') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Frekuensi Aplikasi</label>
                        <input type="number" name="application_frequency" value="{{ old('application_frequency') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pupuk</label>
                        <input type="text" name="fertilizer_type" value="{{ old('fertilizer_type') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Teknis</label>
                        <textarea name="technical_note" rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('technical_note') }}</textarea>
                    </div>
                </div>

                <!-- Pemupukan Daun -->
                <div data-type="Pemupukan Daun" class="contents">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Cakupan (%)</label>
                        <input type="number" step="0.01" name="coverage_target_percent"
                            value="{{ old('coverage_target_percent') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interval Aplikasi</label>
                        <input type="text" name="application_interval" value="{{ old('application_interval') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Contoh: 2 minggu sekali">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="note" rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Penyiangan Gulma -->
                <div data-type="Penyiangan Gulma" class="contents">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Cakupan (%)</label>
                        <input type="number" step="0.01" name="coverage_target_percent"
                            value="{{ old('coverage_target_percent') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rotasi per Tahun</label>
                        <input type="number" name="rotation_per_year" value="{{ old('rotation_per_year') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode</label>
                        <input type="text" name="method" value="{{ old('method') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Manual / Kimia / Mekanis">
                    </div>
                </div>

                <!-- Kultivator -->
                <div data-type="Kultivator / Pengolahan Tanah" class="contents">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Cakupan (%)</label>
                        <input type="number" step="0.01" name="coverage_target_percent"
                            value="{{ old('coverage_target_percent') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Area Fokus</label>
                        <input type="text" name="focus_area" value="{{ old('focus_area') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode</label>
                        <input type="text" name="method" value="{{ old('method') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>

                <!-- Pemetikan -->
                <div data-type="Pemetikan" class="contents">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sistem Pemetikan</label>
                        <input type="text" name="picking_system" value="{{ old('picking_system') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konsistensi Cushion</label>
                        <input type="text" name="cushion_consistency" value="{{ old('cushion_consistency') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Risiko Kandas?</label>
                        <select name="kandas_risk"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="0" {{ old('kandas_risk') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('kandas_risk') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="note" rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Mesin Petik -->
                <div data-type="Mesin Petik" class="contents">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Mesin (Unit)</label>
                        <input type="number" name="total_machine" value="{{ old('total_machine') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Umur Mesin (Thn)</label>
                        <input type="number" step="0.1" name="avg_machine_age"
                            value="{{ old('avg_machine_age') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Peremajaan</label>
                        <input type="text" name="renewal_status" value="{{ old('renewal_status') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="note" rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Pengendalian OPT -->
                <div data-type="Pengendalian OPT" class="contents">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status OPT</label>
                        <input type="text" name="opt_status" value="{{ old('opt_status') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Normalisasi Pucuk?</label>
                        <select name="tp_normalization"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="0" {{ old('tp_normalization') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('tp_normalization') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Perlakuan</label>
                        <textarea name="treatment_note" rows="2"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('treatment_note') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.strategic-actions.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
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
