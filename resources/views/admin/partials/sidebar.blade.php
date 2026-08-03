<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" aria-label="Dashboard"
    class="sidebar-item sb-tooltip-wrap {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-emerald-100' }} relative flex items-center gap-3 rounded-xl px-3 py-3"
    :class="collapsed ? 'lg:justify-center' : ''">
    <i class="fas fa-chart-pie w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
    <span :class="collapsed ? 'lg:hidden' : ''">Dashboard</span>
    <span
        class="sb-tooltip pointer-events-none absolute left-full top-1/2 z-50 ml-3 hidden -translate-y-1/2 whitespace-nowrap rounded-lg bg-emerald-950 px-3 py-1.5 text-xs text-white shadow-xl lg:block">Dashboard</span>
</a>


<div class="my-2 border-t border-emerald-800/60" :class="collapsed ? 'lg:mx-2' : 'mx-1'"></div>

<!-- ===================== 1. Artikel & Kajian ===================== -->
<div class="sb-tooltip-wrap relative" x-data="{ open: {{ request()->routeIs('admin.articles.*') || request()->routeIs('admin.article-categories.*') ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open" aria-label="Menu Artikel dan Kajian" :aria-expanded="open"
        class="sidebar-item {{ request()->routeIs('admin.articles.*') || request()->routeIs('admin.article-categories.*') ? 'active' : 'text-emerald-100' }} flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left"
        :class="collapsed ? 'lg:justify-center' : ''">
        <i class="fas fa-book-open w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
        <span class="flex-1" :class="collapsed ? 'lg:hidden' : ''">Artikel &amp; Kajian</span>
        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
            :class="[open ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']" aria-hidden="true"></i>
    </button>

    <div class="space-y-0.5 overflow-hidden pl-2 transition-all duration-300" x-show="open && !collapsed" x-collapse
        x-cloak>
        <a href="{{ route('admin.articles.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.articles.*') && !request()->routeIs('admin.article-categories.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-newspaper w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Artikel</span>
        </a>
        <a href="{{ route('admin.article-categories.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.article-categories.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-tag w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Kategori Artikel</span>
        </a>
    </div>

    <!-- Flyout untuk mode collapsed -->
    <div
        class="sb-tooltip pointer-events-none absolute left-full top-0 z-50 ml-3 hidden w-52 space-y-0.5 rounded-xl bg-emerald-950 p-2 shadow-xl lg:block">
        <p class="px-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-400">Artikel &amp; Kajian</p>
        <a href="{{ route('admin.articles.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Artikel</a>
        <a href="{{ route('admin.article-categories.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Kategori Artikel</a>
    </div>
</div>

<!-- ===================== 2. Galeri Kegiatan ===================== -->
<div class="sb-tooltip-wrap relative" x-data="{ open: {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-categories.*') ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open" aria-label="Menu Galeri Kegiatan" :aria-expanded="open"
        class="sidebar-item {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-categories.*') ? 'active' : 'text-emerald-100' }} flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left"
        :class="collapsed ? 'lg:justify-center' : ''">
        <i class="fas fa-images w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
        <span class="flex-1" :class="collapsed ? 'lg:hidden' : ''">Galeri Kegiatan</span>
        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
            :class="[open ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']" aria-hidden="true"></i>
    </button>

    <div class="space-y-0.5 overflow-hidden pl-2 transition-all duration-300" x-show="open && !collapsed" x-collapse
        x-cloak>
        <a href="{{ route('admin.events.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.events.*') && !request()->routeIs('admin.event-categories.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-calendar-alt w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Kegiatan</span>
        </a>
        <a href="{{ route('admin.event-categories.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.event-categories.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-tag w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Kategori Kegiatan</span>
        </a>
    </div>

    <div
        class="sb-tooltip pointer-events-none absolute left-full top-0 z-50 ml-3 hidden w-52 space-y-0.5 rounded-xl bg-emerald-950 p-2 shadow-xl lg:block">
        <p class="px-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-400">Galeri Kegiatan</p>
        <a href="{{ route('admin.events.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Kegiatan</a>
        <a href="{{ route('admin.event-categories.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Kategori Kegiatan</a>
    </div>
</div>

<div class="my-2 border-t border-emerald-800/60" :class="collapsed ? 'lg:mx-2' : 'mx-1'"></div>

@php
    // Kurban dikeluarkan dari grup ini -> menjadi grup tersendiri (poin 3)
    $donasiActive =
        request()->routeIs('admin.donation-dashboard.*') ||
        request()->routeIs('admin.infaqs.*') ||
        request()->routeIs('admin.infaq-*') ||
        request()->routeIs('admin.donation-verifications.infaq.*') ||
        request()->routeIs('admin.zakats.*') ||
        request()->routeIs('admin.zakat-types.*') ||
        request()->routeIs('admin.donation-verifications.zakat.*');

    $kurbanActive = request()->routeIs('admin.sacrificial-animals.*') || request()->routeIs('admin.qurban-*');
