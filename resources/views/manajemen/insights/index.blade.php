@extends('layouts.admin')

@section('title', 'Insight')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Header --}}
        <div class="p-6 border-b dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Insight</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Insight dan analisis kebun model berdasarkan Rule Engine.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                <span class="material-icons text-sm mr-1">visibility</span> View Only
            </span>
        </div>

        {{-- Filter Tabs --}}
        <div class="px-6 pt-4 pb-0 flex flex-wrap gap-2 border-b border-gray-100 dark:border-gray-700">
            @php
                $tabs = [
                    null     => ['label' => 'Semua',    'icon' => 'list',         'color' => 'gray'],
                    'high'   => ['label' => 'Kritis',   'icon' => 'warning',      'color' => 'red'],
                    'medium' => ['label' => 'Perhatian','icon' => 'info',          'color' => 'amber'],
                    'low'    => ['label' => 'Optimal',  'icon' => 'check_circle', 'color' => 'green'],
                ];
            @endphp
            @foreach($tabs as $level => $tab)
                @php
                    $isActive = $alertFilter === $level;
                    $count    = $level ? ($counts[$level] ?? 0) : $counts->sum();
                    $href     = $level ? route('manajemen.insights.index', ['alert' => $level]) : route('manajemen.insights.index');
                    $activeClass = match($tab['color']) {
                        'red'   => 'border-red-500 text-red-600 dark:text-red-400',
                        'amber' => 'border-amber-500 text-amber-600 dark:text-amber-400',
                        'green' => 'border-green-500 text-green-600 dark:text-green-400',
                        default => 'border-blue-500 text-blue-600 dark:text-blue-400',
                    };
                    $badgeClass = match($tab['color']) {
                        'red'   => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                        'green' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                    };
                @endphp
                <a href="{{ $href }}"
                   class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium border-b-2 transition-colors
                          {{ $isActive ? $activeClass . ' border-current' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                    <span class="material-icons text-base">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                    <span class="px-1.5 py-0.5 rounded-full text-xs font-bold {{ $isActive ? $badgeClass : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $count }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Insight List --}}
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($insights as $insight)
                @php
                    $level = $insight->alert_level;
                    $rowBg = match($level) {
                        'high'   => 'bg-red-50/60 dark:bg-red-900/10 hover:bg-red-50 dark:hover:bg-red-900/20',
                        'medium' => 'bg-amber-50/60 dark:bg-amber-900/10 hover:bg-amber-50 dark:hover:bg-amber-900/20',
                        default  => 'bg-green-50/30 dark:bg-green-900/5 hover:bg-green-50/60 dark:hover:bg-green-900/10',
                    };
                    $badgeBg = match($level) {
                        'high'   => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                        'medium' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                        default  => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                    };
                    $dotColor = match($level) {
                        'high'   => 'bg-red-500',
                        'medium' => 'bg-amber-500',
                        default  => 'bg-green-500',
                    };
                    $icon = match($level) {
                        'high'   => 'warning',
                        'medium' => 'info',
                        default  => 'check_circle',
                    };
                    $iconColor = match($level) {
                        'high'   => 'text-red-500',
                        'medium' => 'text-amber-500',
                        default  => 'text-green-500',
                    };
                    $label = match($level) {
                        'high'   => 'Kritis',
                        'medium' => 'Perhatian',
                        default  => 'Optimal',
                    };
                @endphp
                <div class="px-6 py-4 transition {{ $rowBg }}">
                    <div class="flex items-start justify-between gap-4">
                        {{-- Ikon Level --}}
                        <div class="flex-shrink-0 mt-0.5">
                            <span class="material-icons text-xl {{ $iconColor }} {{ $level === 'high' ? 'animate-pulse' : '' }}">{{ $icon }}</span>
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-100 text-sm">
                                    {{ $insight->title ?? 'Insight' }}
                                </h4>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold {{ $badgeBg }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} {{ $level === 'high' ? 'animate-ping' : '' }}"></span>
                                    {{ $label }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 truncate">
                                {{ $insight->garden->kebun_name ?? '-' }}
                                @if($insight->garden?->region)
                                    &middot; {{ $insight->garden->region->regional_name }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                {{ $insight->description ?? $insight->content ?? '-' }}
                            </p>
                        </div>

                        {{-- Meta + Link --}}
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xs text-gray-400 whitespace-nowrap mb-2">
                                {{ optional($insight->generated_at ?? $insight->created_at)->format('d M Y') }}
                            </p>
                            <a href="{{ route('manajemen.insights.show', $insight) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg
                                      {{ $level === 'high' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 hover:bg-red-200' :
                                        ($level === 'medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 hover:bg-amber-200' :
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 hover:bg-green-200') }} transition">
                                Detail <span class="material-icons text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <span class="material-icons text-4xl text-gray-300 dark:text-gray-600">insights</span>
                    <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">
                        {{ $alertFilter ? 'Tidak ada insight dengan level ini.' : 'Belum ada data insight.' }}
                    </p>
                </div>
            @endforelse
        </div>

        @if ($insights->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $insights->links() }}
            </div>
        @endif
    </div>
@endsection