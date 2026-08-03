@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <x-breadcrumb :items="['Dashboard' => null]" />
@endsection

@section('content')

    {{-- ===== STATISTICS CARDS ===== --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <!-- Total Jamaah -->
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Jamaah</p>
                    <p class="mt-1 text-2xl font-bold text-gray-800">{{ number_format($stats['total_jamaah'] ?? 0) }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                <span class="font-medium text-emerald-600">+{{ number_format($stats['total_jamaah'] ?? 0) }}</span> total
                pengguna terdaftar
            </div>
        </div>

        <!-- Total Artikel -->
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Artikel</p>
                    <p class="mt-1 text-2xl font-bold text-gray-800">{{ number_format($stats['total_artikel'] ?? 0) }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                <a href="{{ route('admin.articles.index') }}" class="text-emerald-600 hover:underline">Kelola Artikel →</a>
            </div>
        </div>

        <!-- Total Kegiatan -->
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Kegiatan</p>
                    <p class="mt-1 text-2xl font-bold text-gray-800">{{ number_format($stats['total_kegiatan'] ?? 0) }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                <a href="{{ route('admin.events.index') }}" class="text-emerald-600 hover:underline">Kelola Kegiatan →</a>
            </div>
        </div>

        <!-- Total Dana Infaq -->
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Dana Infaq</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-600">Rp
                        {{ number_format($stats['total_infaq'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                <a href="{{ route('admin.reports.index') }}" class="text-emerald-600 hover:underline">Lihat Laporan →</a>
            </div>
        </div>

        <!-- Total Dana Zakat -->
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Dana Zakat</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-600">Rp
                        {{ number_format($stats['total_zakat'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                <a href="{{ route('admin.reports.index') }}" class="text-emerald-600 hover:underline">Lihat Laporan →</a>
            </div>
        </div>
    </div>

    {{-- ===== CHART & QURBAN SUMMARY ===== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <!-- Grafik Dana Bulanan -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">📊 Grafik Dana Bulanan (6 Bulan Terakhir)</h2>
                <span class="rounded bg-gray-50 px-2 py-1 text-xs text-gray-400">Infaq & Zakat</span>
            </div>

            @if (empty($monthlyFunds) || $monthlyFunds->isEmpty())
                <p class="py-8 text-center text-sm text-gray-400">Belum ada data transaksi pada periode ini.</p>
            @else
                <div class="space-y-3">
                    @foreach ($monthlyFunds as $month)
                        <div>
                            <div class="mb-1 flex justify-between text-xs text-gray-500">
                                <span class="font-medium">{{ $month['label'] }}</span>
                                <span>Rp {{ number_format($month['infaq'] + $month['zakat'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex h-3 gap-0.5 overflow-hidden rounded-full">
                                @php
                                    $total = $month['infaq'] + $month['zakat'];
                                    $maxValue =
                                        $monthlyFunds->max(function ($m) {
                                            return $m['infaq'] + $m['zakat'];
                                        }) ?:
                                        1;
                                    $infaqPct = $maxValue > 0 ? ($month['infaq'] / $maxValue) * 100 : 0;
                                    $zakatPct = $maxValue > 0 ? ($month['zakat'] / $maxValue) * 100 : 0;
                                @endphp
                                @if ($infaqPct > 0)
                                    <div class="h-full bg-emerald-500 transition-all duration-500"
                                        style="width: {{ $infaqPct }}%"></div>
                                @endif
                                @if ($zakatPct > 0)
                                    <div class="h-full bg-amber-400 transition-all duration-500"
                                        style="width: {{ $zakatPct }}%"></div>
                                @endif
                                @if ($total == 0)
                                    <div class="h-full w-full bg-gray-200"></div>
                                @endif
                            </div>
                            <div class="mt-0.5 flex justify-between text-[10px] text-gray-400">
                                <span>Infaq: Rp {{ number_format($month['infaq'], 0, ',', '.') }}</span>
                                <span>Zakat: Rp {{ number_format($month['zakat'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 flex justify-end gap-4 text-[10px] text-gray-500">
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Infaq</span>
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        Zakat</span>
                </div>
            @endif
        </div>

        <!-- Ringkasan Kurban -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">🐄 Ringkasan Data Kurban</h2>
                <a href="{{ route('admin.qurban-dashboard.index') }}"
                    class="text-xs text-emerald-600 hover:underline">Lihat Detail →</a>
            </div>

            <dl class="space-y-3 text-sm">
                <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                    <dt class="text-gray-500">Total Hewan</dt>
                    <dd class="font-semibold">{{ $qurbanSummary['total_animals'] ?? 0 }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                    <dt class="flex items-center gap-1 text-gray-500">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Tersedia
                    </dt>
                    <dd class="font-semibold text-emerald-600">{{ $qurbanSummary['available'] ?? 0 }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                    <dt class="flex items-center gap-1 text-gray-500">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span> Slot Penuh
                    </dt>
                    <dd class="font-semibold text-amber-600">{{ $qurbanSummary['fully_booked'] ?? 0 }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                    <dt class="flex items-center gap-1 text-gray-500">
                        <span class="h-2 w-2 rounded-full bg-gray-400"></span> Sudah Disembelih
                    </dt>
                    <dd class="font-semibold text-gray-600">{{ $qurbanSummary['slaughtered'] ?? 0 }}</dd>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <dt class="font-medium text-gray-500">Total Pesanan Sukses</dt>
                    <dd class="font-bold text-emerald-700">{{ $qurbanSummary['successful_orders'] ?? 0 }}</dd>
                </div>
            </dl>

            <div class="mt-4 border-t border-gray-100 pt-3">
                <a href="{{ route('admin.sacrificial-animals.index') }}"
                    class="text-xs text-emerald-600 hover:underline">
                    Kelola Hewan Kurban →
                </a>
            </div>
        </div>

    </div>

    {{-- ===== QUICK ACTIONS ===== --}}
    <div class="mt-8 grid grid-cols-2 gap-3 md:grid-cols-4">
        <a href="{{ route('admin.articles.create') }}"
            class="group rounded-xl border border-gray-100 bg-white p-4 text-center shadow-sm transition hover:border-emerald-200 hover:shadow-md">
            <div
                class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-600">Tulis Artikel</p>
        </a>

        <a href="{{ route('admin.events.create') }}"
            class="group rounded-xl border border-gray-100 bg-white p-4 text-center shadow-sm transition hover:border-emerald-200 hover:shadow-md">
            <div
                class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-purple-50 text-purple-600 transition group-hover:bg-purple-100">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-600">Tambah Kegiatan</p>
        </a>

        <a href="{{ route('admin.sacrificial-animals.create') }}"
            class="group rounded-xl border border-gray-100 bg-white p-4 text-center shadow-sm transition hover:border-emerald-200 hover:shadow-md">
            <div
                class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-600">Tambah Hewan Kurban</p>
        </a>

        <a href="{{ route('admin.reports.index') }}"
            class="group rounded-xl border border-gray-100 bg-white p-4 text-center shadow-sm transition hover:border-emerald-200 hover:shadow-md">
            <div
                class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-600 transition group-hover:bg-red-100">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-600">Lihat Laporan</p>
        </a>
    </div>

@endsection
