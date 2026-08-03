@extends('layouts.app')

@section('title', 'Infaq')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Infaq</h1>

        @if ($activeCampaigns->isNotEmpty())
            <div class="mb-8 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-700">Campaign Infaq Aktif</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($activeCampaigns as $campaign)
                        <a href="{{ route('public.infaq.campaign', $campaign->slug) }}"
                            class="block rounded-lg border border-gray-100 p-4 transition hover:shadow-md">
                            <p class="font-medium text-gray-800">{{ $campaign->title }}</p>
                            <x-quota-progress :booked="$campaign->collected_amount" :total="$campaign->target_amount" />
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @auth
            <form method="POST" action="{{ route('public.infaq.store') }}"
                class="space-y-4 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Kategori (Opsional)</label>
                    <select name="category_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Umum / Tidak terikat kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Campaign (Opsional)</label>
                    <select name="campaign_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Tidak terikat campaign tertentu</option>
                        @foreach ($activeCampaigns as $campaign)
                            <option value="{{ $campaign->id }}" @selected(old('campaign_id', request('campaign_id')) == $campaign->id)>{{ $campaign->title }}</option>
                        @endforeach
                    </select>
                    @error('campaign_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nominal Infaq (Rp)</label>
                    <input type="number" name="amount" min="{{ $settings->min_infaq_amount ?? 10000 }}"
                        value="{{ old('amount') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                        placeholder="Contoh: 50000">
                    @error('amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous'))>
                    Sembunyikan nama saya (tampilkan sebagai "Hamba Allah")
                </label>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Catatan/Doa (Opsional)</label>
                    <textarea name="message" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('message') }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                    <div class="space-y-2 text-sm">
                        <label
                            class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 p-3 hover:border-emerald-600">
                            <input type="radio" name="payment_method" value="midtrans" @checked(old('payment_method', 'midtrans') == 'midtrans')>
                            <span>Pembayaran Online (Kartu, GoPay, QRIS, dll)</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 p-3 hover:border-emerald-600">
                            <input type="radio" name="payment_method" value="manual_transfer" @checked(old('payment_method') == 'manual_transfer')>
                            <span>Transfer Bank Manual (verifikasi Admin, maksimal 1x24 jam)</span>
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-emerald-700 py-2.5 text-sm font-medium text-white hover:bg-emerald-800">
                    Lanjutkan Infaq
                </button>
            </form>
        @else
            <div class="rounded-xl bg-amber-50 p-5 text-center text-sm text-amber-700">
                Silakan <a href="{{ route('login') }}" class="font-semibold underline">masuk</a> atau
                <a href="{{ route('register') }}" class="font-semibold underline">daftar</a> terlebih dahulu untuk melanjutkan
                infaq.
            </div>
        @endauth
    </div>
@endsection
