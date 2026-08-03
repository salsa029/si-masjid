@extends('layouts.app')

@section('title', 'Pengurus DKM')

@section('content')
    <section class="bg-white py-12 md:py-20">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="mb-12 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Pengurus</span>
                <h2 class="mt-3 text-3xl font-extrabold text-green-800 md:text-4xl">Struktur <span
                        class="text-green-600">Pengurus DKM</span></h2>
                <div class="mx-auto mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-green-600 to-green-400"></div>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600">Dewan Kemakmuran Masjid (DKM) yang berdedikasi untuk kemajuan
                    masjid</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse($committees as $committee)
                    <div
                        class="group overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <div class="relative aspect-square overflow-hidden">
                            @if ($committee->photo)
                                <img src="{{ Storage::url($committee->photo) }}" alt="{{ $committee->name }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                    <i class="fas fa-user text-6xl text-green-400" aria-hidden="true"></i>
                                </div>
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                <h3 class="text-lg font-bold text-white">{{ $committee->name }}</h3>
                                <p class="text-sm text-green-200">{{ $committee->position }}</p>
                            </div>
                        </div>
                        <div class="p-5">
                            @if ($committee->bio)
                                <p class="line-clamp-3 text-sm text-gray-600">{{ $committee->bio }}</p>
                            @endif
                            @if ($committee->term_start || $committee->term_end)
                                <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                    <span>
                                        {{ $committee->term_start?->format('d/m/Y') ?? '-' }}
                                        s/d
                                        {{ $committee->term_end?->format('d/m/Y') ?? '-' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="mb-4 text-6xl text-gray-300">
                            <i class="fas fa-users" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-500">Belum Ada Data Pengurus</h3>
                        <p class="mt-2 text-gray-400">Data pengurus akan segera diisi.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
