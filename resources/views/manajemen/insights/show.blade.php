@extends('layouts.admin')

@section('title', 'Detail Insight')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('manajemen.insights.index') }}"
                class="inline-flex items-center text-sm font-semibold text-green-600 dark:text-green-400 hover:underline gap-1">
                <span class="material-icons-outlined text-sm">arrow_back</span>
                Kembali ke Daftar Insight
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Header Card -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kebun Model</span>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mt-0.5">
                        <span class="material-icons-outlined text-green-600">agriculture</span>
                        {{ $insight->garden->kebun_name ?? '-' }}
                        <span class="text-sm font-normal text-gray-500">({{ $insight->garden->region->regional_name ?? '-' }})</span>
                    </h3>
                </div>
                <div>
                    @php
                        $alertColor = $insight->alert_level == 'high' ? 'red' : ($insight->alert_level == 'medium' ? 'yellow' : 'green');
                        $alertLabel = $insight->alert_level == 'high' ? 'Peringatan Tinggi' : ($insight->alert_level == 'medium' ? 'Perlu Perhatian' : 'Optimal');
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-{{ $alertColor }}-100 text-{{ $alertColor }}-800 dark:bg-{{ $alertColor }}-900/30 dark:text-{{ $alertColor }}-400 capitalize">
                        <span class="w-2 h-2 rounded-full bg-{{ $alertColor }}-500 mr-2"></span>
                        {{ $alertLabel }}
                    </span>
                </div>
            </div>

            <!-- Body Content -->
            <div class="p-6">
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Wawasan / Analisis</h4>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">{{ $insight->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $insight->description }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Rekomendasi Tindakan Agronomis</h4>
                    @php
                        $recommendations = is_array($insight->recommendations)
                            ? $insight->recommendations
                            : json_decode($insight->recommendations, true) ?? [];
                    @endphp
                    @if(!empty($recommendations))
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($recommendations as $rec)
                                <div class="flex items-start bg-green-50/50 dark:bg-green-900/10 p-3 rounded-lg border border-green-100/50 dark:border-green-800/20">
                                    <span class="material-icons-outlined text-green-600 text-sm mr-3 text-[20px] mt-0.5">task_alt</span>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $rec }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500">Tidak ada rekomendasi khusus yang dikeluarkan.</p>
                    @endif
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between text-xs text-gray-400">
                    <span>Tipe Evaluasi: <strong class="text-gray-600 dark:text-gray-350 capitalize">{{ str_replace('_', ' ', $insight->insight_type) }}</strong></span>
                    <span>Dianalisis Pada: <strong>{{ optional($insight->generated_at ?? $insight->created_at)->format('d F Y H:i') }}</strong></span>
                </div>
            </div>
        </div>
    </div>
@endsection