@endphp

<!-- ===================== 3. Donasi & Infaq (Infaq & Zakat) ===================== -->
<div class="sb-tooltip-wrap relative" x-data="{ open: {{ $donasiActive ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open" aria-label="Menu Donasi dan Infaq" :aria-expanded="open"
        class="sidebar-item {{ $donasiActive ? 'active' : 'text-emerald-100' }} flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left"
        :class="collapsed ? 'lg:justify-center' : ''">
        <i class="fas fa-hand-holding-heart w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
        <span class="flex-1" :class="collapsed ? 'lg:hidden' : ''">Donasi &amp; Infaq</span>
        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
            :class="[open ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']" aria-hidden="true"></i>
    </button>

    <div class="sidebar-scroll max-h-80 space-y-0.5 overflow-y-auto pl-2 transition-all duration-300"
        x-show="open && !collapsed" x-collapse x-cloak>
        <a href="{{ route('admin.donation-dashboard.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.donation-dashboard.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-chart-line w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Dashboard Donasi</span>
        </a>
        <a href="{{ route('admin.infaqs.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.infaqs.*') && !request()->routeIs('admin.infaq-*') && !request()->routeIs('admin.donation-verifications.infaq.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-hand-holding-heart w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Daftar Infaq</span>
        </a>
        <a href="{{ route('admin.infaq-categories.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.infaq-categories.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-tag w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Kategori Infaq</span>
        </a>
        <a href="{{ route('admin.infaq-campaigns.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.infaq-campaigns.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-bullhorn w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Campaign Infaq</span>
        </a>
        <a href="{{ route('admin.donation-verifications.infaq.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.donation-verifications.infaq.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-check-circle w-4 text-center text-xs" aria-hidden="true"></i>
            <span class="flex-1">Verifikasi Infaq</span>
            <span class="rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-semibold text-white">12</span>
        </a>
        <a href="{{ route('admin.zakats.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.zakats.*') && !request()->routeIs('admin.zakat-types.*') && !request()->routeIs('admin.donation-verifications.zakat.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-charity w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Daftar Zakat</span>
        </a>
        <a href="{{ route('admin.zakat-types.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.zakat-types.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-tag w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Jenis Zakat</span>
        </a>
        <a href="{{ route('admin.donation-verifications.zakat.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.donation-verifications.zakat.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-check-circle w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Verifikasi Zakat</span>
        </a>
    </div>

    <div
        class="sb-tooltip sidebar-scroll pointer-events-none absolute left-full top-0 z-50 ml-3 hidden max-h-96 w-56 space-y-0.5 overflow-y-auto rounded-xl bg-emerald-950 p-2 shadow-xl lg:block">
        <p class="px-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-400">Donasi &amp; Infaq</p>
        <a href="{{ route('admin.donation-dashboard.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Dashboard Donasi</a>
        <a href="{{ route('admin.infaqs.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Daftar Infaq</a>
        <a href="{{ route('admin.infaq-categories.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Kategori Infaq</a>
        <a href="{{ route('admin.infaq-campaigns.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Campaign Infaq</a>
        <a href="{{ route('admin.donation-verifications.infaq.index') }}"
            class="flex items-center justify-between rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Verifikasi
            Infaq <span class="rounded-full bg-red-500 px-1.5 text-[10px] font-semibold text-white">12</span></a>
        <a href="{{ route('admin.zakats.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Daftar Zakat</a>
        <a href="{{ route('admin.zakat-types.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Jenis Zakat</a>
        <a href="{{ route('admin.donation-verifications.zakat.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Verifikasi Zakat</a>
    </div>
</div>

<!-- ===================== 4. Kurban (grup terpisah dari Donasi & Infaq, poin 3) ===================== -->
<div class="sb-tooltip-wrap relative" x-data="{ open: {{ $kurbanActive ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open" aria-label="Menu Kurban" :aria-expanded="open"
        class="sidebar-item {{ $kurbanActive ? 'active' : 'text-emerald-100' }} flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left"
        :class="collapsed ? 'lg:justify-center' : ''">
        <i class="fas fa-cow w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
        <span class="flex-1" :class="collapsed ? 'lg:hidden' : ''">Kurban</span>
        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
            :class="[open ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']" aria-hidden="true"></i>
    </button>

    <div class="space-y-0.5 overflow-hidden pl-2 transition-all duration-300" x-show="open && !collapsed" x-collapse
        x-cloak>
        <a href="{{ route('admin.sacrificial-animals.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.sacrificial-animals.*') && !request()->routeIs('admin.qurban-*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-cow w-4 text-center text-xs" aria-hidden="true"></i>
            <span class="flex-1">Hewan Kurban</span>
            <span class="rounded-full bg-amber-400 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-950">NEW</span>
        </a>
        <a href="{{ route('admin.qurban-dashboard.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.qurban-dashboard.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-chart-bar w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Dashboard Status Kurban</span>
        </a>
        <a href="{{ route('admin.qurban-verifications.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.qurban-verifications.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-check-circle w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Verifikasi Pembayaran</span>
        </a>
    </div>

    <div
        class="sb-tooltip pointer-events-none absolute left-full top-0 z-50 ml-3 hidden w-56 space-y-0.5 rounded-xl bg-emerald-950 p-2 shadow-xl lg:block">
        <p class="px-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-400">Kurban</p>
        <a href="{{ route('admin.sacrificial-animals.index') }}"
            class="flex items-center justify-between rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Hewan
            Kurban <span
                class="rounded-full bg-amber-400 px-1.5 text-[10px] font-semibold text-emerald-950">NEW</span></a>
        <a href="{{ route('admin.qurban-dashboard.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Dashboard Status Kurban</a>
        <a href="{{ route('admin.qurban-verifications.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Verifikasi Pembayaran</a>
    </div>
