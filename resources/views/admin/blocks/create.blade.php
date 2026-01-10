@extends('layouts.admin')

@section('title', 'Tambah Blok Baru')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.blocks.index') }}" class="text-green-600 hover:text-green-800 flex items-center mb-4">
                <span class="material-icons mr-1">arrow_back</span> Kembali ke Daftar
            </a>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Form Tambah Blok</h2>
        </div>

        <form action="{{ route('admin.blocks.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Afdeling Selection -->
                <div class="col-span-2">
                    <label for="afdeling_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Afdeling</label>
                    <select name="afdeling_id" id="afdeling_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Afdeling</option>
                        @foreach ($afdelings as $afdeling)
                            <option value="{{ $afdeling->id }}" {{ old('afdeling_id') == $afdeling->id ? 'selected' : '' }}>
                                {{ $afdeling->name }} ({{ $afdeling->garden->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('afdeling_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Blok</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Blok</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Plant Type -->
                <div>
                    <label for="plant_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Tanaman</label>
                    <select name="plant_type" id="plant_type"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Tipe</option>
                        <option value="seedling" {{ old('plant_type') == 'seedling' ? 'selected' : '' }}>Seedling</option>
                        <option value="klon_gmb" {{ old('plant_type') == 'klon_gmb' ? 'selected' : '' }}>Klon GMB</option>
                        <option value="klon_tri" {{ old('plant_type') == 'klon_tri' ? 'selected' : '' }}>Klon TRI</option>
                    </select>
                    @error('plant_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Planting Year -->
                <div>
                    <label for="planting_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Tanam</label>
                    <input type="number" name="planting_year" id="planting_year" value="{{ old('planting_year') }}"
                        min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                    @error('planting_year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Initial Class -->
                <div>
                    <label for="initial_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas Awal</label>
                    <select name="initial_class" id="initial_class"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Kelas</option>
                        <option value="A" {{ old('initial_class') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('initial_class') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('initial_class') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('initial_class') == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ old('initial_class') == 'E' ? 'selected' : '' }}>E</option>
                    </select>
                    @error('initial_class')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Topography -->
                <div>
                    <label for="topography" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topografi</label>
                    <select name="topography" id="topography"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                        required>
                        <option value="">Pilih Topografi</option>
                        <option value="datar" {{ old('topography') == 'datar' ? 'selected' : '' }}>Datar</option>
                        <option value="gelombang" {{ old('topography') == 'gelombang' ? 'selected' : '' }}>Gelombang</option>
                        <option value="curam" {{ old('topography') == 'curam' ? 'selected' : '' }}>Curam</option>
                    </select>
                    @error('topography')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Simpan Blok
                </button>
            </div>
        </form>
    </div>
@endsection
