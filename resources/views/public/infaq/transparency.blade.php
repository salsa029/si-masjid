@extends('layouts.app')

@section('title', 'Transparansi Infaq')

@section('content')
    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-800 via-emerald-700 to-green-600 py-16 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-10">
            <i class="fas fa-hand-holding-heart absolute -right-10 -top-10 text-[220px]" aria-hidden="true"></i>
        </div>
        <div class="container relative mx-auto px-4 text-center">
            <span class="inline-block rounded-full bg-white/15 px-4 py-1 text-xs font-semibold uppercase tracking-wide">
                <i class="fas fa-eye mr-1.5" aria-hidden="true"></i> Transparansi Dana
            </span>
            <h1 class="mt-4 text-3xl font-extrabold md:text-4xl">Setiap Rupiah, Tercatat &amp; Terlihat</h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm text-emerald-100 md:text-base">
                Riwayat seluruh infaq yang telah masuk sejak awal — tanpa batasan waktu, tanpa disembunyikan.
                Nama yang tampil mengikuti pilihan donatur sendiri saat berinfaq: nama asli, atau
                <span class="font-semibold text-white">"Hamba Allah"</span> bagi yang memilih anonim.
            </p>

            {{-- Stat cards dengan animasi hitung naik --}}
            <div class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">
                    <i class="fas fa-sack-dollar mb-2 block text-2xl text-emerald-200" aria-hidden="true"></i>
                    <p class="text-2xl font-extrabold" data-count-up="{{ (int) $summary['total_amount'] }}" data-prefix="Rp ">Rp 0</p>
                    <p class="mt-1 text-xs text-emerald-100">Total Dana Terkumpul</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">
                    <i class="fas fa-receipt mb-2 block text-2xl text-emerald-200" aria-hidden="true"></i>
                    <p class="text-2xl font-extrabold" data-count-up="{{ $summary['total_transactions'] }}">0</p>
                    <p class="mt-1 text-xs text-emerald-100">Total Transaksi</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">
                    <i class="fas fa-people-group mb-2 block text-2xl text-emerald-200" aria-hidden="true"></i>
                    <p class="text-2xl font-extrabold" data-count-up="{{ $summary['total_donors'] }}">0</p>
                    <p class="mt-1 text-xs text-emerald-100">Donatur Berpartisipasi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FILTER KATEGORI ===== --}}
    <section class="border-b border-gray-100 bg-white py-4">
        <div class="container mx-auto flex flex-wrap items-center justify-center gap-2 px-4">
            <a href="{{ route('public.infaq.transparency') }}"
                class="{{ request('category') ? 'border-gray-200 text-gray-600 hover:border-emerald-400' : 'border-emerald-600 bg-emerald-600 text-white' }} rounded-full border px-4 py-1.5 text-xs font-medium transition">
                Semua Kategori
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('public.infaq.transparency', ['category' => $category->id]) }}"
                    class="{{ request('category') == $category->id ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-200 text-gray-600 hover:border-emerald-400' }} rounded-full border px-4 py-1.5 text-xs font-medium transition">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===== TIMELINE ===== --}}
    <section class="bg-gray-50 py-12">
        <div class="container mx-auto max-w-3xl px-4">
            @forelse ($infaqs as $infaq)
                @php
                    $palette = [
                        ['ring' => 'ring-emerald-400', 'dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700'],
                        ['ring' => 'ring-sky-400', 'dot' => 'bg-sky-500', 'badge' => 'bg-sky-50 text-sky-700'],
                        ['ring' => 'ring-amber-400', 'dot' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700'],
                        ['ring' => 'ring-rose-400', 'dot' => 'bg-rose-500', 'badge' => 'bg-rose-50 text-rose-700'],
                        ['ring' => 'ring-violet-400', 'dot' => 'bg-violet-500', 'badge' => 'bg-violet-50 text-violet-700'],
                    ];
                    $color = $palette[$loop->iteration % count($palette)];
                @endphp
                <div class="relative pb-8 pl-10 last:pb-0">
                    {{-- Garis penghubung timeline --}}
                    @if (! $loop->last)
                        <span class="absolute left-[15px] top-8 h-full w-px bg-gray-200" aria-hidden="true"></span>
                    @endif

                    {{-- Titik penanda --}}
                    <span
                        class="{{ $color['dot'] }} {{ $color['ring'] }} absolute left-0 top-1 flex h-8 w-8 items-center justify-center rounded-full text-white ring-4 ring-offset-2 ring-offset-gray-50">
                        <i class="fas fa-hand-holding-heart text-xs" aria-hidden="true"></i>
                    </span>

                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $infaq->display_name }}
                                    @if ($infaq->is_anonymous)
                                        <i class="fas fa-user-secret ml-1 text-xs text-gray-400" title="Infaq anonim"></i>
                                    @endif
                                </p>
                                <p class="mt-0.5 text-xs text-gray-400">
                                    <i class="far fa-clock mr-1" aria-hidden="true"></i>
                                    {{ $infaq->paid_at?->translatedFormat('d F Y, H:i') }} WIB
                                    <span class="mx-1">·</span>
                                    {{ $infaq->paid_at?->diffForHumans() }}
                                </p>
                            </div>
                            <p class="text-lg font-extrabold text-emerald-700">
                                Rp {{ number_format($infaq->amount, 0, ',', '.') }}
                            </p>
                        </div>

                        @if ($infaq->category || $infaq->campaign)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @if ($infaq->campaign)
                                    <span class="{{ $color['badge'] }} rounded-full px-2.5 py-1 text-xs font-medium">
                                        <i class="fas fa-bullhorn mr-1" aria-hidden="true"></i>{{ $infaq->campaign->title }}
                                    </span>
                                @endif
                                @if ($infaq->category)
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        <i class="fas fa-tag mr-1" aria-hidden="true"></i>{{ $infaq->category->name }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if ($infaq->message)
                            <p class="mt-3 rounded-xl bg-gray-50 p-3 text-sm italic text-gray-600">
                                <i class="fas fa-quote-left mr-1 text-gray-300" aria-hidden="true"></i>{{ $infaq->message }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                    <i class="fas fa-hand-holding-heart mb-3 block text-4xl text-gray-300" aria-hidden="true"></i>
                    <p class="text-sm text-gray-400">Belum ada riwayat infaq untuk ditampilkan.</p>
                </div>
            @endforelse

            @if ($infaqs->hasPages())
                <div class="mt-8">{{ $infaqs->links() }}</div>
            @endif

            <div class="mt-10 text-center">
                <a href="{{ route('public.infaq.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <i class="fas fa-hand-holding-heart" aria-hidden="true"></i> Ikut Berinfaq Sekarang
                </a>
            </div>
        </div>
    </section>

    <script>
        // Animasi hitung naik untuk kartu statistik di header — murni kosmetik, jalan sekali saat halaman dibuka.
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-count-up]').forEach(function (el) {
                const target = parseInt(el.getAttribute('data-count-up'), 10) || 0;
                const prefix = el.getAttribute('data-prefix') || '';
                const duration = 1200;
                const start = performance.now();

                function tick(now) {
                    const progress = Math.min(1, (now - start) / duration);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const value = Math.round(target * eased);
                    el.textContent = prefix + value.toLocaleString('id-ID');
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    }
                }
                requestAnimationFrame(tick);
            });
        });
    </script>
@endsection
