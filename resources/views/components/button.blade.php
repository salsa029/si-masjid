@props([
    'variant' => 'primary', // primary | outline | gold
    'href' => null,
    'type' => 'button',
])

@php
    $variantClass = match ($variant) {
        'outline' => 'btn-outline',
        'gold' => 'btn-gold',
        default => 'btn-primary',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "btn $variantClass"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "btn $variantClass"]) }}>
        {{ $slot }}
    </button>
@endif
