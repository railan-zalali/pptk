@extends('layouts.admin')

@section('title', 'Kelola Insight')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Insight</h3>
        <a href="{{ route('admin.insights.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Insight</a>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Level Peringatan</label>
                <select name="alert_level" class="w-full border rounded px-3 py-2">
                    <option value="">Semua</option>
                    <option value="low" {{ request('alert_level') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('alert_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('alert_level') === 'high' ? 'selected' : '' }}>High</option>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kebun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($insights as $insight)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $insight->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $insight->garden->name }} • {{ $insight->garden->region->name }}
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $insight->title }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs
                            @if($insight->alert_level==='high') bg-red-100 text-red-800
                            @elseif($insight->alert_level==='medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($insight->alert_level) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.insights.edit', $insight) }}"
                           class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                        <form action="{{ route('admin.insights.destroy', $insight) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus insight ini?')">
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
        {{ $insights->links() }}
    </div>
@endsection

