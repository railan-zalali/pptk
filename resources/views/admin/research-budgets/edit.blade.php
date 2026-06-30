@extends('layouts.admin')

@section('title', 'Edit Realisasi Anggaran Penelitian')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">edit</span>
            Edit Realisasi Anggaran Penelitian {{ $year }}
        </h3>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tahun:</label>
                <select name="year" onchange="this.form.submit()" 
                    class="border dark:border-gray-700 dark:bg-gray-800 rounded-lg px-3 py-2 text-sm">
                    @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('manajemen.research-budgets.index', ['year' => $year]) }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-icons-outlined text-sm">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('manajemen.research-budgets.update') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="year" value="{{ $year }}">

        <!-- Opening Balances -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h4 class="font-semibold text-gray-800 dark:text-gray-100">Saldo Awal per Kegiatan</h4>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($activities as $activity)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $activity }}</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400">Rp</span>
                                <input type="number" name="opening_balances[{{ $activity }}]" 
                                    value="{{ old('opening_balances.' . $activity, $openingBalances[$activity] ?? 0) }}"
                                    class="w-full border dark:border-gray-700 dark:bg-gray-800 rounded-lg pl-10 pr-3 py-2 text-sm"
                                    step="100">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Monthly Data -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h4 class="font-semibold text-gray-800 dark:text-gray-100">Data Bulanan</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan data pencairan dan pengeluaran per bulan untuk setiap kegiatan</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-40">
                                Kegiatan
                            </th>
                            @for($m = 1; $m <= 12; $m++)
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="2">
                                    {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                                </th>
                            @endfor
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-600">
                            <th class="px-3 py-2 text-left text-xs text-gray-500 dark:text-gray-300"></th>
                            @for($m = 1; $m <= 12; $m++)
                                <th class="px-2 py-2 text-center text-xs text-green-600 dark:text-green-400">Pencairan</th>
                                <th class="px-2 py-2 text-center text-xs text-red-600 dark:text-red-400">Pengeluaran</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($activities as $activity)
                            <tr>
                                <td class="px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700">
                                    {{ $activity }}
                                </td>
                                @for($m = 1; $m <= 12; $m++)
                                    <td class="px-1 py-2">
                                        <input type="number" name="monthly[{{ $activity }}][{{ $m }}][income]" 
                                            value="{{ old('monthly.' . $activity . '.' . $m . '.income', $monthlyData[$activity][$m]['income'] ?? 0) }}"
                                            class="w-full border dark:border-gray-700 dark:bg-gray-800 rounded px-2 py-1 text-xs text-center"
                                            step="100"
                                            placeholder="0">
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="number" name="monthly[{{ $activity }}][{{ $m }}][expenditure]" 
                                            value="{{ old('monthly.' . $activity . '.' . $m . '.expenditure', $monthlyData[$activity][$m]['expenditure'] ?? 0) }}"
                                            class="w-full border dark:border-gray-700 dark:bg-gray-800 rounded px-2 py-1 text-xs text-center"
                                            step="100"
                                            placeholder="0">
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 sticky bottom-0 bg-gray-100 dark:bg-gray-900 py-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.research-budgets.index', ['year' => $year]) }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                Batal
            </a>
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                <span class="material-icons-outlined text-sm">save</span>
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
