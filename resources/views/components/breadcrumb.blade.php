@props(['items'])
<nav class="mb-4 text-sm text-gray-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
    @foreach ($items as $label => $url)
        <span class="mx-1">/</span>
        @if ($url && !$loop->last)
            <a href="{{ $url }}" class="hover:text-emerald-600">{{ $label }}</a>
        @else
            <span class="font-medium text-gray-700">{{ $label }}</span>
        @endif
    @endforeach
</nav>
