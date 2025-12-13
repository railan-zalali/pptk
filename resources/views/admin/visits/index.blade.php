@extends('layouts.admin')

@section('title', 'Kelola Kunjungan Dinas')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Kunjungan Dinas</h3>
        <a href="{{ route('admin.visits.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Kunjungan</a>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kebun</label>
                <select name="garden_id" class="w-full border rounded px-3 py-2">
                    <option value="">Semua Kebun</option>
                    @foreach ($gardens as $garden)
                        <option value="{{ $garden->id }}" {{ request('garden_id') == $garden->id ? 'selected' : '' }}>
                            {{ $garden->name }} • {{ $garden->region->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="">Semua</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded w-full">Filter</button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kebun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($visits as $visit)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $visit->visit_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $visit->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $visit->garden->name }} • {{ $visit->garden->region->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $visit->participants_count }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $visit->rating }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($visit->status) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.visits.edit', $visit) }}" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                            <form action="{{ route('admin.visits.destroy', $visit) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kunjungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $visits->links() }}
    </div>
@endsection

