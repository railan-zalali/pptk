@extends('layouts.admin')

@section('title', 'Kelola Program')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Program</h3>
        <a href="{{ route('admin.programs.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tambah Program</a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Program</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($programs as $program)
                    <tr>
                        <td class="px-6 py-4">{{ $program->program_name }}</td>
                        <td class="px-6 py-4">{{ $program->year }}</td>
                        <td class="px-6 py-4">{{ $program->program_type }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $program->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $program->status ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.programs.edit', $program) }}"
                                class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                            <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus program ini?')">
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
        {{ $programs->links() }}
    </div>
@endsection
