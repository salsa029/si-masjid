@props(['field', 'label'])
@php
    $currentSort = request('sort', 'created_at');
    $currentDirection = request('direction', 'desc');
    $newDirection = $currentSort === $field && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp
<a href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $newDirection]) }}"
    class="flex items-center gap-1 hover:text-emerald-600">
    {{ $label }}
    @if ($currentSort === $field)
        <span>{{ $currentDirection === 'asc' ? '↑' : '↓' }}</span>
    @endif
</a>
