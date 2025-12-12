@extends('layouts.admin')

@section('title', 'Tambah Insight')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.insights.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kebun *</label>
                <select name="garden_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Kebun</option>
                    @foreach ($gardens as $garden)
                        <option value="{{ $garden->id }}">{{ $garden->name }} • {{ $garden->region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Insight *</label>
                <input name="insight_type" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('insight_type') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                <input name="title" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('title') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pesan *</label>
                <textarea name="message" rows="3" required class="w-full border rounded px-3 py-2">{{ old('message') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Level Peringatan *</label>
                <select name="alert_level" required class="w-full border rounded px-3 py-2">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                <div id="recommendation-list" class="space-y-2">
                    <div class="flex gap-2">
                        <input name="recommendations[]" type="text" class="flex-1 border rounded px-3 py-2" placeholder="Masukkan rekomendasi">
                        <button type="button" class="px-3 py-2 rounded bg-gray-100 hover:bg-gray-200" onclick="addRecommendation()">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.insights.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function addRecommendation() {
        const container = document.getElementById('recommendation-list');
        const row = document.createElement('div');
        row.className = 'flex gap-2';
        row.innerHTML = `
            <input name="recommendations[]" type="text" class="flex-1 border rounded px-3 py-2" placeholder="Masukkan rekomendasi">
            <button type="button" class="px-3 py-2 rounded bg-red-100 hover:bg-red-200" onclick="this.parentElement.remove()">Hapus</button>
        `;
        container.appendChild(row);
    }
</script>
@endpush
@endsection