</div>

<div class="my-2 border-t border-emerald-800/60" :class="collapsed ? 'lg:mx-2' : 'mx-1'"></div>

@php
    $pengaturanActive =
        request()->routeIs('admin.mosque-profile.*') ||
        request()->routeIs('admin.committees.*') ||
        request()->routeIs('admin.donation-settings.*');
@endphp

<!-- ===================== 5. Pengaturan ===================== -->
<div class="sb-tooltip-wrap relative" x-data="{ open: {{ $pengaturanActive ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open" aria-label="Menu Pengaturan" :aria-expanded="open"
        class="sidebar-item {{ $pengaturanActive ? 'active' : 'text-emerald-100' }} flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left"
        :class="collapsed ? 'lg:justify-center' : ''">
        <i class="fas fa-cog w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
        <span class="flex-1" :class="collapsed ? 'lg:hidden' : ''">Pengaturan</span>
        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
            :class="[open ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']" aria-hidden="true"></i>
    </button>

    <div class="space-y-0.5 overflow-hidden pl-2 transition-all duration-300" x-show="open && !collapsed" x-collapse
        x-cloak>
        <a href="{{ route('admin.mosque-profile.edit') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.mosque-profile.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-mosque w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Profil Masjid</span>
        </a>
        <a href="{{ route('admin.committees.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.committees.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-users w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Pengurus</span>
        </a>
        <a href="{{ route('admin.donation-settings.edit') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.donation-settings.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-sliders w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Pengaturan Donasi</span>
        </a>
    </div>

    <div
        class="sb-tooltip pointer-events-none absolute left-full top-0 z-50 ml-3 hidden w-52 space-y-0.5 rounded-xl bg-emerald-950 p-2 shadow-xl lg:block">
        <p class="px-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-400">Pengaturan</p>
        <a href="{{ route('admin.mosque-profile.edit') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Profil Masjid</a>
        <a href="{{ route('admin.committees.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Pengurus</a>
        <a href="{{ route('admin.donation-settings.edit') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Pengaturan Donasi</a>
    </div>
</div>

<!-- ===================== 6. Laporan ===================== -->
<div class="sb-tooltip-wrap relative" x-data="{ open: {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.audit-trail.*') ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open" aria-label="Menu Laporan" :aria-expanded="open"
        class="sidebar-item {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.audit-trail.*') ? 'active' : 'text-emerald-100' }} flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left"
        :class="collapsed ? 'lg:justify-center' : ''">
        <i class="fas fa-file-invoice-dollar w-5 flex-shrink-0 text-center" aria-hidden="true"></i>
        <span class="flex-1" :class="collapsed ? 'lg:hidden' : ''">Laporan</span>
        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
            :class="[open ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']" aria-hidden="true"></i>
    </button>

    <div class="space-y-0.5 overflow-hidden pl-2 transition-all duration-300" x-show="open && !collapsed" x-collapse
        x-cloak>
        <a href="{{ route('admin.reports.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.reports.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-file-invoice-dollar w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Laporan Keuangan</span>
        </a>
        <a href="{{ route('admin.audit-trail.index') }}"
            class="sidebar-sub-item {{ request()->routeIs('admin.audit-trail.*') ? 'active' : 'text-emerald-200' }} flex items-center gap-3 rounded-lg py-2.5 pl-8 pr-3">
            <i class="fas fa-clipboard-list w-4 text-center text-xs" aria-hidden="true"></i>
            <span>Audit Trail</span>
        </a>
    </div>

    <div
        class="sb-tooltip pointer-events-none absolute left-full top-0 z-50 ml-3 hidden w-52 space-y-0.5 rounded-xl bg-emerald-950 p-2 shadow-xl lg:block">
        <p class="px-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-400">Laporan</p>
        <a href="{{ route('admin.reports.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Laporan Keuangan</a>
        <a href="{{ route('admin.audit-trail.index') }}"
            class="block rounded-lg px-2 py-1.5 text-sm text-emerald-100 hover:bg-white/10">Audit Trail</a>
    </div>
</div>
