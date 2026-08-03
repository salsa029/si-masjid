<div class="mb-8">
    <h2 class="mb-3 text-lg font-semibold text-gray-800">
        <i class="fas fa-map-marked-alt mr-2 text-green-600" aria-hidden="true"></i>
        Lokasi Kegiatan
    </h2>

    <div class="overflow-hidden rounded-2xl shadow-md">
        @if ($event->latitude && $event->longitude)
            <iframe src="https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}&output=embed"
                width="100%" height="300" style="border:0;" allowfullscreen loading="lazy" class="rounded-2xl"
                title="Peta lokasi kegiatan"></iframe>
        @else
            <iframe src="https://www.google.com/maps?q={{ urlencode($event->location) }}&output=embed" width="100%"
                height="300" style="border:0;" allowfullscreen loading="lazy" class="rounded-2xl"
                title="Peta lokasi kegiatan"></iframe>
        @endif
    </div>

    <a href="https://www.google.com/maps/search/?api=1&query={{ $event->latitude && $event->longitude ? $event->latitude . ',' . $event->longitude : urlencode($event->location) }}"
        target="_blank" rel="noopener noreferrer"
        class="mt-2 inline-block text-sm text-green-600 transition hover:text-green-800">
        <i class="fas fa-external-link-alt mr-1" aria-hidden="true"></i>
        Buka di Google Maps
    </a>
</div>
