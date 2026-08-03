@extends('layouts.app')

@section('title', $sacrificialAnimal->name)

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-3xl px-4">
            <!-- Back Link -->
            <a href="{{ route('public.qurban.index') }}"
                class="mb-6 inline-flex items-center gap-2 text-green-600 transition hover:text-green-800">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                Kembali ke Katalog
            </a>

            <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
                <!-- Image -->
                <div class="relative overflow-hidden">
                    @if ($sacrificialAnimal->photo)
                        <img src="{{ Storage::url($sacrificialAnimal->photo) }}" alt="{{ $sacrificialAnimal->name }}"
                            class="h-auto max-h-[450px] w-full object-cover" loading="lazy">
                    @else
                        <div
                            class="flex h-80 w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                            <i class="fas fa-cow text-8xl text-green-400" aria-hidden="true"></i>
                        </div>
                    @endif
                    <span
                        class="absolute left-4 top-4 rounded-full bg-black/50 px-3 py-1 text-xs font-semibold text-white backdrop-blur-sm">
                        {{ ucfirst($sacrificialAnimal->animal_type) }}
                    </span>
                    @if ($sacrificialAnimal->status === 'fully_booked')
                        <span
                            class="absolute right-4 top-4 rounded-full bg-amber-500 px-4 py-2 text-xs font-bold text-white shadow-lg">
                            <i class="fas fa-clock mr-1" aria-hidden="true"></i> Kuota Penuh
                        </span>
                    @elseif($sacrificialAnimal->status === 'slaughtered')
                        <span
                            class="absolute right-4 top-4 rounded-full bg-gray-500 px-4 py-2 text-xs font-bold text-white shadow-lg">
                            <i class="fas fa-check-circle mr-1" aria-hidden="true"></i> Sudah Disembelih
                        </span>
                    @endif
                </div>

                <div class="p-6 md:p-8">
                    <!-- Name & Package -->
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-extrabold text-gray-800 md:text-3xl">{{ $sacrificialAnimal->name }}
                            </h1>
                            @if ($sacrificialAnimal->package_name)
                                <span
                                    class="mt-1 inline-block rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-600">
                                    <i class="fas fa-box mr-1" aria-hidden="true"></i>
                                    {{ $sacrificialAnimal->package_name }}
                                </span>
                            @endif
                        </div>
                        <p class="text-2xl font-bold text-green-700">Rp
                            {{ number_format($sacrificialAnimal->price, 0, ',', '.') }}</p>
                    </div>

                    <!-- Package Description -->
                    @if ($sacrificialAnimal->package_description)
                        <div class="mt-4 rounded-xl bg-green-50 p-4 text-sm text-green-800">
                            <i class="fas fa-info-circle mr-2" aria-hidden="true"></i>
                            {{ $sacrificialAnimal->package_description }}
                        </div>
                    @endif

                    <!-- Details Grid -->
                    <div class="mt-6 grid grid-cols-3 gap-4">
                        <div class="rounded-xl bg-gray-50 p-4 text-center">
                            <p class="text-xs text-gray-500">Bobot</p>
                            <p class="mt-1 font-bold text-gray-800">{{ $sacrificialAnimal->weight }} kg</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-4 text-center">
                            <p class="text-xs text-gray-500">Usia</p>
                            <p class="mt-1 font-bold text-gray-800">{{ $sacrificialAnimal->age }} bulan</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-4 text-center">
                            <p class="text-xs text-gray-500">Status</p>
                            @php
                                $statusLabel = match ($sacrificialAnimal->status) {
                                    'available' => 'Tersedia',
                                    'fully_booked' => 'Kuota Penuh',
                                    'slaughtered' => 'Sudah Disembelih',
                                };
                                $statusColor = match ($sacrificialAnimal->status) {
                                    'available' => 'text-green-700',
                                    'fully_booked' => 'text-amber-700',
                                    'slaughtered' => 'text-gray-500',
                                };
                            @endphp
                            <p class="{{ $statusColor }} mt-1 font-bold">{{ $statusLabel }}</p>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mt-6">
                        <x-quota-progress :booked="$sacrificialAnimal->booked_slots_count ?? 0" :total="$sacrificialAnimal->max_participants" />
                        @if ($sacrificialAnimal->max_participants > 1)
                            <p class="mt-1 text-xs text-gray-400">
                                <i class="fas fa-users mr-1" aria-hidden="true"></i>
                                Patungan maksimal {{ $sacrificialAnimal->max_participants }} orang
                            </p>
                        @endif
                    </div>

                    <!-- Action Button -->
                    @if ($sacrificialAnimal->status === 'available')
                        @auth
                            <a href="{{ route('public.qurban.orders.create', $sacrificialAnimal) }}"
                                class="mt-6 block w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 text-center font-semibold text-white shadow-lg transition hover:from-green-700 hover:to-green-800 hover:shadow-xl">
                                <i class="fas fa-shopping-cart mr-2" aria-hidden="true"></i>
                                Pesan Sekarang
                            </a>
                        @else
                            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-center text-amber-700">
                                <i class="fas fa-info-circle mr-2" aria-hidden="true"></i>
                                Silakan <a href="{{ route('login') }}"
                                    class="font-medium underline hover:text-amber-900">masuk</a> atau
                                <a href="{{ route('register') }}" class="font-medium underline hover:text-amber-900">daftar</a>
                                terlebih dahulu untuk memesan kurban.
                            </div>
                        @endauth
                    @else
                        <div class="mt-6 rounded-xl bg-gray-100 p-4 text-center text-gray-500">
                            <i class="fas fa-info-circle mr-2" aria-hidden="true"></i>
                            @if ($sacrificialAnimal->status === 'fully_booked')
                                Hewan ini sudah penuh dipesan. Silakan pilih hewan lain yang tersedia.
                            @else
                                Hewan ini sudah selesai disembelih. Terima kasih telah berpartisipasi.
                            @endif
                        </div>
                    @endif

                    <!-- Documentation -->
                    @if ($sacrificialAnimal->documentations->isNotEmpty())
                        <div class="mt-10 border-t border-gray-100 pt-6">
                            <h2 class="mb-4 text-lg font-semibold text-gray-800">Dokumentasi Penyembelihan</h2>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                @foreach ($sacrificialAnimal->documentations as $documentation)
                                    <button type="button"
                                        onclick="openLightbox('{{ Storage::url($documentation->photo) }}')"
                                        class="block overflow-hidden rounded-xl shadow-md transition-shadow hover:shadow-xl">
                                        <img src="{{ Storage::url($documentation->photo) }}" alt="Dokumentasi"
                                            class="h-32 w-full object-cover transition-transform duration-300 hover:scale-105"
                                            loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Share -->
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-xs font-medium text-gray-400">Bagikan:</span>
                            @php
                                $shareUrl = urlencode(url()->current());
                                $shareTitle = urlencode($sacrificialAnimal->name);
                            @endphp
                            <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs text-green-700 transition hover:bg-green-100">
                                <i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs text-blue-700 transition hover:bg-blue-100">
                                <i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs text-gray-700 transition hover:bg-gray-200">
                                <i class="fab fa-twitter" aria-hidden="true"></i> X
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
