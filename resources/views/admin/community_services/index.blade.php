@extends('layouts.admin')

@section('title', 'Kelola Pengabdian Masyarakat')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="material-icons-outlined text-green-600">volunteer_activism</span>
                    Pengabdian Masyarakat
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">Daftar kegiatan pengabdian masyarakat.</p>
            </div>
            <a href="{{ route('admin.community-services.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-icons-outlined text-sm">add</span>
                Tambah Kegiatan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wider">
                        <th class="py-3 px-4">Nama Kegiatan</th>
                        <th class="py-3 px-4">Tim</th>
                        <th class="py-3 px-4">Tahun</th>
                        <th class="py-3 px-4 text-right">Total Anggaran</th>
                        <th class="py-3 px-4 text-right">Sisa Dana</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-3 px-4 font-medium">{{ $service->activity_name }}</td>
                            <td class="py-3 px-4">{{ $service->team_name }}</td>
                            <td class="py-3 px-4">{{ $service->year }}</td>
                            <td class="py-3 px-4 text-right">Rp {{ number_format($service->total_budget, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($service->remaining_budget, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.community-services.edit', $service) }}"
                                        class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.community-services.destroy', $service) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                            title="Hapus">
                                            <span class="material-icons-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-icons-outlined text-4xl mb-2 text-gray-300">volunteer_activism</span>
                                    <p>Belum ada data pengabdian masyarakat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
@endsection
