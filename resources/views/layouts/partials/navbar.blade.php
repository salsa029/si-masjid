<!-- ================================================================
NAVBAR
================================================================ -->
<header class="fixed z-50 w-full bg-white/90 shadow-sm backdrop-blur-md transition-shadow duration-300" id="navbar"
    role="banner" x-data="{ mobileMenuOpen: false }" @click.outside="mobileMenuOpen = false"
    :class="{ 'shadow-md': window.scrollY > 50 }">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between py-3 md:py-4">
            <!-- ===== LOGO ===== -->
            <a href="{{ route('home') }}" class="group flex items-center" aria-label="Beranda">
                @php
                    $mosque = \App\Models\MosqueProfile::first();
                @endphp
                <img src="{{ asset('images/logo-annur.png') }}" alt="{{ $mosque->name ?? 'Masjid An-Nur' }}"
                    class="h-10 w-auto transition-transform group-hover:scale-105 md:h-12">
            </a>

            <!-- ===== DESKTOP NAVIGATION ===== -->
            <nav class="hidden items-center space-x-1 lg:flex" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}"
                    class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} px-3 py-2 text-sm">
                    Beranda
                </a>
                <a href="{{ route('public.committees.index') }}"
                    class="nav-link {{ request()->routeIs('public.committees.*') ? 'active' : '' }} px-3 py-2 text-sm">
                    Pengurus
                </a>
                <a href="{{ route('public.events.index') }}"
                    class="nav-link {{ request()->routeIs('public.events.*') && !request()->routeIs('public.events.calendar') ? 'active' : '' }} px-3 py-2 text-sm">
                    Kegiatan
                </a>
                <a href="{{ route('public.events.calendar') }}"
                    class="nav-link {{ request()->routeIs('public.events.calendar') ? 'active' : '' }} px-3 py-2 text-sm">
                    Kalender
                </a>
                <a href="{{ route('public.articles.index') }}"
                    class="nav-link {{ request()->routeIs('public.articles.*') ? 'active' : '' }} px-3 py-2 text-sm">
                    Artikel
                </a>
                <a href="{{ route('public.infaq.index') }}"
                    class="nav-link {{ request()->routeIs('public.infaq.*') && !request()->routeIs('public.infaq.history') ? 'active' : '' }} px-3 py-2 text-sm">
                    Infaq
                </a>
                <a href="{{ route('public.zakat.index') }}"
                    class="nav-link {{ request()->routeIs('public.zakat.*') && !request()->routeIs('public.zakat.history') && !request()->routeIs('public.zakat.calculator') ? 'active' : '' }} px-3 py-2 text-sm">
                    Zakat
                </a>
                <a href="{{ route('public.qurban.index') }}"
                    class="nav-link {{ request()->routeIs('public.qurban.*') ? 'active' : '' }} px-3 py-2 text-sm">
                    Kurban
                </a>
            </nav>

            <!-- ===== RIGHT SECTION ===== -->
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Donation Button -->
                <a href="{{ route('public.infaq.index') }}"
                    class="hidden items-center gap-2 rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700 hover:shadow-lg md:flex">
                    <i class="fas fa-hand-holding-heart" aria-hidden="true"></i>
                    Donasi
                </a>

                <!-- ===== PROFILE / AUTH ===== -->
                @auth
                    <div class="profile-dropdown hidden md:block" x-data="{ open: false }" @click.outside="open = false">
                        <button class="profile-btn" @click="open = !open" :aria-expanded="open.toString()">
                            @if (Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                    class="h-8 w-8 rounded-full object-cover shadow-md">
                            @else
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-700 text-white shadow-md">
                                    <i class="fas fa-user text-sm" aria-hidden="true"></i>
                                </div>
                            @endif
                            <span
                                class="hidden text-sm font-medium text-gray-700 lg:inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''" aria-hidden="true"></i>
                        </button>

                        <!-- Dropdown Content -->
                        <div class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black/5"
                            role="menu" x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="text-xs text-gray-500">Masuk sebagai</p>
                                <p class="font-medium text-green-600">
                                    {{ Auth::user()->hasRole('admin') ? 'Admin' : 'Jamaah' }}
                                </p>
                            </div>

                            @if (Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item" role="menuitem">
                                    <i class="fas fa-tools text-gray-400" aria-hidden="true"></i>
                                    Dashboard Admin
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                                <i class="fas fa-user-circle text-gray-400" aria-hidden="true"></i>
                                Edit Profil
                            </a>

                            <a href="{{ route('public.infaq.history') }}" class="dropdown-item" role="menuitem">
                                <i class="fas fa-hand-holding-heart text-gray-400" aria-hidden="true"></i>
                                Riwayat Infaq
                            </a>
                            <a href="{{ route('public.zakat.history') }}" class="dropdown-item" role="menuitem">
                                <i class="fas fa-hand-holding-usd text-gray-400" aria-hidden="true"></i>
                                Riwayat Zakat
                            </a>
                            <a href="{{ route('public.qurban.orders.history') }}" class="dropdown-item" role="menuitem">
                                <i class="fas fa-cow text-gray-400" aria-hidden="true"></i>
                                Pesanan Kurban
                            </a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-red-600 hover:bg-red-50" role="menuitem">
                                    <i class="fas fa-sign-out-alt text-red-400" aria-hidden="true"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden items-center gap-3 md:flex">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-green-700 hover:underline">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-green-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
                            Daftar
                        </a>
                    </div>
                @endauth

                <!-- ===== MOBILE MENU BUTTON ===== -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="rounded-full p-2 text-gray-600 transition hover:bg-gray-100 lg:hidden"
                    :aria-expanded="mobileMenuOpen.toString()" aria-label="Toggle menu">
                    <i class="fas text-xl" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- ===== MOBILE MENU ===== -->
        <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5 lg:hidden" x-show="mobileMenuOpen"
            x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4">
            <div class="space-y-1 px-2 pb-4 pt-2">
                <!-- Mobile Nav Links -->
                <a href="{{ route('home') }}" class="mobile-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Beranda
                </a>
                <a href="{{ route('public.committees.index') }}"
                    class="mobile-link {{ request()->routeIs('public.committees.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Pengurus
                </a>
                <a href="{{ route('public.events.index') }}"
                    class="mobile-link {{ request()->routeIs('public.events.*') && !request()->routeIs('public.events.calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Kegiatan
                </a>
                <a href="{{ route('public.events.calendar') }}"
                    class="mobile-link {{ request()->routeIs('public.events.calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Kalender
                </a>
                <a href="{{ route('public.articles.index') }}"
                    class="mobile-link {{ request()->routeIs('public.articles.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Artikel
                </a>
                <a href="{{ route('public.infaq.index') }}"
                    class="mobile-link {{ request()->routeIs('public.infaq.*') && !request()->routeIs('public.infaq.history') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-heart w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Infaq
                </a>
                <a href="{{ route('public.zakat.index') }}"
                    class="mobile-link {{ request()->routeIs('public.zakat.*') && !request()->routeIs('public.zakat.history') && !request()->routeIs('public.zakat.calculator') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Zakat
                </a>
                <a href="{{ route('public.zakat.calculator') }}"
                    class="mobile-link {{ request()->routeIs('public.zakat.calculator') ? 'active' : '' }}">
                    <i class="fas fa-calculator w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Kalkulator Zakat
                </a>
                <a href="{{ route('public.qurban.index') }}"
                    class="mobile-link {{ request()->routeIs('public.qurban.*') ? 'active' : '' }}">
                    <i class="fas fa-cow w-5 text-center text-gray-400" aria-hidden="true"></i>
                    Kurban
                </a>

                <!-- Mobile Auth Section -->
                <div class="mt-4 border-t border-gray-200 pt-4">
                    @auth
                        <div class="flex items-center gap-3 px-3 py-2">
                            @if (Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                    class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-white">
                                    <i class="fas fa-user text-sm" aria-hidden="true"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="mt-2 space-y-1">
                            @if (Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="mobile-link">
                                    <i class="fas fa-tools w-5 text-center text-gray-400" aria-hidden="true"></i>
                                    Dashboard Admin
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="mobile-link">
                                <i class="fas fa-user-circle w-5 text-center text-gray-400" aria-hidden="true"></i>
                                Edit Profil
                            </a>

                            <a href="{{ route('public.infaq.history') }}" class="mobile-link">
                                <i class="fas fa-hand-holding-heart w-5 text-center text-gray-400" aria-hidden="true"></i>
                                Riwayat Infaq
                            </a>
                            <a href="{{ route('public.zakat.history') }}" class="mobile-link">
                                <i class="fas fa-hand-holding-usd w-5 text-center text-gray-400" aria-hidden="true"></i>
                                Riwayat Zakat
                            </a>
                            <a href="{{ route('public.qurban.orders.history') }}" class="mobile-link">
                                <i class="fas fa-cow w-5 text-center text-gray-400" aria-hidden="true"></i>
                                Pesanan Kurban
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="mobile-link w-full text-left text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-5 text-center text-red-400" aria-hidden="true"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col gap-2 px-3">
                            <a href="{{ route('login') }}"
                                class="block rounded-lg bg-green-600 px-4 py-2.5 text-center font-semibold text-white hover:bg-green-700">
                                <i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="block rounded-lg border-2 border-green-600 px-4 py-2.5 text-center font-semibold text-green-600 hover:bg-green-50">
                                <i class="fas fa-user-plus mr-2" aria-hidden="true"></i>
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
