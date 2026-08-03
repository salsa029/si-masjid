@props(['padding' => 'p-5'])

<div {{ $attributes->merge(['class' => "card-surface $padding"]) }}>
    {{ $slot }}
</div>
