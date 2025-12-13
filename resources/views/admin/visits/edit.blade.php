@extends('layouts.admin')

@section('title', 'Edit Kunjungan Dinas')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.visits.update', $visit) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2" required value="{{ old('title', $visit->title) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kebun *</label>
                    <select name="garden_id" class="w-full border rounded px-3 py-2" required>
                        @foreach ($gardens as $garden)
                            <option value="{{ $garden->id }}" {{ $visit->garden_id == $garden->id ? 'selected' : '' }}>
                                {{ $garden->name }} • {{ $garden->region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                    <input type="date" name="visit_date" class="w-full border rounded px-3 py-2" required value="{{ old('visit_date', $visit->visit_date->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (jam) *</label>
                    <input type="number" name="duration" min="1" max="24" class="w-full border rounded px-3 py-2" required value="{{ old('duration', $visit->duration) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Peserta *</label>
                    <input type="number" name="participants_count" min="1" class="w-full border rounded px-3 py-2" required value="{{ old('participants_count', $visit->participants_count) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" class="w-full border rounded px-3 py-2" required>
                        <option value="scheduled" {{ $visit->status == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="completed" {{ $visit->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $visit->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Peserta</label>
                <textarea name="participants_list" rows="3" class="w-full border rounded px-3 py-2">{{ old('participants_list', $visit->participants_list) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('description', $visit->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan</label>
                    <textarea name="objectives" rows="3" class="w-full border rounded px-3 py-2">{{ old('objectives', $visit->objectives) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Temuan</label>
                    <textarea name="findings" rows="3" class="w-full border rounded px-3 py-2">{{ old('findings', $visit->findings) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                    <textarea name="recommendations" rows="3" class="w-full border rounded px-3 py-2">{{ old('recommendations', $visit->recommendations) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                    <select name="rating" class="w-full border rounded px-3 py-2" required>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $visit->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('admin.visits.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Batal</a>
            </div>
        </form>
    </div>
@endsection

