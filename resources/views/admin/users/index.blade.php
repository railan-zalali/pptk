@extends('layouts.admin')

@section('title', 'Manajemen Users')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Users</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola data pengguna dan hak akses sistem.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
            <span class="material-icons-outlined text-sm">add</span>
            Tambah User
        </a>
    </div>

    <form method="GET"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">admin_panel_settings</span>
                    </span>
                    <select name="role" onchange="this.form.submit()"
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none transition-colors">
                        <option value="">Semua Role</option>
                        <option value="admin_pptk" {{ request('role') == 'admin_pptk' ? 'selected' : '' }}>Admin PPTK</option>
                        <option value="manajemen" {{ request('role') == 'manajemen' ? 'selected' : '' }}>Manajemen</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Cari User</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 text-sm">search</span>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full bg-gray-800 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-500 text-white px-4 py-2.5 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-icons-outlined text-sm">filter_list</span>
                    Filter
                </button>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div
            class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center">
            <span class="material-icons-outlined mr-2">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center">
            <span class="material-icons-outlined mr-2">error</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th
                            class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Email</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Role</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Terdaftar</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400 font-bold text-xs">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = [
                                        'admin_pptk' =>
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200',
                                        'manajemen' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                                    ];
                                    $roleLabels = [
                                        'admin_pptk' => 'Admin PPTK',
                                        'manajemen'  => 'Manajemen',
                                    ];
                                    $roleClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                    $roleLabel = $roleLabels[$user->role] ?? ucfirst($user->role);
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-1.5 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg transition-colors"
                                                title="Hapus">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span
                                        class="material-icons-outlined text-4xl mb-2 text-gray-300 dark:text-gray-600">person_off</span>
                                    <p>Belum ada data user.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
