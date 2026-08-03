{{-- Quota Progress Component - Reusable progress bar --}}
@props(['booked', 'total', 'label' => null, 'showLabel' => true, 'color' => 'emerald'])

@php
    $percentage = $total > 0 ? min(100, round(($booked / $total) * 100)) : 0;
    $bookedDisplay = is_numeric($booked) ? number_format($booked, 0, ',', '.') : $booked;
    $totalDisplay = is_numeric($total) ? number_format($total, 0, ',', '.') : $total;

    $colors = [
        'emerald' => 'bg-emerald-600',
        'blue' => 'bg-blue-600',
        'amber' => 'bg-amber-600',
        'red' => 'bg-red-600',
        'purple' => 'bg-purple-600',
    ];

    $bgColors = [
        'emerald' => 'bg-emerald-100',
        'blue' => 'bg-blue-100',
        'amber' => 'bg-amber-100',
        'red' => 'bg-red-100',
        'purple' => 'bg-purple-100',
    ];

    $barColor = $colors[$color] ?? $colors['emerald'];
    $bgColor = $bgColors[$color] ?? $bgColors['emerald'];

    // Determine status label
    $statusLabel = '';
    $statusColor = 'text-gray-500';
    if ($percentage >= 100) {
        $statusLabel = 'Penuh';
        $statusColor = 'text-amber-600';
    } elseif ($percentage >= 75) {
        $statusLabel = 'Hampir Penuh';
        $statusColor = 'text-amber-500';
    } elseif ($percentage >= 50) {
        $statusLabel = 'Setengah Terisi';
        $statusColor = 'text-emerald-600';
    } elseif ($percentage > 0) {
        $statusLabel = 'Tersedia';
        $statusColor = 'text-emerald-500';
    } else {
        $statusLabel = 'Kosong';
        $statusColor = 'text-gray-400';
    }
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    {{-- Label --}}
    @if ($showLabel)
        <div class="mb-1.5 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                @if ($label)
                    <span class="font-medium text-gray-700">{{ $label }}</span>
                @endif
                <span class="text-gray-500">
                    {{ $bookedDisplay }} dari {{ $totalDisplay }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <span class="{{ $statusColor }} text-xs font-semibold">
                    {{ $statusLabel }}
                </span>
                <span class="text-xs font-semibold text-gray-700">
                    {{ $percentage }}%
                </span>
            </div>
        </div>
    @endif

    {{-- Bar --}}
    <div class="{{ $bgColor }} relative h-2.5 w-full overflow-hidden rounded-full">
        <div class="{{ $barColor }} relative h-2.5 rounded-full transition-all duration-700 ease-out"
            style="width: {{ $percentage }}%">
            @if ($percentage > 0 && $percentage < 100)
                <div
                    class="absolute inset-0 animate-pulse bg-gradient-to-r from-transparent via-white/20 to-transparent">
                </div>
            @endif
        </div>
    </div>

    {{-- Mini status indicator for small spaces --}}
    @if (!$showLabel && $percentage > 0)
        <div class="mt-1 text-right text-[10px] text-gray-400">
            {{ $percentage }}% • {{ $bookedDisplay }}/{{ $totalDisplay }}
        </div>
    @endif
</div>
