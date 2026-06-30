@extends('layouts.admin')

@section('title', 'Realisasi Anggaran Penelitian')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <span class="material-icons-outlined text-green-600">account_balance</span>
            Realisasi Anggaran Penelitian {{ $year }}
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
            <a href="{{ route('manajemen.research-budgets.edit', ['year' => $year]) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-icons-outlined text-sm">edit</span>
                Edit Data
            </a>
        </div>
    </div>

    <!-- Grand Totals -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Saldo Awal</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        Rp {{ number_format($grandTotals['total_opening'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <span class="material-icons-outlined text-blue-600 dark:text-blue-400">savings</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Total Pencairan</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                        Rp {{ number_format($grandTotals['total_income'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <span class="material-icons-outlined text-green-600 dark:text-green-400">trending_up</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                        Rp {{ number_format($grandTotals['total_expenditure'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                    <span class="material-icons-outlined text-red-600 dark:text-red-400">trending_down</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Sisa Anggaran</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                        Rp {{ number_format($grandTotals['total_remaining'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                    <span class="material-icons-outlined text-purple-600 dark:text-purple-400">account_balance_wallet</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Annual Summary Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-gray-800 dark:text-gray-100">Ringkasan per Kegiatan</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kegiatan
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Saldo Awal
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Pencairan
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Pengeluaran
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Sisa
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($annualSummary as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $item['activity_name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 text-right">
                                Rp {{ number_format($item['opening_balance'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right">
                                Rp {{ number_format($item['total_income'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right">
                                Rp {{ number_format($item['total_expenditure'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $item['remaining_balance'] >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }} text-right">
                                Rp {{ number_format($item['remaining_balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Data -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h4 class="font-semibold text-gray-800 dark:text-gray-100">Detail Bulanan</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-48">
                            Kegiatan
                        </th>
                        @for($m = 1; $m <= 12; $m++)
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($monthlyData as $activity => $months)
                        <!-- Income Row -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-2 text-xs text-green-600 dark:text-green-400 font-medium">
                                {{ $activity }} (Pencairan)
                            </td>
                            @for($m = 1; $m <= 12; $m++)
                                <td class="px-2 py-2 text-center text-xs text-gray-600 dark:text-gray-400">
                                    {{ $months[$m]['income'] > 0 ? number_format($months[$m]['income'], 0, ',', '.') : '-' }}
                                </td>
                            @endfor
                        </tr>
                        <!-- Expenditure Row -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b-2 border-gray-100 dark:border-gray-600">
                            <td class="px-4 py-2 text-xs text-red-600 dark:text-red-400 font-medium">
                                {{ $activity }} (Pengeluaran)
                            </td>
                            @for($m = 1; $m <= 12; $m++)
                                <td class="px-2 py-2 text-center text-xs text-gray-600 dark:text-gray-400">
                                    {{ $months[$m]['expenditure'] > 0 ? number_format($months[$m]['expenditure'], 0, ',', '.') : '-' }}
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
