@extends('layouts.admin')

@section('title', 'Tambah Target Kinerja')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <span class="material-icons-outlined text-green-600">add_circle</span>
                Tambah Target Kinerja
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">Tetapkan target produktivitas tahunan untuk kebun.
            </p>
        </div>

        <form action="{{ route('admin.performance-targets.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Garden Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kebun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">agriculture</span>
                        </span>
                        <select name="kebun_id" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="">Pilih Kebun</option>
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

                <!-- Year -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                        </span>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" required min="2000"
                            max="2099"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Tahun">
                    </div>
                    @error('year')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Target Protas Min -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Target Protas Min
                        (Kg/Ha)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">trending_down</span>
                        </span>
                        <input type="number" step="0.01" name="target_protas_min" value="{{ old('target_protas_min') }}"
                            required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="0.00">
                    </div>
                    @error('target_protas_min')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Target Protas Max -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Target Protas Max
                        (Kg/Ha)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">trending_up</span>
                        </span>
                        <input type="number" step="0.01" name="target_protas_max" value="{{ old('target_protas_max') }}"
                            required
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="0.00">
                    </div>
                    @error('target_protas_max')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Note -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <span class="material-icons-outlined text-gray-400 text-sm">description</span>
                        </span>
                        <textarea name="note" rows="3"
                            class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors"
                            placeholder="Tambahkan catatan...">{{ old('note') }}</textarea>
                    </div>
                    @error('note')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <span class="material-icons-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.performance-targets.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan Target
                </button>
            </div>
        </form>
    </div>
@endsection
