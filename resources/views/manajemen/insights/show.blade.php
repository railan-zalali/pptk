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

        @php
            $level = $insight->alert_level;

            $bannerBg = match($level) {
                'high'   => 'bg-red-600',
                'medium' => 'bg-amber-500',
                default  => 'bg-green-600',
            };
            $bannerBorder = match($level) {
                'high'   => 'border-red-700',
                'medium' => 'border-amber-600',
                default  => 'border-green-700',
            };
            $alertLabel = match($level) {
                'high'   => 'Peringatan Kritis',
                'medium' => 'Perlu Perhatian',
                default  => 'Kondisi Optimal',
            };
            $alertIcon = match($level) {
                'high'   => 'warning',
                'medium' => 'info',
                default  => 'check_circle',
            };
            $bodyBorder = match($level) {
                'high'   => 'border-red-200 dark:border-red-800',
                'medium' => 'border-amber-200 dark:border-amber-800',
                default  => 'border-green-200 dark:border-green-800',
            };
            $recBg = match($level) {
                'high'   => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
                'medium' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
                default  => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
            };
            $recIconColor = match($level) {
                'high'   => 'text-red-500',
                'medium' => 'text-amber-500',
                default  => 'text-green-500',
            };
        @endphp

        <div class="rounded-xl shadow-sm border {{ $bodyBorder }} overflow-hidden bg-white dark:bg-gray-800">

            {{-- Alert Banner --}}
            <div class="{{ $bannerBg }} px-6 py-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    {{-- Animated icon for high --}}
                    <div class="relative flex-shrink-0">
                        @if($level === 'high')
                            <span class="absolute inline-flex w-10 h-10 rounded-full bg-red-400 opacity-60 animate-ping"></span>
                        @endif
                        <span class="material-icons text-white text-3xl relative z-10">{{ $alertIcon }}</span>
                    </div>
                    <div>
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Status Alert</p>
                        <p class="text-white text-xl font-black leading-tight">{{ $alertLabel }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white/70 text-xs">Tipe Evaluasi</p>
                    <p class="text-white font-semibold text-sm capitalize">{{ str_replace('_', ' ', $insight->insight_type) }}</p>
                </div>
            </div>

            {{-- Garden Info --}}
            <div class="px-6 py-4 border-b {{ $bodyBorder }} flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-green-600 dark:text-green-400">agriculture</span>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wider">Kebun Model</p>
                        <p class="font-bold text-gray-800 dark:text-gray-100">
                            {{ $insight->garden->kebun_name ?? '-' }}
                            <span class="font-normal text-sm text-gray-500 dark:text-gray-400">
                                ({{ $insight->garden->region->regional_name ?? '-' }})
                            </span>
                        </p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Dianalisis: <strong class="text-gray-600 dark:text-gray-300">{{ optional($insight->generated_at ?? $insight->created_at)->format('d F Y H:i') }}</strong>
                </p>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-6">

                {{-- Wawasan --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Wawasan / Analisis</h4>
                    <div class="p-5 rounded-xl border {{ $bodyBorder }} bg-gray-50 dark:bg-gray-700/30">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">{{ $insight->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $insight->description }}</p>
                    </div>
                </div>

                {{-- Rekomendasi --}}
                @php
                    $recommendations = is_array($insight->recommendations)
                        ? $insight->recommendations
                        : json_decode($insight->recommendations, true) ?? [];
                @endphp
                @if(!empty($recommendations))
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                            Rekomendasi Tindakan Agronomis
                        </h4>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($recommendations as $i => $rec)
                                <div class="flex items-start gap-3 p-3.5 rounded-xl border {{ $recBg }}">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $recBg }} {{ $recIconColor }} border-current">
                                        {{ $i + 1 }}
                                    </span>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-snug">{{ $rec }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection