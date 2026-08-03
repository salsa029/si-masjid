@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'center'])

<div class="{{ $align === 'center' ? 'text-center mx-auto' : '' }} mb-10 max-w-2xl" data-reveal>
    @if ($eyebrow)
        <span
            class="text-masjid-700 bg-masjid-50 mb-3 inline-block rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider">
            {{ $eyebrow }}
        </span>
    @endif
    <h2 class="text-2xl font-bold text-slate-800 md:text-3xl">{{ $title }}</h2>
    @if ($subtitle)
        <p class="mt-2 text-slate-500">{{ $subtitle }}</p>
    @endif
</div>
