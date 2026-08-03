@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <section class="bg-white py-12 md:py-20">
        <div class="container mx-auto max-w-4xl px-4">
            <!-- Back Link -->
            <a href="{{ route('public.events.index') }}"
                class="mb-6 inline-flex items-center gap-2 text-green-600 transition hover:text-green-800">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                Kembali ke Daftar Kegiatan
            </a>

            <!-- Poster/Thumbnail -->
            @if ($event->poster ?? $event->thumbnail)
                <div class="relative mb-8 overflow-hidden rounded-2xl shadow-xl">
                    <img src="{{ Storage::url($event->poster ?? $event->thumbnail) }}" alt="{{ $event->title }}"
                        class="h-auto max-h-[500px] w-full object-cover" loading="lazy">
                    @if ($event->is_featured)
                        <span
                            class="absolute right-4 top-4 rounded-full bg-amber-500 px-4 py-2 text-sm font-bold text-white shadow-lg">
                            <i class="fas fa-star mr-1" aria-hidden="true"></i> Unggulan
                        </span>
                    @endif
                </div>
            @endif

            <!-- Category -->
            @if ($event->category)
                <span
                    class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">{{ $event->category->name }}</span>
            @endif

            <!-- Title -->
            <h1 class="mt-2 text-3xl font-extrabold text-gray-800 md:text-4xl">{{ $event->title }}</h1>

            <!-- Meta Info -->
            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-green-600" aria-hidden="true"></i>
                    {{ $event->start_at->translatedFormat('d F Y, H:i') }} - {{ $event->end_at->translatedFormat('H:i') }}
                    WIB
                </span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-green-600" aria-hidden="true"></i>
                    {{ $event->location }}
                </span>
                <span class="flex items-center gap-2">
                    <i class="far fa-eye text-green-600" aria-hidden="true"></i>
                    {{ number_format($event->views_count) }}x dilihat
                </span>
            </div>

            <!-- Time Status Badge -->
            @php
                $timeColor = match ($event->time_status) {
                    'upcoming' => 'bg-blue-100 text-blue-700',
                    'ongoing' => 'bg-green-100 text-green-700',
                    'finished' => 'bg-gray-100 text-gray-500',
                };
            @endphp
            <span
                class="{{ $timeColor }} mt-4 inline-block rounded-full px-4 py-2 text-sm font-medium">{{ $event->time_status_label }}</span>

            <!-- Speaker -->
            @if ($event->speaker_name)
                <div class="mt-4 flex items-center gap-3 rounded-xl bg-green-50 p-4 text-green-800">
                    <i class="fas fa-microphone text-green-600" aria-hidden="true"></i>
                    <div>
                        <span class="text-sm font-medium">Pemateri:</span>
                        <span class="text-sm">{{ $event->speaker_name }}</span>
                    </div>
                </div>
            @endif

            <!-- Registration Status -->
            <div class="mt-4 flex items-center gap-3">
                <span class="text-sm font-medium text-gray-600">Status Pendaftaran:</span>
                @php
                    $regColor = match ($event->registration_status) {
                        'open' => 'bg-green-100 text-green-700',
                        'closed' => 'bg-gray-100 text-gray-500',
                        'full' => 'bg-amber-100 text-amber-700',
                    };
                    $regLabel = match ($event->registration_status) {
                        'open' => 'Dibuka',
                        'closed' => 'Ditutup',
                        'full' => 'Kuota Penuh',
                    };
                @endphp
                <span
                    class="{{ $regColor }} inline-block rounded-full px-3 py-1 text-xs font-medium">{{ $regLabel }}</span>
            </div>

            <!-- Description -->
            <div class="prose prose-green mt-8 max-w-none">
                {!! nl2br(e($event->description)) !!}
            </div>

            <!-- Share Buttons -->
            @include('public.events._share-buttons')

            <!-- Map -->
            @include('public.events._map')

            <!-- Documentation Gallery -->
            @if ($event->documentations->isNotEmpty())
                <div class="mt-12">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">Dokumentasi</h2>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($event->documentations as $documentation)
                            <button type="button" onclick="openLightbox('{{ Storage::url($documentation->photo) }}')"
                                class="block overflow-hidden rounded-xl shadow-md transition-shadow hover:shadow-xl">
                                <img src="{{ Storage::url($documentation->photo) }}" alt="Dokumentasi"
                                    class="h-40 w-full object-cover transition-transform duration-300 hover:scale-105"
                                    loading="lazy">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Events -->
            @if ($relatedEvents->isNotEmpty())
                <div class="mt-12 border-t border-gray-200 pt-8">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">Kegiatan Terkait</h2>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        @foreach ($relatedEvents as $related)
                            <a href="{{ route('public.events.show', $related->slug) }}"
                                class="group overflow-hidden rounded-xl bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                @if ($related->thumbnail)
                                    <img src="{{ Storage::url($related->thumbnail) }}" alt="{{ $related->title }}"
                                        class="h-36 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy">
                                @else
                                    <div
                                        class="flex h-36 w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                        <i class="fas fa-calendar-alt text-2xl text-green-400" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4
                                        class="line-clamp-2 text-sm font-semibold text-gray-800 transition group-hover:text-green-700">
                                        {{ $related->title }}</h4>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $related->start_at->translatedFormat('d M Y, H:i') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Lightbox -->
    <div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4"
        onclick="closeLightbox()">
        <img id="lightbox-image" src="" class="max-h-[85vh] max-w-full rounded-lg shadow-2xl">
        <button type="button" class="absolute right-5 top-5 text-3xl text-white transition hover:text-gray-300"
            onclick="closeLightbox()">&times;</button>
    </div>

    <script>
        function openLightbox(imageUrl) {
            document.getElementById('lightbox-image').src = imageUrl;
            document.getElementById('lightbox').classList.remove('hidden');
            document.getElementById('lightbox').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.getElementById('lightbox').classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endsection
