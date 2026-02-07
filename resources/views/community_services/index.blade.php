@extends('layouts.pptk')

@section('title', 'Pengabdian Masyarakat')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-green-800 text-white">
        <div class="relative container mx-auto px-4 py-16">
            <h1 class="text-4xl font-bold mb-4">Pengabdian Masyarakat</h1>
            <p class="text-xl max-w-2xl text-green-100">
                Informasi terkait kegiatan pengabdian masyarakat PPTK Gambung
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Kegiatan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Tim
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tahun
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Anggaran
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sisa Dana
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $service->activity_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $service->team_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $service->year }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-mono text-gray-900">
                                    Rp {{ number_format($service->total_budget, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-mono text-green-600 font-bold">
                                    Rp {{ number_format($service->remaining_budget, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Belum ada data kegiatan pengabdian masyarakat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
