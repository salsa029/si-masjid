@extends('layouts.app')

@section('title', 'Kegiatan Masjid')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <span
                        class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Kegiatan</span>
                    <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Kegiatan <span
                            class="text-green-600">Masjid</span></h2>
                </div>
                <a href="{{ route('public.events.calendar') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700 hover:shadow-lg">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    Lihat Kalender
                </a>
            </div>

            <!-- Search & Filter -->
            <form method="GET" class="mb-8 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-4 shadow-sm">
                <div class="min-w-[200px] flex-1">
                    <label class="mb-1 block text-xs font-medium text-gray-600">Cari Kegiatan</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Ketik judul kegiatan..."
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Kategori</label>
                    <select name="category"
                        class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Status Waktu</label>
                    <select name="time_status"
                        class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Waktu</option>
                        <option value="upcoming" @selected(request('time_status') === 'upcoming')>Akan Datang</option>
                        <option value="ongoing" @selected(request('time_status') === 'ongoing')>Sedang Berlangsung</option>
                        <option value="finished" @selected(request('time_status') === 'finished')>Selesai</option>
                    </select>
                </div>
                <button type="submit"
                    class="rounded-xl bg-green-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                    <i class="fas fa-search mr-2" aria-hidden="true"></i> Cari
                </button>
                @if (request()->hasAny(['search', 'category', 'time_status']))
                    <a href="{{ route('public.events.index') }}"
                        class="text-sm text-gray-400 transition hover:text-gray-600">Reset</a>
                @endif
            </form>

            <!-- Featured Events -->
            @if (isset($featuredEvents) && $featuredEvents->isNotEmpty())
                <div class="mb-10">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-gray-500">
                        <i class="fas fa-star text-amber-500" aria-hidden="true"></i>
                        Event Unggulan
                    </h3>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        @foreach ($featuredEvents as $event)
                            <a href="{{ route('public.events.show', $event->slug) }}"
                                class="group relative h-56 overflow-hidden rounded-2xl shadow-md transition-shadow hover:shadow-xl">
                                @if ($event->thumbnail)
                                    <img src="{{ Storage::url($event->thumbnail) }}" alt="{{ $event->title }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-green-700 to-green-900"></div>
                                @endif
                                <div
                                    class="absolute inset-0 flex items-end bg-gradient-to-t from-black/70 via-black/20 to-transparent p-5">
                                    <div>
                                        <span
                                            class="mb-2 inline-block rounded-full bg-amber-500/20 px-2.5 py-1 text-xs font-semibold text-amber-300">
                                            <i class="fas fa-star mr-1" aria-hidden="true"></i> Unggulan
                                        </span>
                                        <h4 class="text-lg font-bold text-white">{{ $event->title }}</h4>
                                        <p class="text-sm text-green-200">
                                            {{ $event->start_at->translatedFormat('d F Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Events Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($events as $event)
                    <a href="{{ route('public.events.show', $event->slug) }}"
                        class="group overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <div class="relative h-52 overflow-hidden">
                            @if ($event->thumbnail)
                                <img src="{{ Storage::url($event->thumbnail) }}" alt="{{ $event->title }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                    <i class="fas fa-calendar-alt text-5xl text-green-400" aria-hidden="true"></i>
                                </div>
                            @endif
                            @if ($event->is_featured)
                                <span
                                    class="absolute right-3 top-3 rounded-full bg-amber-500 px-3 py-1 text-xs font-bold text-white shadow-lg">
                                    <i class="fas fa-star mr-1" aria-hidden="true"></i> Unggulan
                                </span>
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                @if ($event->category)
                                    <span
                                        class="inline-block rounded-full bg-green-600/50 px-2 py-0.5 text-xs font-semibold text-green-200">{{ $event->category->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="line-clamp-2 text-lg font-bold text-gray-800 transition group-hover:text-green-700">
                                {{ $event->title }}</h3>
                            @if ($event->excerpt)
                                <p class="mt-1 line-clamp-2 text-sm text-gray-500">{{ $event->excerpt }}</p>
                            @endif
                            <div class="mt-3 flex items-center gap-3 text-sm text-gray-500">
                                <span>{{ $event->start_at->translatedFormat('d M Y, H:i') }}</span>
                                <span>•</span>
                                <span>{{ $event->location }}</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                @php
                                    $timeColor = match ($event->time_status) {
                                        'upcoming' => 'bg-blue-100 text-blue-700',
                                        'ongoing' => 'bg-green-100 text-green-700',
                                        'finished' => 'bg-gray-100 text-gray-500',
                                    };
                                @endphp
                                <span
                                    class="{{ $timeColor }} inline-block rounded-full px-3 py-1 text-xs font-medium">{{ $event->time_status_label }}</span>
                                <span class="text-xs text-gray-400">
                                    <i class="far fa-eye mr-1" aria-hidden="true"></i>
                                    {{ number_format($event->views_count) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="mb-4 text-6xl text-gray-300">
                            <i class="fas fa-calendar-times" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-500">Tidak Ada Kegiatan</h3>
                        <p class="mt-2 text-gray-400">Belum ada kegiatan yang dipublikasikan saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        </div>
    </section>
@endsection
