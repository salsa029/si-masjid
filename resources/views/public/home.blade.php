@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @php
        // $mosqueProfile dan $prayerData sudah disiapkan oleh HomeController (termasuk city_code masjid)
        $mosque = $mosqueProfile;
    @endphp

    <!-- ===== HERO SECTION ===== -->
    <section id="home" class="relative flex min-h-screen items-center overflow-hidden">
        <div class="hero-overlay absolute inset-0"></div>
        <div class="hero-pattern absolute inset-0"></div>

        <div class="absolute right-10 top-20 hidden opacity-10 lg:block">
            <i class="fas fa-mosque text-[200px] text-white"></i>
        </div>

        <div class="container relative z-10 mx-auto px-4 py-20">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <!-- Hero Text -->
                <div class="w-full text-center lg:w-1/2 lg:text-left">
                    <div
                        class="mb-6 inline-block rounded-full bg-green-500/20 px-4 py-1.5 text-sm font-medium text-green-200 backdrop-blur-sm">
                        <i class="fas fa-mosque mr-2" aria-hidden="true"></i>
                        Masjid Terpercaya
                    </div>
                    <h1 class="hero-title mb-6 text-4xl font-extrabold leading-tight text-white md:text-5xl lg:text-6xl">
                        {{ $mosque->name ?? 'MASJID AN-NUR' }}
                        <br />
                        <span class="text-2xl md:text-3xl lg:text-4xl">Rumah Qur'ani Untuk Semua</span>
                    </h1>
                    <p class="mx-auto mb-8 max-w-lg text-base text-green-100/90 md:text-lg lg:mx-0">
                        {{ $mosque->vision ?? 'Pusat ibadah dan peradaban umat Islam.' }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 lg:justify-start">
                        <a href="#about"
                            class="flex items-center gap-2 rounded-full bg-white px-8 py-3.5 font-bold text-green-800 shadow-lg transition hover:scale-105 hover:shadow-xl">
                            <i class="fas fa-info-circle" aria-hidden="true"></i> Tentang Kami
                        </a>
                        <a href="{{ route('public.infaq.index') }}"
                            class="flex items-center gap-2 rounded-full border-2 border-white bg-transparent px-8 py-3.5 font-bold text-white transition hover:bg-white hover:text-green-800">
                            <i class="fas fa-hand-holding-heart" aria-hidden="true"></i> Donasi
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="mt-12 flex flex-wrap justify-center gap-8 lg:justify-start">
                        <a href="{{ route('public.committees.index') }}" class="transition hover:opacity-80">
                            <p class="text-2xl font-bold text-white">{{ $activeCommitteeCount }}+</p>
                            <p class="text-sm text-green-200">Pengurus Aktif</p>
                        </a>
                        <a href="{{ route('public.events.index') }}" class="transition hover:opacity-80">
                            <p class="text-2xl font-bold text-white">{{ $publishedEventCount }}+</p>
                            <p class="text-sm text-green-200">Kegiatan</p>
                        </a>
                        <a href="{{ route('public.articles.index') }}" class="transition hover:opacity-80">
                            <p class="text-2xl font-bold text-white">{{ $publishedArticleCount }}+</p>
                            <p class="text-sm text-green-200">Artikel</p>
                        </a>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="w-full lg:w-1/2">
                    <div class="relative">
                        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-green-400/20 blur-3xl"></div>
                        <div class="absolute -bottom-4 -left-4 h-32 w-32 rounded-full bg-yellow-400/20 blur-3xl"></div>

                        @if (count($heroImages) > 1)
                            {{-- Slideshow: berganti otomatis antar foto galeri masjid --}}
                            <div x-data="{
                                images: @js($heroImages),
                                current: 0,
                                timer: null,
                                init() {
                                    this.timer = setInterval(() => {
                                        this.current = (this.current + 1) % this.images.length;
                                    }, 4000);
                                }
                            }"
                                class="relative h-72 w-full overflow-hidden rounded-2xl shadow-2xl ring-4 ring-white/10 sm:h-96 lg:h-[420px]">
                                <template x-for="(image, index) in images" :key="index">
                                    <img :src="image" alt="{{ $mosqueProfile->name ?? 'Masjid An-Nur' }}"
                                        x-show="current === index" x-transition:enter="transition ease-in-out duration-1000"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in-out duration-1000"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="absolute inset-0 h-full w-full object-cover">
                                </template>

                                <div class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 gap-2">
                                    <template x-for="(image, index) in images" :key="index">
                                        <button type="button" @click="current = index"
                                            :class="current === index ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                                            class="h-2 rounded-full transition-all duration-300" aria-label="Lihat foto"></button>
                                    </template>
                                </div>
                            </div>
                        @elseif (count($heroImages) === 1)
                            <img src="{{ $heroImages[0] }}" alt="{{ $mosqueProfile->name ?? 'Masjid An-Nur' }}"
                                class="relative h-auto w-full rounded-2xl shadow-2xl ring-4 ring-white/10" loading="lazy">
                        @else
                            <img src="https://images.unsplash.com/photo-1584551246679-258d2be6d3a8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80&fm=webp"
                                alt="Masjid" class="relative h-auto w-full rounded-2xl shadow-2xl ring-4 ring-white/10"
                                loading="lazy">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRAYER TIMES ===== -->
    <section id="prayer-times-section" class="bg-white py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-green-700 via-green-800 to-green-900 shadow-2xl">
                <div class="p-6 text-white md:p-8">
                    <div class="mb-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                        <div>
                            <h2 class="flex items-center text-2xl font-bold">
                                <i class="fas fa-clock mr-3 text-green-300" aria-hidden="true"></i>
                                <span id="prayer-date">Waktu Sholat Hari Ini</span>
                            </h2>
                            <p class="mt-2 flex items-center text-green-200/80">
                                <i class="fas fa-map-marker-alt mr-2" aria-hidden="true"></i>
                                <span>{{ $mosque->address ?? 'Jakarta Selatan' }}</span>
                            </p>
                        </div>
                        <button id="refresh-prayer"
                            class="flex items-center gap-2 rounded-full bg-green-600/50 px-4 py-2.5 text-sm text-white transition hover:bg-green-600">
                            <i class="fas fa-sync-alt" aria-hidden="true"></i>
                            <span class="hidden sm:inline">Perbarui</span>
                        </button>
                    </div>

                    <!-- Prayer Cards -->
                    <div class="grid grid-cols-3 gap-3 sm:grid-cols-5" id="prayer-times-container">
                        <!-- Akan diisi JavaScript -->
                    </div>

                    <!-- Info Bar -->
                    <div
                        class="mt-4 flex flex-col items-center justify-between gap-2 rounded-xl bg-green-800/30 p-4 text-center text-sm text-green-200 md:flex-row md:text-left">
                        <div class="flex items-center gap-2">
                            <span class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-green-400"></span>
                            <span id="current-prayer-label">🕌 Memuat jadwal sholat...</span>
                        </div>
                        <div id="time-remaining" class="text-xs text-green-300/80">
                            <i class="fas fa-hourglass-half mr-1" aria-hidden="true"></i>
                            <span id="next-prayer-info">Memuat...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== IMPACT / HIGHLIGHT LAPORAN KEUANGAN ===== -->
    <section id="impact" class="relative overflow-hidden bg-gradient-to-br from-green-800 via-green-700 to-green-600 py-14 md:py-20">
        <div class="absolute inset-0 opacity-10">
            <i class="fas fa-mosque absolute -right-10 -top-10 text-[220px] text-white" aria-hidden="true"></i>
        </div>
        <div class="container relative z-10 mx-auto px-4">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <span
                    class="mb-3 inline-block rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-green-100 backdrop-blur-sm">
                    <i class="fas fa-chart-line mr-1" aria-hidden="true"></i> Transparansi Amanah
                </span>
                <h2 class="text-2xl font-extrabold text-white md:text-4xl">Jejak Kebaikan Masjid An-Nur</h2>
                <p class="mt-3 text-sm text-green-100/90 md:text-base">
                    Setiap rupiah infaq dan setiap hewan kurban yang Anda titipkan, kami salurkan dengan amanah untuk
                    kemakmuran umat.
                </p>
            </div>

            <div class="mx-auto grid max-w-5xl grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
                <div class="rounded-2xl bg-white/10 p-5 text-center backdrop-blur-sm ring-1 ring-white/20 md:p-6">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white/15">
                        <i class="fas fa-hand-holding-dollar text-xl text-white" aria-hidden="true"></i>
                    </div>
                    <p class="text-xl font-extrabold text-white md:text-2xl">
                        Rp {{ number_format($impactStats['total_donation'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-green-100/80 md:text-sm">Infaq &amp; Zakat Terkumpul</p>
                </div>

                <div class="rounded-2xl bg-white/10 p-5 text-center backdrop-blur-sm ring-1 ring-white/20 md:p-6">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white/15">
                        <i class="fas fa-users text-xl text-white" aria-hidden="true"></i>
                    </div>
                    <p class="text-xl font-extrabold text-white md:text-2xl">{{ $impactStats['total_donors'] }}+</p>
                    <p class="mt-1 text-xs text-green-100/80 md:text-sm">Donatur Berpartisipasi</p>
                </div>

                <div class="rounded-2xl bg-white/10 p-5 text-center backdrop-blur-sm ring-1 ring-white/20 md:p-6">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white/15">
                        <i class="fas fa-cow text-xl text-white" aria-hidden="true"></i>
                    </div>
                    <p class="text-xl font-extrabold text-white md:text-2xl">
                        {{ $impactStats['total_sacrificed_animals'] }}</p>
                    <p class="mt-1 text-xs text-green-100/80 md:text-sm">Ekor Hewan Kurban Disalurkan</p>
                </div>

                <div class="rounded-2xl bg-white/10 p-5 text-center backdrop-blur-sm ring-1 ring-white/20 md:p-6">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white/15">
                        <i class="fas fa-heart-circle-check text-xl text-white" aria-hidden="true"></i>
                    </div>
                    <p class="text-xl font-extrabold text-white md:text-2xl">
                        {{ $impactStats['total_qurban_participants'] }}</p>
                    <p class="mt-1 text-xs text-green-100/80 md:text-sm">Jamaah Shohibul Kurban</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('public.infaq.index') }}"
                    class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-green-700 shadow-lg transition hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-hand-holding-heart mr-2" aria-hidden="true"></i> Tunaikan Infaq Sekarang
                </a>
                <a href="{{ route('public.qurban.index') }}"
                    class="rounded-full border-2 border-white/60 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    <i class="fas fa-mosque mr-2" aria-hidden="true"></i> Daftar Kurban
                </a>
            </div>
        </div>
    </section>

    <!-- ===== CAMPAIGN INFAQ ===== -->
    @if ($activeCampaigns->isNotEmpty())
        <section id="campaigns" class="bg-white py-16 md:py-20" data-reveal>
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <span
                        class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Campaign
                        Infaq</span>
                    <h2 class="mt-3 text-3xl font-extrabold text-green-800 md:text-4xl">Sedang <span
                            class="text-green-600">Berlangsung</span></h2>
                    <div class="mx-auto mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-green-600 to-green-400"></div>
                    <p class="mx-auto mt-4 max-w-2xl text-gray-500">
                        Yuk ikut berpartisipasi dalam campaign infaq yang sedang berjalan di Masjid An-Nur.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($activeCampaigns as $campaign)
                        <a href="{{ route('public.infaq.campaign', $campaign->slug) }}"
                            class="card-hover group flex flex-col overflow-hidden rounded-2xl bg-white shadow-md">
                            <div class="relative h-48 overflow-hidden">
                                @if ($campaign->thumbnail)
                                    <img src="{{ Storage::url($campaign->thumbnail) }}" alt="{{ $campaign->title }}"
                                        class="card-image h-full w-full object-cover" loading="lazy">
                                @else
                                    <div
                                        class="flex h-full w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                        <i class="fas fa-hand-holding-heart text-5xl text-green-400"
                                            aria-hidden="true"></i>
                                    </div>
                                @endif
                                @if ($campaign->category)
                                    <span
                                        class="absolute left-3 top-3 rounded-full bg-green-600/90 px-3 py-1 text-xs font-semibold text-white">
                                        {{ $campaign->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h3
                                    class="mb-2 line-clamp-2 text-lg font-bold text-gray-800 transition group-hover:text-green-700">
                                    {{ $campaign->title }}
                                </h3>
                                @if ($campaign->description)
                                    <p class="mb-4 line-clamp-2 text-sm text-gray-500">{{ $campaign->description }}</p>
                                @endif
                                <div class="mt-auto pt-2">
                                    <x-quota-progress :booked="$campaign->collected_amount"
                                        :total="$campaign->target_amount" :showLabel="false" />
                                    <p class="mt-2 text-xs font-semibold text-green-700">
                                        Terkumpul Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}
                                        <span class="font-normal text-gray-400">dari Rp
                                            {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <a href="{{ route('public.infaq.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-green-600 px-8 py-3 font-semibold text-white shadow-md transition hover:bg-green-700 hover:shadow-lg">
                        <i class="fas fa-hand-holding-heart" aria-hidden="true"></i>
                        <span>Lihat Semua Campaign &amp; Donasi</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- ===== DONATION ===== -->
    <section id="donation" class="bg-gray-50 py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-5xl overflow-hidden rounded-2xl bg-white shadow-xl transition hover:shadow-2xl">
                <div class="flex flex-col md:flex-row">
                    <div class="relative bg-gradient-to-br from-green-700 to-green-600 p-8 text-white md:w-3/5 md:p-10">
                        <div class="absolute right-0 top-0 opacity-10">
                            <i class="fas fa-hand-holding-heart text-[100px]" aria-hidden="true"></i>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="mb-2 inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider">
                                <i class="fas fa-heart mr-1 text-red-300" aria-hidden="true"></i> Berbagi Berkah
                            </div>
                            <h2 class="mb-3 text-2xl font-bold md:text-3xl">Tunaikan Infaq & Sedekah</h2>
                            <p class="mb-6 text-sm text-green-100/90 md:text-base">
                                Salurkan infaq dan sedekah Anda melalui masjid kami untuk kemakmuran umat dan pembangunan
                                fasilitas ibadah.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('public.infaq.index') }}"
                                    class="rounded-full bg-white px-6 py-3 font-semibold text-green-700 transition hover:scale-105 hover:shadow-lg">
                                    <i class="fas fa-hand-holding-heart mr-2" aria-hidden="true"></i> Donasi Sekarang
                                </a>
                                <a href="#about"
                                    class="rounded-full border-2 border-white/50 px-6 py-3 font-semibold text-white transition hover:bg-white/10">
                                    <i class="fas fa-info-circle mr-2" aria-hidden="true"></i> Pelajari
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center p-8 md:w-2/5">
                        <div class="text-center">
                            <div class="relative inline-block rounded-2xl bg-white p-4 shadow-lg">
                                @if ($donationSettings->qris_image)
                                    <img src="{{ Storage::url($donationSettings->qris_image) }}" alt="QRIS Donasi"
                                        class="mx-auto h-32 w-32 object-contain md:h-40 md:w-40" loading="lazy">
                                @else
                                    <div
                                        class="mx-auto flex h-32 w-32 items-center justify-center rounded-lg bg-gray-100 text-gray-400 md:h-40 md:w-40">
                                        <i class="fas fa-qrcode text-4xl" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div
                                    class="absolute -right-2 -top-2 rounded-full bg-green-600 px-2 py-0.5 text-[10px] font-bold text-white">
                                    QRIS</div>
                            </div>
                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-800">Scan QRIS untuk Donasi</h3>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $donationSettings->qris_image ? 'Gunakan aplikasi perbankan Anda' : 'QRIS belum diatur oleh Admin' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ABOUT ===== -->
    <section id="about" class="bg-white py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center">
                <span class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Tentang
                    Kami</span>
                <h2 class="mt-3 text-3xl font-extrabold text-green-800 md:text-4xl">Tentang <span
                        class="text-green-600">MASJID AN-NUR</span></h2>
                <div class="mx-auto mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-green-600 to-green-400"></div>
            </div>

            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <div class="lg:w-1/2">
                    <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                        @if ($mosque && $mosque->hero_image)
                            <img src="{{ Storage::url($mosque->hero_image) }}" alt="{{ $mosque->name }}"
                                class="h-auto w-full" loading="lazy">
                        @else
                            <img src="https://images.unsplash.com/photo-1584551246679-258d2be6d3a8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80&fm=webp"
                                alt="Masjid" class="h-auto w-full" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 text-white">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-600 text-xl">
                                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">{{ $mosque->name ?? 'MASJID AN-NUR' }}</h3>
                                    <p class="text-sm text-green-200">Berdiri sejak 1985</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2">
                    <div class="mb-6 rounded-2xl bg-green-50 p-6 shadow-inner">
                        <h3 class="mb-3 text-xl font-bold text-green-800">
                            <i class="fas fa-history mr-2 text-green-600" aria-hidden="true"></i>
                            Sejarah Berdiri
                        </h3>
                        <p class="text-gray-700">
                            {{ $mosque->history ?? 'Masjid An-Nur berdiri sebagai pusat kegiatan keagamaan dan sosial masyarakat.' }}
                        </p>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="rounded-xl border border-green-100 bg-green-50 p-6 transition hover:shadow-md">
                            <div class="mb-3 flex items-center">
                                <div
                                    class="mr-4 rounded-lg bg-gradient-to-br from-green-600 to-green-700 p-3 text-white shadow-md">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </div>
                                <h4 class="font-bold text-green-800">Visi</h4>
                            </div>
                            <p class="text-sm text-gray-700">
                                {{ $mosque->vision ?? 'Menjadi pusat peradaban Islam yang rahmatan lil alamin.' }}</p>
                        </div>
                        <div class="rounded-xl border border-green-100 bg-green-50 p-6 transition hover:shadow-md">
                            <div class="mb-3 flex items-center">
                                <div
                                    class="mr-4 rounded-lg bg-gradient-to-br from-green-600 to-green-700 p-3 text-white shadow-md">
                                    <i class="fas fa-bullseye" aria-hidden="true"></i>
                                </div>
                                <h4 class="font-bold text-green-800">Misi</h4>
                            </div>
                            <p class="text-sm text-gray-700">
                                {{ $mosque->mission ?? 'Membangun masyarakat Qur\'ani melalui pendidikan, sosial, dan dakwah.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ACTIVITIES (GALLERY) ===== -->
    <section id="activities" class="bg-gray-50 py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Galeri</span>
                <h2 class="mt-3 text-3xl font-extrabold text-green-800 md:text-4xl">Galeri <span
                        class="text-green-600">Kegiatan</span></h2>
                <div class="mx-auto mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-green-600 to-green-400"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($galleryEvents as $event)
                    <div class="card-hover group relative overflow-hidden rounded-xl shadow-md">
                        <a href="{{ route('public.events.show', $event->slug) }}" class="absolute inset-0 z-10"
                            aria-label="Lihat detail kegiatan {{ $event->title }}"></a>
                        @if ($event->thumbnail)
                            <img src="{{ Storage::url($event->thumbnail) }}" alt="{{ $event->title }}"
                                class="card-image h-64 w-full object-cover" loading="lazy">
                        @else
                            <img src="https://images.unsplash.com/photo-1586444248902-2f1a2b9e7e6d?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80&fm=webp"
                                alt="Kegiatan" class="card-image h-64 w-full object-cover" loading="lazy">
                        @endif
                        <div class="gallery-overlay absolute inset-0 flex items-end p-6">
                            <div class="gallery-content w-full">
                                <span
                                    class="inline-block rounded-full bg-green-600/90 px-3 py-1 text-xs font-semibold text-white">
                                    <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                                    {{ $event->start_at->translatedFormat('F Y') }}
                                </span>
                                <h3 class="mt-2 text-xl font-bold text-white">{{ $event->title }}</h3>
                                <p class="text-sm text-green-200">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500">Belum ada kegiatan yang dipublikasikan.</p>
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('public.events.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-green-600 px-8 py-3 font-semibold text-green-600 transition hover:bg-green-50 hover:shadow-lg">
                    <span>Lihat Semua Kegiatan</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== ARTICLES ===== -->
    <section id="articles" class="bg-white py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Artikel</span>
                <h2 class="mt-3 text-3xl font-extrabold text-green-800 md:text-4xl">Artikel <span
                        class="text-green-600">Terkini</span></h2>
                <div class="mx-auto mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-green-600 to-green-400"></div>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse($latestArticles as $article)
                    <article class="card-hover flex flex-col overflow-hidden rounded-2xl bg-white shadow-md">
                        <a href="{{ route('public.articles.show', $article->slug) }}" class="block overflow-hidden">
                            @if ($article->thumbnail)
                                <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}"
                                    class="card-image h-52 w-full object-cover" loading="lazy">
                            @else
                                <img src="https://images.unsplash.com/photo-1564121211835-e88c852648ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80&fm=webp"
                                    alt="Artikel" class="card-image h-52 w-full object-cover" loading="lazy">
                            @endif
                        </a>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                <span
                                    class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Kajian</span>
                                <span class="text-gray-400">•</span>
                                <span
                                    class="text-gray-500">{{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}</span>
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-500"><i class="far fa-eye mr-1" aria-hidden="true"></i>
                                    {{ number_format($article->views_count) }}</span>
                            </div>
                            <a href="{{ route('public.articles.show', $article->slug) }}" class="block">
                                <h3
                                    class="mt-3 line-clamp-2 text-xl font-bold text-green-800 transition hover:text-green-600">
                                    {{ $article->title }}</h3>
                            </a>
                            <p class="mt-3 line-clamp-3 flex-1 text-sm text-gray-600">
                                {{ Str::limit(strip_tags($article->content), 150) }}</p>
                            <a href="{{ route('public.articles.show', $article->slug) }}"
                                class="mt-4 inline-flex items-center font-semibold text-green-600 transition hover:text-green-800">
                                Baca Selengkapnya <i class="fas fa-arrow-right ml-2 text-xs" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full text-center text-gray-500">Belum ada artikel yang diterbitkan.</p>
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('public.articles.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-green-600 px-8 py-3 font-semibold text-green-600 transition hover:bg-green-50 hover:shadow-lg">
                    <span>Lihat Semua Artikel</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== CONTACT ===== -->
    <section id="contact" class="bg-gray-50 py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Kontak</span>
                <h2 class="mt-3 text-3xl font-extrabold text-green-800 md:text-4xl">Hubungi <span
                        class="text-green-600">Kami</span></h2>
                <div class="mx-auto mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-green-600 to-green-400"></div>
            </div>

            <div class="flex flex-col gap-8 lg:flex-row">
                <!-- Contact Info -->
                <div class="lg:w-5/12">
                    <div class="h-full rounded-2xl bg-white p-8 shadow-md">
                        <h3 class="mb-6 text-xl font-bold text-green-800">
                            <i class="fas fa-address-card mr-2 text-green-600" aria-hidden="true"></i>
                            Informasi Kontak
                        </h3>
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="rounded-xl bg-green-100 p-3 text-green-600">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Alamat</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $mosque->address ?? 'Jl. Kebajikan No. 123, Jakarta Selatan' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="rounded-xl bg-green-100 p-3 text-green-600">
                                    <i class="fas fa-phone-alt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Telepon</h4>
                                    <a href="tel:{{ $mosque->contact ?? '' }}"
                                        class="text-sm text-gray-600 hover:text-green-600">{{ $mosque->contact ?? '(021) 7654321' }}</a>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="rounded-xl bg-green-100 p-3 text-green-600">
                                    <i class="fas fa-clock" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Jam Layanan</h4>
                                    <p class="text-sm text-gray-600">24 Jam / Setiap Hari</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mt-8 border-t border-gray-100 pt-6">
                            <h4 class="mb-3 font-semibold text-gray-800">Ikuti Kami</h4>
                            <div class="flex gap-3">
                                <a href="#"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600 transition hover:bg-green-600 hover:text-white"
                                    aria-label="Facebook">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                </a>
                                <a href="#"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600 transition hover:bg-green-600 hover:text-white"
                                    aria-label="Instagram">
                                    <i class="fab fa-instagram" aria-hidden="true"></i>
                                </a>
                                <a href="#"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600 transition hover:bg-green-600 hover:text-white"
                                    aria-label="YouTube">
                                    <i class="fab fa-youtube" aria-hidden="true"></i>
                                </a>
                                <a href="#"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600 transition hover:bg-green-600 hover:text-white"
                                    aria-label="Twitter">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="lg:w-7/12">
                    <div class="h-full min-h-[400px] overflow-hidden rounded-2xl shadow-md">
                        @if ($mosque && $mosque->latitude && $mosque->longitude)
                            <iframe
                                src="https://www.google.com/maps?q={{ $mosque->latitude }},{{ $mosque->longitude }}&output=embed"
                                width="100%" height="100%" style="min-height: 400px; border: 0" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta lokasi Masjid"
                                class="rounded-2xl"></iframe>
                        @else
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2357.7042929752856!2d107.14797669530307!3d-6.372018314286549!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699969f55e6037%3A0x28f0ca92520f4b4c!2sMasjid%20Annur%20Puri%20Sentosa!5e0!3m2!1sen!2sid!4v1782161731163!5m2!1sen!2sid"
                                width="100%" height="100%" style="min-height: 400px; border: 0" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta lokasi Masjid"
                                class="rounded-2xl"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========================================
            // PRAYER TIMES - DYNAMIC
            // ========================================

            // Data dari server
            const prayerData = @json($prayerData);
            const prayerTimes = prayerData.prayer_times;
            let currentPrayer = prayerData.current_prayer;
            let nextPrayer = prayerData.next_prayer;
            let timeRemaining = prayerData.time_remaining;
            let lastUpdated = prayerData.last_updated;

            // DOM Elements
            const container = document.getElementById('prayer-times-container');
            const currentLabel = document.getElementById('current-prayer-label');
            const nextInfo = document.getElementById('next-prayer-info');
            const dateEl = document.getElementById('prayer-date');
            const refreshBtn = document.getElementById('refresh-prayer');

            // Format tanggal
            const now = new Date();
            const dateOptions = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            dateEl.textContent = `Waktu Sholat Hari Ini - ${now.toLocaleDateString('id-ID', dateOptions)}`;

            // Fungsi update
            function updatePrayerDisplay() {
                const now = new Date();
                const currentTime = formatTime(now);

                // Cari sholat saat ini
                let current = null;
                for (let i = prayerTimes.length - 1; i >= 0; i--) {
                    if (currentTime >= prayerTimes[i].time) {
                        current = prayerTimes[i];
                        break;
                    }
                }
                if (!current) {
                    current = prayerTimes[prayerTimes.length - 1];
                }
                currentPrayer = current;

                // Cari sholat berikutnya
                const currentIndex = prayerTimes.indexOf(current);
                const nextIndex = (currentIndex + 1) % prayerTimes.length;
                nextPrayer = prayerTimes[nextIndex];

                // Update label
                currentLabel.innerHTML = `🕌 Waktu ${currentPrayer.name}`;
                nextInfo.textContent = `Berikutnya: ${nextPrayer.name} pukul ${nextPrayer.time}`;

                // Update cards
                container.innerHTML = prayerTimes.map((prayer, idx) => {
                    const isActive = prayer.name === currentPrayer.name;
                    return `
                    <div class="prayer-card rounded-xl p-3 text-center backdrop-blur-sm ${isActive ? 'active-prayer' : 'bg-green-800/40'}">
                        <p class="text-xs text-green-200">${prayer.name}</p>
                        <p class="text-lg font-bold text-white">${prayer.time}</p>
                        ${isActive ? '<span class="text-[10px] text-yellow-200">● Sekarang</span>' : ''}
                    </div>
                `;
                }).join('');

                // Update countdown
                updateCountdown();

                // Update footer jika ada (untuk kompatibilitas dengan floating prayer)
                if (window.updateFooterPrayerTimes) {
                    window.updateFooterPrayerTimes(prayerTimes);
                }

                // Update last updated
                lastUpdated = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function updateCountdown() {
                if (!nextPrayer) return;

                const now = new Date();
                const [hours, minutes] = nextPrayer.time.split(':').map(Number);
                let nextTime = new Date(now);
                nextTime.setHours(hours, minutes, 0, 0);

                if (nextTime <= now) {
                    nextTime.setDate(nextTime.getDate() + 1);
                }

                const diff = nextTime - now;
                const hoursRemain = Math.floor(diff / (1000 * 60 * 60));
                const minutesRemain = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                const timeText = hoursRemain > 0 ?
                    `${hoursRemain}j ${minutesRemain}m` :
                    `${minutesRemain}m`;

                const timeRemainingEl = document.getElementById('time-remaining');
                if (timeRemainingEl) {
                    timeRemainingEl.innerHTML = `
                    <i class="fas fa-hourglass-half mr-1" aria-hidden="true"></i>
                    Berikutnya: ${nextPrayer.name} pukul ${nextPrayer.time}
                    <span class="ml-2 text-yellow-200">⏳ ${timeText}</span>
                `;
                }
            }

            function formatTime(date) {
                return date.getHours().toString().padStart(2, '0') + ':' +
                    date.getMinutes().toString().padStart(2, '0');
            }

            // Refresh dari API (paksa ambil data terbaru, lewati cache 12 jam di server)
            async function refreshPrayerTimes() {
                try {
                    const response = await fetch('/api/prayer/refresh', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    const result = await response.json();

                    if (result.success) {
                        const data = result.data;
                        prayerTimes.length = 0;
                        prayerTimes.push(...data.prayer_times);
                        currentPrayer = data.current_prayer;
                        nextPrayer = data.next_prayer;
                        lastUpdated = data.last_updated;
                        updatePrayerDisplay();
                    }
                } catch (error) {
                    console.error('Failed to refresh prayer times:', error);
                }
            }

            // Initial render
            updatePrayerDisplay();

            // Auto update every 30 seconds
            setInterval(updatePrayerDisplay, 30000);

            // Countdown update every second
            setInterval(updateCountdown, 1000);

            // Refresh button
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    icon.classList.add('fa-spin');
                    refreshPrayerTimes().finally(() => {
                        icon.classList.remove('fa-spin');
                    });
                });
            }

            // ========================================
            // BACK TO TOP (dari partial)
            // ========================================
            const backToTopBtn = document.getElementById('back-to-top');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 400) {
                        backToTopBtn.classList.add('visible');
                    } else {
                        backToTopBtn.classList.remove('visible');
                    }
                });

                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>
@endpush
