<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        * {
            font-family: "Poppins", sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            font-size: 14px;
        }

        /* ===== Sidebar surface ===== */
        .sidebar-surface {
            background: linear-gradient(175deg, #022c22 0%, #064e3b 46%, #065f46 100%);
        }

        /* Faint geometric islamic motif watermark on the branding zone */
        .brand-motif {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='84' height='84' viewBox='0 0 84 84'%3E%3Cg fill='none' stroke='%2334d399' stroke-opacity='0.16' stroke-width='1'%3E%3Cpath d='M42 4 L74 24 L74 60 L42 80 L10 60 L10 24 Z'/%3E%3Cpath d='M42 20 L60 32 L60 52 L42 64 L24 52 L24 32 Z'/%3E%3C/g%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        /* ===== Sidebar nav items ===== */
        .sidebar-item {
            position: relative;
            border-left: 3px solid transparent;
            transition: background-color .3s ease, color .3s ease, border-color .3s ease,
                transform .25s cubic-bezier(.34, 1.2, .64, 1), box-shadow .3s ease;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #d1fae5;
            transform: translateX(3px);
        }

        .sidebar-item:active {
            transform: translateX(3px) scale(.98);
        }

        .sidebar-item .fa-chevron-down {
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
        }

        .sidebar-item i:not(.fa-chevron-down) {
            transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
        }

        .sidebar-item:hover i:not(.fa-chevron-down) {
            transform: scale(1.12);
        }

        .sidebar-item.active {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border-left-color: #6ee7b7;
            box-shadow: 0 4px 14px -4px rgba(4, 120, 87, 0.55);
            animation: sidebarActivePop .4s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        @keyframes sidebarActivePop {
            0% {
                transform: scale(.96);
                box-shadow: 0 0 0 -4px rgba(4, 120, 87, 0);
            }

            60% {
                transform: scale(1.015);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 4px 14px -4px rgba(4, 120, 87, 0.55);
            }
        }

        .sidebar-sub-item {
            position: relative;
            transition: background-color .25s ease, color .25s ease, transform .22s cubic-bezier(.4, 0, .2, 1);
            animation: sidebarSubItemIn .3s cubic-bezier(.4, 0, .2, 1) both;
        }

        /* Stagger halus saat submenu terbuka */
        .sidebar-sub-item:nth-child(1) {
            animation-delay: .02s;
        }

        .sidebar-sub-item:nth-child(2) {
            animation-delay: .06s;
        }

        .sidebar-sub-item:nth-child(3) {
            animation-delay: .10s;
        }

        .sidebar-sub-item:nth-child(4) {
            animation-delay: .14s;
        }

        .sidebar-sub-item:nth-child(5) {
            animation-delay: .18s;
        }

        .sidebar-sub-item:nth-child(6) {
            animation-delay: .22s;
        }

        .sidebar-sub-item:nth-child(7) {
            animation-delay: .26s;
        }

        .sidebar-sub-item:nth-child(8) {
            animation-delay: .30s;
        }

        @keyframes sidebarSubItemIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar-sub-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #d1fae5;
            transform: translateX(3px);
        }

        .sidebar-sub-item.active {
            background: rgba(16, 185, 129, 0.18);
            color: #6ee7b7;
        }

        .sidebar-sub-item.active::before {
            content: "";
            position: absolute;
            left: 1.35rem;
            top: 50%;
            width: 5px;
            height: 5px;
            border-radius: 9999px;
            background: #34d399;
            transform: translateY(-50%);
            transition: transform .25s cubic-bezier(.34, 1.56, .64, 1);
        }

        /* ===== Collapsed-mode tooltip / flyout ===== */
        .sb-tooltip {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-6px) scale(.95);
            transition: opacity .22s cubic-bezier(.4, 0, .2, 1), transform .22s cubic-bezier(.34, 1.56, .64, 1);
        }

        .sb-tooltip-wrap:hover .sb-tooltip {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0) scale(1);
        }

        /* ===== Scrollbar ===== */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* ===== Transisi halaman (poin 2): membuat perpindahan menu terasa halus ===== */
        @keyframes pageFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-fade-in {
            animation: pageFadeIn .45s cubic-bezier(.4, 0, .2, 1) both;
        }

        /* Top loading bar ala progress indicator saat berpindah menu */
        #nprogress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #34d399, #059669);
            z-index: 9999;
            opacity: 0;
            box-shadow: 0 0 8px rgba(16, 185, 129, .6);
            transition: width .4s cubic-bezier(.4, 0, .2, 1), opacity .25s ease;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 text-sm text-gray-800 antialiased" x-data="{
    sidebarOpen: false,
    collapsed: localStorage.getItem('an-nur-admin-collapsed') === '1',
    toggleCollapsed() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('an-nur-admin-collapsed', this.collapsed ? '1' : '0');
    }
}">

    <!-- ============================================================ -->
    <!-- TOP LOADING BAR (poin 2: transisi antar menu lebih smooth) -->
    <!-- ============================================================ -->
    <div id="nprogress-bar"></div>

    <!-- ============================================================ -->
    <!-- TOAST NOTIFICATION -->
    <!-- ============================================================ -->
    <x-toast />

    <!-- ============================================================ -->
    <!-- ADMIN LAYOUT -->
    <!-- ============================================================ -->
    <div class="flex min-h-screen">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak x-transition.opacity
            class="fixed inset-0 z-30 bg-black/50 backdrop-blur-[2px] lg:hidden"></div>

        <!-- ============================================================ -->
        <!-- SIDEBAR (FIXED di semua breakpoint) -->
        <!-- ============================================================ -->
        <aside
            class="sidebar-surface fixed inset-y-0 left-0 z-40 flex flex-shrink-0 transform flex-col overflow-x-hidden text-emerald-50 shadow-2xl transition-all duration-300 lg:translate-x-0"
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                collapsed ? 'w-72 lg:w-[72px]' : 'w-72'
            ]"
            aria-label="Navigasi admin">

            <div class="sidebar-scroll relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">

                <!-- ===================== ZONA 1: BRANDING (skligus tombol ke website publik) ===================== -->
                <div class="brand-motif relative flex-shrink-0 border-b border-emerald-800/70 px-4 py-5">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener"
                        aria-label="Buka website publik Masjid An-Nur di tab baru"
                        class="flex items-center gap-3 rounded-lg transition-opacity duration-200 hover:opacity-80"
                        :class="collapsed ? 'lg:justify-center' : ''">
                        <div
                            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-900/50 ring-2 ring-emerald-300/30">
                            <i class="fas fa-mosque text-lg" aria-hidden="true"></i>
                        </div>
                        <div :class="collapsed ? 'lg:hidden' : ''">
                            <p class="text-sm font-bold tracking-wide">MASJID AN-NUR</p>
                            <p class="text-[11px] text-emerald-300">Admin Panel</p>
                        </div>
                    </a>
                </div>

                <!-- ===================== ZONA 2: NAVIGASI INTI ===================== -->
                <nav class="flex-1 space-y-1.5 overflow-y-auto overflow-x-hidden px-3 py-4" aria-label="Menu utama">
                    @include('admin.partials.sidebar')
                </nav>

                <!-- ===================== FOOTER ===================== -->
                <div class="flex-shrink-0 border-t border-emerald-800/70 px-3 py-4">
                    <p class="text-center text-[10px] text-emerald-500" :class="collapsed ? 'lg:hidden' : ''">
                        v{{ config('app.version', '1.0.0') }}
                    </p>
                </div>
            </div>

            <!-- Tombol collapse (desktop only) -->
            <!-- Pill bulat elegan, sedikit naik dari tepi bawah, di luar area scroll -->
            <div
                class="hidden flex-shrink-0 justify-center border-t border-emerald-800/60 bg-emerald-950/30 pb-4 pt-3 lg:flex">
                <button @click="toggleCollapsed()" aria-label="Ciutkan atau perluas sidebar" :aria-pressed="collapsed"
                    class="group flex h-9 w-9 items-center justify-center rounded-full border border-emerald-500/40 bg-gradient-to-br from-emerald-700 to-emerald-800 text-emerald-200 shadow-lg shadow-emerald-950/50 ring-1 ring-white/5 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-600 hover:to-emerald-700 hover:text-white hover:shadow-emerald-900/60">
                    <i class="fas fa-chevron-left text-xs transition-transform duration-300 group-hover:-translate-x-0.5"
                        :class="collapsed ? 'rotate-180' : ''" aria-hidden="true"></i>
                </button>
            </div>
        </aside>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT (margin mengikuti lebar sidebar yang fixed) -->
        <!-- ============================================================ -->
        <div class="flex min-h-screen flex-1 flex-col transition-all duration-300"
            :class="collapsed ? 'lg:ml-[72px]' : 'lg:ml-72'">

            <!-- Header (FIXED, offset kiri mengikuti lebar sidebar) -->
            <header
                class="fixed right-0 top-0 z-20 flex h-16 items-center justify-between border-b border-gray-200 bg-white/90 px-4 shadow-sm backdrop-blur transition-all duration-300 md:px-6"
                :class="collapsed ? 'left-0 lg:left-[72px]' : 'left-0 lg:left-72'">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" aria-label="Buka menu navigasi"
                        class="text-gray-600 transition-colors hover:text-green-600 lg:hidden">
                        <i class="fas fa-bars text-xl" aria-hidden="true"></i>
                    </button>
                    <h1 class="page-fade-in text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                </div>

                <!-- Dropdown profil admin (menggantikan tombol Keluar terpisah, poin 8) -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" aria-haspopup="true" :aria-expanded="open"
                        aria-label="Buka menu akun admin"
                        class="flex items-center gap-2 rounded-lg px-2 py-1.5 transition-colors hover:bg-gray-100">
                        <span class="hidden text-sm text-gray-600 md:inline">{{ auth()->user()->name }}</span>
                        @if (auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="h-8 w-8 rounded-full object-cover shadow-md">
                        @else
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-700 text-white shadow-md">
                                <i class="fas fa-user text-sm" aria-hidden="true"></i>
                            </div>
                        @endif
                        <i class="fas fa-chevron-down hidden text-[10px] text-gray-400 transition-transform duration-200 md:inline"
                            :class="open ? 'rotate-180' : ''" aria-hidden="true"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition.opacity.duration.150ms
                        class="absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-xl">
                        <div class="border-b border-gray-100 px-4 py-2 md:hidden">
                            <p class="truncate text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-gray-700 transition-colors hover:bg-gray-50">
                            <i class="fas fa-user-circle w-4 text-center text-gray-400" aria-hidden="true"></i>
                            Edit Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-red-600 transition-colors hover:bg-red-50">
                                <i class="fas fa-right-from-bracket w-4 text-center" aria-hidden="true"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content (padding-top menyesuaikan tinggi header yang fixed) -->
            <main class="page-fade-in flex-1 p-4 pt-20 md:p-6 md:pt-20">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SCRIPT: Top loading bar (poin 2 - transisi antar menu smooth) -->
    <!-- ============================================================ -->
    <script>
        (function() {
            var bar = document.getElementById('nprogress-bar');
            if (!bar) return;

            function startBar() {
                bar.style.transition = 'none';
                bar.style.width = '0%';
                bar.style.opacity = '1';
                // force reflow supaya transition berikutnya kepakai
                void bar.offsetWidth;
                bar.style.transition = 'width .4s cubic-bezier(.4,0,.2,1), opacity .25s ease';
                requestAnimationFrame(function() {
                    bar.style.width = '78%';
                });
            }

            function finishBar() {
                bar.style.width = '100%';
                setTimeout(function() {
                    bar.style.opacity = '0';
                    setTimeout(function() {
                        bar.style.width = '0%';
                    }, 250);
                }, 150);
            }

            // Mulai bar saat mengklik link internal (menu sidebar, dsb), kecuali tab baru/anchor
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a[href]');
                if (!link) return;
                if (link.target === '_blank' || link.hasAttribute('download')) return;

                var href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

                try {
                    var url = new URL(link.href, window.location.href);
                    if (url.origin !== window.location.origin) return;
                    if (url.pathname === window.location.pathname && url.search === window.location.search)
                        return;
                } catch (err) {
                    return;
                }

                startBar();
            });

            // Mulai bar juga saat submit form (mis. logout)
            document.addEventListener('submit', function() {
                startBar();
            });

            // Selesaikan bar ketika halaman baru selesai dimuat
            window.addEventListener('pageshow', finishBar);
            finishBar();
        })();
    </script>

    @stack('scripts')
</body>

</html>
