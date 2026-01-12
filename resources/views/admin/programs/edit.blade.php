@extends('layouts.admin')

@section('title', 'Edit Program')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Edit Program</h3>
            <p class="text-sm text-gray-600">Perbarui informasi program strategis.</p>
        </div>

        <form action="{{ route('admin.programs.update', $program) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Program</label>
                    <input type="text" name="program_name" value="{{ old('program_name', $program->program_name) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('program_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="year" value="{{ old('year', $program->year) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Program</label>
                    <select name="program_type"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="Model"
                            {{ old('program_type', $program->program_type) == 'Model' ? 'selected' : '' }}>Model</option>
                        <option value="Pengembangan"
                            {{ old('program_type', $program->program_type) == 'Pengembangan' ? 'selected' : '' }}>
                            Pengembangan</option>
                    </select>
                    @error('program_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="1" {{ old('status', $program->status) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('status', $program->status) ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.programs.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    Update Program
                </button>
            </div>
        </form>
    </div>
@endsection
