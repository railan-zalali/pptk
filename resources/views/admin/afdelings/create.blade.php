@extends('layouts.admin')

@section('title', 'Tambah Afdeling Baru')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.afdelings.index') }}" class="text-green-600 hover:text-green-800 flex items-center mb-4">
                <span class="material-icons mr-1">arrow_back</span> Kembali ke Daftar
            </a>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Form Tambah Afdeling</h2>
        </div>

        <form action="{{ route('admin.afdelings.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Garden Selection -->
                <div class="col-span-2">
                    <label for="garden_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kebun</label>
                    <select name="garden_id" id="garden_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Kebun</option>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ old('garden_id') == $garden->id ? 'selected' : '' }}>
                                {{ $garden->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('garden_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="col-span-2 md:col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Afdeling</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Manager Name -->
                <div class="col-span-2 md:col-span-1">
                    <label for="manager_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Manager</label>
                    <input type="text" name="manager_name" id="manager_name" value="{{ old('manager_name') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    @error('manager_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Area -->
                <div>
                    <label for="total_area_ha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Area (Ha)</label>
                    <input type="number" step="0.01" name="total_area_ha" id="total_area_ha" value="{{ old('total_area_ha') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('total_area_ha')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TM Area -->
                <div>
                    <label for="tm_area_ha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Luas TM (Ha)</label>
                    <input type="number" step="0.01" name="tm_area_ha" id="tm_area_ha" value="{{ old('tm_area_ha') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('tm_area_ha')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Simpan Afdeling
                </button>
            </div>
        </form>
    </div>
@endsection
