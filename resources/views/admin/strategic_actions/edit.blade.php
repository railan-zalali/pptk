@extends('layouts.admin')

@section('title', 'Edit Strategic Action')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.strategic-actions.index') }}" class="text-green-600 hover:text-green-800 flex items-center mb-4">
                <span class="material-icons mr-1">arrow_back</span> Kembali ke Daftar
            </a>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Form Edit Strategic Action</h2>
        </div>

        <form action="{{ route('admin.strategic-actions.update', $strategicAction) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Block Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="block_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Blok</label>
                    <select name="block_id" id="block_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Blok</option>
                        @foreach ($blocks as $block)
                            <option value="{{ $block->id }}" {{ old('block_id', $strategicAction->block_id) == $block->id ? 'selected' : '' }}>
                                {{ $block->name }} ({{ $block->afdeling->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('block_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Period -->
                <div class="col-span-2 md:col-span-1">
                    <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periode</label>
                    <input type="date" name="period" id="period" value="{{ old('period', $strategicAction->period->format('Y-m-d')) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('period')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Type -->
                <div class="col-span-2">
                    <label for="action_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Aksi</label>
                    <select name="action_type" id="action_type"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Tipe Aksi</option>
                        <option value="fertilizer_root" {{ old('action_type', $strategicAction->action_type) == 'fertilizer_root' ? 'selected' : '' }}>Pemupukan Akar</option>
                        <option value="fertilizer_leaf" {{ old('action_type', $strategicAction->action_type) == 'fertilizer_leaf' ? 'selected' : '' }}>Pemupukan Daun</option>
                        <option value="cultivator" {{ old('action_type', $strategicAction->action_type) == 'cultivator' ? 'selected' : '' }}>Kultivator</option>
                        <option value="weed_control" {{ old('action_type', $strategicAction->action_type) == 'weed_control' ? 'selected' : '' }}>Pengendalian Gulma</option>
                    </select>
                    @error('action_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Volume -->
                <div>
                    <label for="target_volume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Volume</label>
                    <input type="number" step="0.01" name="target_volume" id="target_volume" value="{{ old('target_volume', $strategicAction->target_volume) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('target_volume')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Realization Volume -->
                <div>
                    <label for="realization_volume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Realisasi Volume</label>
                    <input type="number" step="0.01" name="realization_volume" id="realization_volume" value="{{ old('realization_volume', $strategicAction->realization_volume) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('realization_volume')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nitrogen Content -->
                <div>
                    <label for="nitrogen_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kandungan Nitrogen (%) (Opsional)</label>
                    <input type="number" step="0.01" name="nitrogen_content" id="nitrogen_content" value="{{ old('nitrogen_content', $strategicAction->nitrogen_content) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    @error('nitrogen_content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">{{ old('notes', $strategicAction->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Update Action
                </button>
            </div>
        </form>
    </div>
@endsection
