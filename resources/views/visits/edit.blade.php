@extends('layouts.pptk')

@section('title', 'Edit Kunjungan Dinas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800 mb-2">Edit Kunjungan Dinas</h1>
            <p class="text-gray-600">Perbarui informasi kunjungan dinas</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('visits.update', $visit) }}" method="POST">
                @csrf
                @method('PUT')
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                        <div class="font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan
                            validasi</div>
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Kunjungan *</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            value="{{ old('title', $visit->title) }}">
                    </div>
                    <div>
                        <label for="garden_id" class="block text-sm font-medium text-gray-700 mb-2">Kebun Model *</label>
                        <select name="garden_id" id="garden_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            @foreach ($gardens as $garden)
                                <option value="{{ $garden->id }}"
                                    {{ old('garden_id', $visit->garden_id) == $garden->id ? 'selected' : '' }}>
                                    {{ $garden->name }} - {{ $garden->region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kunjungan
                            *</label>
                        <input type="date" name="visit_date" id="visit_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            value="{{ old('visit_date', $visit->visit_date->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durasi (jam) *</label>
                        <input type="number" name="duration" id="duration" required min="1" max="24"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            value="{{ old('duration', $visit->duration) }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="participants_count" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Peserta
                            *</label>
                        <input type="number" name="participants_count" id="participants_count" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            value="{{ old('participants_count', $visit->participants_count) }}">
                    </div>
                    <div>
                        <label for="participants_list" class="block text-sm font-medium text-gray-700 mb-2">Daftar
                            Peserta</label>
                        <textarea name="participants_list" id="participants_list" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">{{ old('participants_list', $visit->participants_list) }}</textarea>
                    </div>
                </div>

                <div class="space-y-6 mb-6">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kunjungan
                            *</label>
                        <textarea name="description" id="description" required rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">{{ old('description', $visit->description) }}</textarea>
                    </div>
                    <div>
                        <label for="objectives" class="block text-sm font-medium text-gray-700 mb-2">Tujuan
                            Kunjungan</label>
                        <textarea name="objectives" id="objectives" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">{{ old('objectives', $visit->objectives) }}</textarea>
                    </div>
                    <div>
                        <label for="findings" class="block text-sm font-medium text-gray-700 mb-2">Temuan/Hasil</label>
                        <textarea name="findings" id="findings" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">{{ old('findings', $visit->findings) }}</textarea>
                    </div>
                    <div>
                        <label for="recommendations"
                            class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                        <textarea name="recommendations" id="recommendations" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">{{ old('recommendations', $visit->recommendations) }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating Kunjungan
                            *</label>
                        <select name="rating" id="rating" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}"
                                    {{ old('rating', $visit->rating) == $i ? 'selected' : '' }}>
                                    {{ $i }} Bintang
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Kunjungan
                            *</label>
                        <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            <option value="scheduled"
                                {{ old('status', $visit->status) == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="completed"
                                {{ old('status', $visit->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled"
                                {{ old('status', $visit->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('visits.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
